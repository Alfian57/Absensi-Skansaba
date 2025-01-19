<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
                UserSeeder::class,
                GradeSeeder::class,
                SubjectSeeder::class,
                StudentSeeder::class,
            ]
        );

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
                'present_date' => date('Y-m-d'),
                'present_time' => $now,
            ]);
        }
    }
}
