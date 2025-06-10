<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Employee;
use App\Exports\GajiExport;
use App\Exports\AdminGajiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Presence;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $month = $request->get('month') ?? date('Y-m');
            $currentMonth = Carbon::now()->format('Y-m');
            
            // Validate month format first
            if (!$this->isValidMonthFormat($month)) {
                $month = date('Y-m');
            }
            
            $selected = Carbon::createFromFormat('Y-m', $month);
            $current = Carbon::now();
            
            \Log::info('GajiController@index month filter', [
                'selected' => $selected->format('Y-m'),
                'current' => $current->format('Y-m'),
            ]);

            // Validate month range first before processing
            $validationResult = $this->validateMonthRange($selected, $current);
            if (!$validationResult['valid']) {
                return view('admin.gaji.index', [
                    'employees' => collect([]),
                    'attendanceData' => [],
                    'month' => $month,
                ])->with('warning', 'Bulan yang dipilih tidak valid atau tidak ada data.');
            }

            // Ensure gaji records exist
            $this->ensureGajiRecordsExist($month);

            // Get employees with proper error handling
            $employees = $this->getEmployeesWithGaji($month, $request);
            
            if ($employees->isEmpty()) {
                return view('admin.gaji.index', [
                    'employees' => collect([]),
                    'attendanceData' => [],
                    'month' => $month,
                ])->with('info', 'Tidak ada data karyawan untuk bulan ini.');
            }

            // Calculate workdays
            $workdaysData = $this->calculateWorkdays($month);
            
            // Get attendance data
            $attendanceData = $this->getOptimizedAttendanceData($employees, $month, $workdaysData);

            return view('admin.gaji.index', [
                'employees' => $employees,
                'attendanceData' => $attendanceData,
                'month' => $month,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in GajiController@index: ' . $e->getMessage(), [
                'stack_trace' => $e->getTraceAsString(),
                'month' => $month ?? 'unknown',
                'request_data' => $request->all()
            ]);
            
            return view('admin.gaji.index', [
                'employees' => collect([]),
                'attendanceData' => [],
                'month' => $month ?? date('Y-m'),
            ])->with('error', 'Terjadi kesalahan saat memuat data gaji. Silakan coba lagi.');
        }
    }

    /**
     * Validate month format
     */
    private function isValidMonthFormat($month)
    {
        return preg_match('/^\d{4}-\d{2}$/', $month) && 
               checkdate(substr($month, 5, 2), 1, substr($month, 0, 4));
    }

    /**
     * Get employees with gaji data
     */
    private function getEmployeesWithGaji($month, $request)
    {
        try {
            return Employee::with(['gajis' => function($query) use ($month) {
                $query->where('periode_bayar', $month);
            }])
            ->select('employee_id', 'name', 'email', 'gaji_pokok')
            ->paginate(10)
            ->appends($request->except('page'));
        } catch (\Exception $e) {
            \Log::error('Error getting employees with gaji: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Bulk ensure gaji records exist with better error handling
     */
    private function ensureGajiRecordsExist($month)
    {
        try {
            // Use DB transaction for better performance and consistency
            DB::transaction(function () use ($month) {
                // Get employees that don't have gaji records for this month
                $employeesWithoutGaji = Employee::whereNotExists(function ($query) use ($month) {
                    $query->select(DB::raw(1))
                          ->from('gajis')
                          ->whereColumn('gajis.employee_id', 'employees.employee_id')
                          ->where('gajis.periode_bayar', $month);
                })->get();

                // Bulk insert new gaji records
                if ($employeesWithoutGaji->isNotEmpty()) {
                    $gajiData = $employeesWithoutGaji->map(function ($employee) use ($month) {
                        return [
                            'employee_id' => $employee->employee_id,
                            'periode_bayar' => $month,
                            'gaji_pokok' => $employee->gaji_pokok ?? 0,
                            'komponen_tambahan' => 0,
                            'potongan' => 0,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray();

                    // Use insert instead of create for better performance
                    Gaji::insert($gajiData);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Error ensuring gaji records exist: ' . $e->getMessage());
            // Don't throw exception, just log it
        }
    }

    /**
     * Calculate workdays with error handling
     */
    private function calculateWorkdays($month)
    {
        try {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $start = Carbon::createFromDate($year, $monthNum, 1);
            $end = $start->copy()->endOfMonth();

            // Get holidays with error handling
            $holidays = [];
            try {
                $holidays = Holiday::whereBetween('date', [$start->toDateString(), $end->toDateString()])
                    ->pluck('date')
                    ->toArray();
            } catch (\Exception $e) {
                \Log::warning('Error getting holidays: ' . $e->getMessage());
            }

            $workdays = 0;
            $totalDays = $start->daysInMonth;
            
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->isWeekend() || in_array($date->toDateString(), $holidays)) {
                    continue;
                }
                $workdays++;
            }

            return [
                'workdays' => $workdays,
                'total_days' => $totalDays,
                'start' => $start,
                'end' => $end
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating workdays: ' . $e->getMessage());
            // Return default values
            return [
                'workdays' => 22, // Default workdays
                'total_days' => 30, // Default total days
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth()
            ];
        }
    }

    /**
     * Get attendance data with improved error handling
     */
    private function getOptimizedAttendanceData($employees, $month, $workdaysData)
    {
        try {
            // Handle empty employees collection
            if ($employees->isEmpty()) {
                return [];
            }

            $employeeIds = $employees->pluck('employee_id')->filter()->toArray();
            
            if (empty($employeeIds)) {
                return [];
            }

            // Get presence data with error handling
            $presenceData = collect([]);
            try {
                $presenceData = DB::table('presences')
                    ->select(
                        'employee_id',
                        'status',
                        DB::raw('COUNT(*) as count')
                    )
                    ->whereIn('employee_id', $employeeIds)
                    ->whereBetween('date', [$workdaysData['start']->toDateString(), $workdaysData['end']->toDateString()])
                    ->groupBy('employee_id', 'status')
                    ->get()
                    ->groupBy('employee_id');
            } catch (\Exception $e) {
                \Log::error('Error getting presence data: ' . $e->getMessage());
            }

            $attendanceData = [];
            
            foreach ($employees as $employee) {
                if (!$employee->employee_id) {
                    continue;
                }

                $employeePresences = $presenceData->get($employee->employee_id, collect());
                
                // Convert to associative array for easier access
                $statusCounts = [];
                foreach ($employeePresences as $presence) {
                    $statusCounts[$presence->status] = $presence->count;
                }
                
                $hadir = $statusCounts['present'] ?? 0;
                $telat = $statusCounts['late'] ?? 0;
                $absen = $statusCounts['absent'] ?? 0;
                $onLeave = $statusCounts['on_leave'] ?? 0;
                $libur = $statusCounts['libur'] ?? 0;
                $notCheckedIn = $statusCounts['not_checked_in'] ?? 0;
                
                $absentDays = max(0, $workdaysData['workdays'] - ($hadir + $telat + $onLeave));
                
                $attendanceData[$employee->employee_id] = [
                    'hadir' => $hadir,
                    'telat' => $telat,
                    'absen' => $absen,
                    'on_leave' => $onLeave,
                    'libur' => $libur,
                    'not_checked_in' => $notCheckedIn,
                    'days_worked' => $hadir + $telat,
                    'absent_days' => $absentDays,
                    'workdays' => $workdaysData['workdays'],
                    'total_days' => $workdaysData['total_days'],
                ];
            }

            return $attendanceData;
        } catch (\Exception $e) {
            \Log::error('Error getting optimized attendance data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate month range with better error handling
     */
    private function validateMonthRange($selected, $current)
    {
        try {
            $firstMonth = $this->getFirstDataMonth();
            
            if (!$firstMonth || $selected->lt($firstMonth) || $selected->gt($current)) {
                return ['valid' => false];
            }
            
            return ['valid' => true];
        } catch (\Exception $e) {
            \Log::error('Error validating month range: ' . $e->getMessage());
            return ['valid' => false];
        }
    }

    /**
     * Get first data month with error handling
     */
    private function getFirstDataMonth()
    {
        static $firstMonth = null;
        
        if ($firstMonth === null) {
            try {
                $firstGajiDate = null;
                $firstPresenceDate = null;

                try {
                    $firstGajiDate = Gaji::min('periode_bayar');
                } catch (\Exception $e) {
                    \Log::warning('Error getting first gaji date: ' . $e->getMessage());
                }

                try {
                    $firstPresenceDate = Presence::min('date');
                } catch (\Exception $e) {
                    \Log::warning('Error getting first presence date: ' . $e->getMessage());
                }
                
                $dates = array_filter([$firstGajiDate, $firstPresenceDate]);
                
                if (!empty($dates)) {
                    $earliestDate = min($dates);
                    $firstMonth = Carbon::createFromFormat('Y-m', substr($earliestDate, 0, 7));
                } else {
                    $firstMonth = Carbon::now()->subMonths(12); // Default to 12 months ago
                }
            } catch (\Exception $e) {
                \Log::error('Error getting first data month: ' . $e->getMessage());
                $firstMonth = Carbon::now()->subMonths(12);
            }
        }
        
        return $firstMonth;
    }

    // Rest of the methods remain the same...
    public function create()
    {
        try {
            $karyawans = Employee::all();
            return view('admin.gaji.create', compact('karyawans'));
        } catch (\Exception $e) {
            \Log::error('Error in create method: ' . $e->getMessage());
            return redirect()->route('admin.gaji.index')->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,employee_id',
                'gaji_pokok' => 'required|numeric',
                'periode_bayar' => 'required|string',
            ]);

            Gaji::create($request->all());

            return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error storing gaji: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data gaji.');
        }
    }

    public function myGaji()
    {
        try {
            $gajis = Gaji::where('employee_id', auth()->user()->employee_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

            return view('employee.gaji.index', compact('gajis'));
        } catch (\Exception $e) {
            \Log::error('Error in myGaji: ' . $e->getMessage());
            return view('employee.gaji.index', ['gajis' => collect([])]);
        }
    }

    public function export()
    {
        try {
            return Excel::download(new GajiExport(auth()->user()->employee_id), 'gaji.xlsx');
        } catch (\Exception $e) {
            \Log::error('Error exporting gaji: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh data gaji.');
        }
    }

    public function exportAll()
    {
        try {
            $filename = 'data_gaji_karyawan_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new AdminGajiExport(), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting all gaji: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh data gaji.');
        }
    }
}