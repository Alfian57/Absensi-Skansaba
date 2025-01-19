<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MeController extends Controller
{
    public function profile()
    {
        $id = Auth::guard('sanctum')->user()->id;
        $student = Student::where('id', $id)
            ->select('id', 'nisn', 'nis', 'name', 'date_of_birth', 'gender', 'address', 'grade_id as grade', 'entry_year', 'profile_pic')
            ->first();

        $grade = Grade::where('id', $student->grade)->first();

        $student->grade = $grade->name;

        $data = [
            'message' => 'Berhasil Mendapatkan Data Siswa',
            'errors' => null,
            'data' => [
                'student' => $student,
            ],
        ];

        return response()->json($data, 200);
    }

    public function changePassword(Request $request)
    {
        $id = Auth::guard('sanctum')->user()->id;
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Ada Data yang Masih Kosong',
                'errors' => $validator->errors(),
                'data' => null,
            ];

            return response()->json($data);
        }

        $student = Student::where('id', $id)->first();

        if ($student == null) {
            $data = [
                'message' => 'ID Siswa Tidak Ditemukan',
                'errors' => null,
                'data' => null,
            ];

            return response()->json($data);
        }

        if (! Hash::check($request->oldPassword, $student->password)) {
            $data = [
                'message' => 'Password Lama Salah',
                'errors' => null,
                'data' => null,
            ];

            return response()->json($data);
        }

        Student::where('id', $id)
            ->update([
                'password' => Hash::make($request->newPassword),
            ]);

        $student = Student::where('id', $id)
            ->select('id', 'nisn', 'nis', 'name', 'date_of_birth', 'gender', 'address', 'grade_id as grade', 'entry_year', 'profile_pic')
            ->first();

        $grade = Grade::where('id', $student->grade)->first();

        $student->grade = $grade->name;

        $data = [
            'message' => 'Password Berhasil Diganti',
            'errors' => null,
            'data' => [
                'student' => $student,
            ],
        ];

        return response()->json($data, 200);
    }
}
