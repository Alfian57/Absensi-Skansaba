<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\SkippingClass;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SkippingClassExport implements FromCollection, WithHeadings
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
        $gradesId = Grade::where('slug', $this->grade)->pluck('id');
        $studentsId = Student::whereIn('grade_id', $gradesId)->pluck('id');
        $attendancesStudentsId = Attendance::whereIn('student_id', $studentsId)
            ->where('present_date', $this->date)
            ->pluck('student_id');

        return SkippingClass::whereIn('student_id', $attendancesStudentsId)
            ->join('subjects', 'skipping_classes.subject_id', '=', 'subjects.id')
            ->join('students', 'skipping_classes.student_id', '=', 'students.id')
            ->select('students.name as namaSiswa', 'students.nisn', 'subjects.name as namaPelajaran')
            ->get();
    }

    public function headings(): array
    {
        return ['Nama', 'NISN', 'Mata Pelajaran'];
    }
}
