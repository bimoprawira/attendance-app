<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Presence;
use App\Models\Holiday;
use Carbon\Carbon;

class FixHolidayPresenceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:fix-holiday-status {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all presence records for holidays to status libur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ?? Carbon::today()->toDateString();
        $this->info("Fixing presence status for holidays on $date...");

        $isHoliday = Holiday::isHoliday($date);
        if (!$isHoliday) {
            $this->info("$date is not a holiday. No changes made.");
            return;
        }

        $count = Presence::whereDate('date', $date)
            ->where('status', '!=', 'libur')
            ->update(['status' => 'libur', 'check_in' => null, 'check_out' => null]);

        $this->info("Updated $count presence records to 'libur' for $date.");
    }
}
