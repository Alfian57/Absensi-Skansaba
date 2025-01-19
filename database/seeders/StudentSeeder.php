<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::create([
            'nisn' => '0045453942',
            'nis' => '15458',
            'name' => 'Alfian Gading Saputra',
            'date_of_birth' => '2004-09-10',
            'gender' => '0',
            'address' => 'Indonesia',
            'grade_id' => 33,
            'entry_year' => '2020',
            'profile_pic' => null,
            'password' => Hash::make('password'),
            'already_login' => false,
        ]);
    }
}
