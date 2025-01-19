<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $grade;

    protected $date;

    public function __construct($grade, $date)
    {
        $this->grade = $grade;
        $this->date = $date;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Attendance::where('attendances.present_date', $this->date)
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('grades', 'grades.id', '=', 'students.grade_id')
            ->where('grades.slug', $this->grade)
            ->select('students.name as nama', 'students.nisn', 'grades.name as kelas', 'attendances.desc')
            ->orderBy('attendances.desc', 'DESC')
            ->get();

        // return $attendances;
    }

    public function headings(): array
    {
        return ['Nama', 'NISN', 'Kelas', 'Keterangan'];
    }
}
