<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\OtherData;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                OtherDataSeeder::class,
                UserSeeder::class
            ]
        );

        Grade::factory(48)->create();
        Teacher::factory(50)->create();
        Student::factory(10)->create();

        $now = Carbon::now();
        $now->setTimezone('Asia/Jakarta');
        $now = $now->toTimeString();

        $studentsId = Student::pluck('id');
        foreach ($studentsId as $id) {
            Attendance::create([
                'student_id' => $id,
                'desc' => 'alpha',
                'present_date' => date("Y-m-d"),
                'present_time' => $now
            ]);
        }
    }
}