<?php

namespace Database\Seeders;

use App\Models\OtherData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OtherDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtherData::factory()->create([
            'name' => 'QR Absensi Masuk',
            'value' => Str::random(20),
        ]);

        OtherData::factory()->create([
            'name' => 'QR Absensi Pulang',
            'value' => Str::random(20),
        ]);

        OtherData::factory()->create([
            'name' => 'Waktu Absen Mulai',
            'value' => '07:00',
        ]);

        OtherData::factory()->create([
            'name' => 'Waktu Absen Terlambat',
            'value' => '07:30',
        ]);

        OtherData::factory()->create([
            'name' => 'Waktu Absen Berakhir',
            'value' => '07:45',
        ]);

        OtherData::factory()->create([
            'name' => 'Waktu Pulang Mulai',
            'value' => '14:00',
        ]);

        OtherData::factory()->create([
            'name' => 'Hari Masuk',
            'value' => 'Senin, Selasa, Rabu, Kamis, Jumat',
        ]);
    }
}
