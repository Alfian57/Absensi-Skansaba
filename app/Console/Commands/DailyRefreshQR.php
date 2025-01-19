<?php

namespace App\Console\Commands;

use App\Models\OtherData;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DailyRefreshQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh QR For Attendance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        OtherData::where('name', 'QR Absensi Masuk')->update([
            'value' => Str::random(20),
        ]);

        OtherData::where('name', 'QR Absensi Pulang')->update([
            'value' => Str::random(20),
        ]);
    }
}
