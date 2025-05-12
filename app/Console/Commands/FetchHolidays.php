<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Holiday;
use Carbon\Carbon;

class FetchHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:fetch {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Indonesian holidays from APIs and merge with official list, then store.';

    protected function fetchFromNagerDate($year)
    {
        $response = Http::withoutVerifying()->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/ID");
        if (!$response->successful()) return [];
        $holidays = [];
        foreach ($response->json() as $holiday) {
            $holidays[$holiday['date']] = [
                'date' => $holiday['date'],
                'name' => $holiday['localName'],
                'description' => $holiday['name']
            ];
        }
        return $holidays;
    }

    protected function fetchFromCalendarific($year)
    {
        $apiKey = env('CALENDARIFIC_API_KEY');
        if (!$apiKey) return [];
        $response = Http::withoutVerifying()->get('https://calendarific.com/api/v2/holidays', [
            'api_key' => $apiKey,
            'country' => 'ID',
            'year' => $year
        ]);
        if (!$response->successful()) return [];
        $holidays = [];
        foreach ($response->json('response.holidays') as $holiday) {
            $date = $holiday['date']['iso'];
            $holidays[$date] = [
                'date' => $date,
                'name' => $holiday['name'],
                'description' => $holiday['description'] ?? $holiday['name']
            ];
        }
        return $holidays;
    }

    protected function officialIndonesianHolidays($year)
    {
        // Update this list every year based on SKB 3 Menteri
        if ($year == 2025) {
            return [
                ['date' => '2025-01-01', 'name' => 'Tahun Baru Masehi', 'description' => 'Tahun Baru 2025'],
                ['date' => '2025-01-29', 'name' => 'Isra Miraj Nabi Muhammad SAW', 'description' => 'Isra Miraj'],
                ['date' => '2025-01-29', 'name' => 'Cuti Bersama Isra Miraj', 'description' => 'Cuti Bersama'],
                ['date' => '2025-01-29', 'name' => 'Tahun Baru Imlek 2576', 'description' => 'Imlek'],
                ['date' => '2025-03-29', 'name' => 'Hari Suci Nyepi', 'description' => 'Nyepi'],
                ['date' => '2025-04-18', 'name' => 'Wafat Isa Almasih', 'description' => 'Jumat Agung'],
                ['date' => '2025-04-20', 'name' => 'Hari Paskah', 'description' => 'Paskah'],
                ['date' => '2025-05-01', 'name' => 'Hari Buruh Internasional', 'description' => 'Hari Buruh'],
                ['date' => '2025-05-12', 'name' => 'Hari Raya Waisak', 'description' => 'Waisak'],
                ['date' => '2025-05-29', 'name' => 'Kenaikan Isa Almasih', 'description' => 'Kenaikan Isa Almasih'],
                ['date' => '2025-06-06', 'name' => 'Idul Fitri 1446 H (Hari Pertama)', 'description' => 'Idul Fitri'],
                ['date' => '2025-06-07', 'name' => 'Idul Fitri 1446 H (Hari Kedua)', 'description' => 'Idul Fitri'],
                ['date' => '2025-06-08', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Cuti Bersama'],
                ['date' => '2025-06-09', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Cuti Bersama'],
                ['date' => '2025-06-10', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Cuti Bersama'],
                ['date' => '2025-07-17', 'name' => 'Hari Raya Idul Adha', 'description' => 'Idul Adha'],
                ['date' => '2025-07-27', 'name' => 'Tahun Baru Islam 1447 H', 'description' => 'Tahun Baru Islam'],
                ['date' => '2025-08-17', 'name' => 'Hari Kemerdekaan RI', 'description' => 'Hari Kemerdekaan'],
                ['date' => '2025-09-05', 'name' => 'Maulid Nabi Muhammad SAW', 'description' => 'Maulid Nabi'],
                ['date' => '2025-12-25', 'name' => 'Hari Raya Natal', 'description' => 'Natal'],
                ['date' => '2025-12-26', 'name' => 'Cuti Bersama Natal', 'description' => 'Cuti Bersama'],
            ];
        }
        if ($year == 2024) {
            return [
                ['date' => '2024-01-01', 'name' => 'Tahun Baru Masehi', 'description' => 'Tahun Baru 2024'],
                ['date' => '2024-02-08', 'name' => 'Isra Miraj Nabi Muhammad SAW', 'description' => 'Isra Miraj'],
                ['date' => '2024-02-10', 'name' => 'Tahun Baru Imlek 2575', 'description' => 'Imlek'],
                ['date' => '2024-03-11', 'name' => 'Hari Suci Nyepi', 'description' => 'Nyepi'],
                ['date' => '2024-03-29', 'name' => 'Wafat Isa Almasih', 'description' => 'Jumat Agung'],
                ['date' => '2024-03-31', 'name' => 'Hari Paskah', 'description' => 'Paskah'],
                ['date' => '2024-04-10', 'name' => 'Idul Fitri 1445 H (Hari Pertama)', 'description' => 'Idul Fitri'],
                ['date' => '2024-04-11', 'name' => 'Idul Fitri 1445 H (Hari Kedua)', 'description' => 'Idul Fitri'],
                ['date' => '2024-04-12', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Cuti Bersama'],
                ['date' => '2024-04-15', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Cuti Bersama'],
                ['date' => '2024-05-01', 'name' => 'Hari Buruh Internasional', 'description' => 'Hari Buruh'],
                ['date' => '2024-05-09', 'name' => 'Kenaikan Isa Almasih', 'description' => 'Kenaikan Isa Almasih'],
                ['date' => '2024-05-23', 'name' => 'Hari Raya Waisak', 'description' => 'Waisak'],
                ['date' => '2024-06-01', 'name' => 'Hari Lahir Pancasila', 'description' => 'Hari Lahir Pancasila'],
                ['date' => '2024-06-17', 'name' => 'Hari Raya Idul Adha', 'description' => 'Idul Adha'],
                ['date' => '2024-07-07', 'name' => 'Tahun Baru Islam 1446 H', 'description' => 'Tahun Baru Islam'],
                ['date' => '2024-08-17', 'name' => 'Hari Kemerdekaan RI', 'description' => 'Hari Kemerdekaan'],
                ['date' => '2024-09-15', 'name' => 'Maulid Nabi Muhammad SAW', 'description' => 'Maulid Nabi'],
                ['date' => '2024-12-25', 'name' => 'Hari Raya Natal', 'description' => 'Natal'],
                ['date' => '2024-12-26', 'name' => 'Cuti Bersama Natal', 'description' => 'Cuti Bersama'],
            ];
        }
        return [];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $this->info("Fetching holidays for year {$year}...");

        // Fetch from both APIs
        $nager = $this->fetchFromNagerDate($year);
        $calendarific = $this->fetchFromCalendarific($year);

        // Merge, prioritizing Calendarific for duplicates
        $holidays = array_merge($nager, $calendarific);

        // Merge with official list (overrides API if duplicate)
        foreach ($this->officialIndonesianHolidays($year) as $holiday) {
            $holidays[$holiday['date']] = $holiday;
        }

        $count = 0;
        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']],
                [
                    'name' => $holiday['name'],
                    'description' => $holiday['description']
                ]
            );
            $count++;
        }

        // Add weekends
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);
        while ($startDate <= $endDate) {
            if ($startDate->isWeekend()) {
                Holiday::updateOrCreate(
                    ['date' => $startDate->toDateString()],
                    [
                        'name' => 'Weekend - ' . $startDate->format('l'),
                        'description' => 'Weekend'
                    ]
                );
                $count++;
            }
            $startDate->addDay();
        }

        $this->info("Successfully added/updated $count holidays and weekends!");
    }
}
