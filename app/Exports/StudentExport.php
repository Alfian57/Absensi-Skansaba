<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $students = Student::select('nisn', 'nis', 'name', 'gender', 'address')->get();

        foreach ($students as $student) {
            if ($student->gender == '0') {
                $student->gender = 'Laki-laki';
            } else {
                $student->gender = 'Perempuan';
            }
        }

        return $students;
    }

    public function headings(): array
    {
        return ['NISN', 'NIS', 'Nama', 'Jenis Kelamin', 'Alamat'];
    }
}
