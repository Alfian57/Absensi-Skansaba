<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyMakeAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Attendance For Student Daily Attendance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $now->setTimezone('Asia/Jakarta');
        $now = $now->toTimeString();

        $attendances = Attendance::where('return_time', null)
            ->where('present_date', '!=', date('Y-m-d'))
            ->whereIn('desc', ['masuk', 'terlambat'])
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->update([
                'student_id' => $attendance->student_id,
                'desc' => 'masuk (bolos)',
                'present_date' => $attendance->present_date,
                'present_time' => $attendance->present_time,
                'return_time' => $attendance->return_time,
            ]);
        }

        $date = Attendance::orderBy('present_date', 'DESC')->first();
        if ($date != null) {
            $date = $date->present_date;

            if ($date != date('Y-m-d')) {

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
        } else {
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
}
