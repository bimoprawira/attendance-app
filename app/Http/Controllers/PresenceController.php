<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday;

class PresenceController extends Controller
{
    // Change these values to your desired times
    const PRESENT_BEFORE = '09:00'; // Present if check-in before this time
    const LATE_BEFORE = '10:00';    // Late if check-in before this time, otherwise absent
    
    protected function getPresentBeforeTime()
    {
        return env('ATTENDANCE_PRESENT_BEFORE', self::PRESENT_BEFORE);
    }

    protected function getLateBeforeTime()
    {
        return env('ATTENDANCE_LATE_BEFORE', self::LATE_BEFORE);
    }

    protected function isHolidayOrWeekend(Carbon $date)
    {
        // Check if it's weekend (Saturday = 6, Sunday = 0)
        if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
            return [true, 'Weekend - ' . $date->format('l')];
        }

        // Check if it's a holiday
        $holiday = Holiday::getHoliday($date);
        if ($holiday) {
            return [true, $holiday->name];
        }

        return [false, null];
    }

    protected function ensureTodayRecord()
    {
        $employee = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        // Get or create today's record
        $presence = Presence::where('employee_id', $employee->employee_id)
            ->whereDate('date', $today)
            ->first();

        // Check if it's a holiday or weekend
        [$isHoliday, $holidayName] = $this->isHolidayOrWeekend($today);

        if ($isHoliday) {
            if (!$presence) {
                // Create new record with 'libur' status
                $presence = Presence::create([
                    'employee_id' => $employee->employee_id,
                    'date' => $today,
                    'status' => 'libur',
                    'check_in' => null,
                    'check_out' => null
                ]);
            } else {
                // Update existing record to 'libur' status
                $presence->update([
                    'status' => 'libur',
                    'check_in' => null,
                    'check_out' => null
                ]);
            }
            return $presence;
        }

        if (!$presence) {
            // Check if employee is on leave
            $onLeave = Leave::where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->exists();

            // Create presence record with proper status
            $status = 'not_checked_in';
            if ($onLeave) {
                $status = 'on_leave';
            } else if ($now->format('H:i') > $this->getLateBeforeTime()) {
                $status = 'absent';
            }

            // Create presence record
            $presence = Presence::create([
                'employee_id' => $employee->employee_id,
                'date' => $today,
                'status' => $status,
                'check_in' => null,
                'check_out' => null
            ]);
        }

        return $presence;
    }
    
    protected function isAttendanceFormOpen()
    {
        $now = Carbon::now();
        $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
        $formOpenTime = $presentBeforeTime->copy()->subMinutes(30);
        
        return $now->gte($formOpenTime);
    }

    public function index()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Get holiday information first
        [$isHoliday, $holidayName] = $this->isHolidayOrWeekend($today);
        
        // Get or create today's record
        $presence = $this->ensureTodayRecord();

        // Only update to absent if it's not a holiday
        if (!$isHoliday && 
            !$presence->check_in && 
            $presence->status === 'not_checked_in' && 
            $now->format('H:i') > $this->getLateBeforeTime()) {
            $presence->update(['status' => 'absent']);
            $presence->refresh();
        }

        // Calculate form open time
        $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
        $formOpenTime = $presentBeforeTime->copy()->subMinutes(30)->format('H:i');

        return view('employee.presence.index', [
            'status' => $presence->status,
            'presence' => $presence,
            'now' => $now->format('H:i'),
            'presentBefore' => $this->getPresentBeforeTime(),
            'lateBefore' => $this->getLateBeforeTime(),
            'formOpenTime' => $formOpenTime,
            'isHoliday' => $isHoliday,
            'holidayName' => $holidayName
        ]);
    }

    public function checkIn()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses halaman ini.');
        }

        $now = Carbon::now();
        $today = Carbon::today();

        // Check if it's a holiday
        [$isHoliday, $holidayName] = $this->isHolidayOrWeekend($today);
        if ($isHoliday) {
            return back()->with('error', "Hari ini adalah hari libur ($holidayName). Tidak perlu presensi.");
        }

        // Get or create today's record
        $presence = $this->ensureTodayRecord();

        // Check if on leave
        if ($presence->status === 'on_leave') {
            return back()->with('error', 'Anda tidak dapat presensi saat sedang cuti.');
        }

        // Check if already checked in
        if ($presence->check_in) {
            return back()->with('error', 'Anda sudah presensi hari ini.');
        }

        // Check if past late threshold
        if ($now->format('H:i') > $this->getLateBeforeTime()) {
            return back()->with('error', 'Presensi tidak tersedia lagi. Anda ditandai sebagai absen untuk hari ini.');
        }

        // Check if attendance form is not yet open
        if (!$this->isAttendanceFormOpen()) {
            $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
            $formOpenTime = $presentBeforeTime->copy()->subMinutes(30)->format('H:i');
            return back()->with('error', "Attendance form is not open yet. You can check in from {$formOpenTime}.");
        }

        // Determine status based on time
        $status = $now->format('H:i') > $this->getPresentBeforeTime() ? 'late' : 'present';

        // Update presence record
        $presence->update([
            'check_in' => $now,
            'status' => $status
        ]);

        return back()->with('success', 'Presensi masuk berhasil.');
    }

    public function checkOut()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses halaman ini.');
        }

        $now = Carbon::now();
        
        // Get today's record
        $presence = $this->ensureTodayRecord();

        // Check if on leave
        if ($presence->status === 'on_leave') {
            return back()->with('error', 'Anda tidak dapat melakukan presensi keluar saat sedang cuti.');
        }

        // Check if not checked in
        if (!$presence->check_in) {
            return back()->with('error', 'Anda perlu melakukan presensi terlebih dahulu.');
        }

        // Check if already checked out
        if ($presence->check_out) {
            return back()->with('error', 'Anda sudah presensi keluar hari ini.');
        }

        // Update presence record
        $presence->update([
            'check_out' => $now
        ]);

        return back()->with('success', 'Presensi keluar berhasil .');
    }

    public function history()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses halaman ini.');
        }
        
        // First update any old records that should be absent
        $oldRecords = Presence::where('employee_id', $employee->employee_id)
            ->where('status', 'not_checked_in')
            ->where('date', '<', now()->format('Y-m-d'))
            ->get();

        foreach ($oldRecords as $record) {
            $record->update(['status' => 'absent']);
        }

        // Then get paginated records
        $presences = Presence::where('employee_id', $employee->employee_id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('employee.presence.history', compact('presences'));
    }
}
