<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nis' => 'required',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            $data = [
                'message' => 'Validasi Gagal',
                'error' => $validate->errors(),
                'data' => null,
            ];

            return response()->json($data, 402);
        } else {
            $credential = request(['nis', 'password']);

            if (Auth::guard('student')->attempt($credential)) {
                $studentResponse = Student::where('nis', $request->nis)
                    ->select('id', 'nisn', 'nis', 'name', 'date_of_birth', 'gender', 'address', 'grade_id as grade', 'entry_year', 'profile_pic')
                    ->first();
                $studentStatus = Student::where('nis', $request->nis)
                    ->select('already_login')
                    ->first();

                if ($studentStatus->already_login == true) {
                    $data = [
                        'message' => 'Akun Sudah Digunakan Di Perangkat Lain',
                        'errors' => null,
                        'data' => null,
                    ];

                    return response()->json($data, 401);
                }

                $grade = Student::where('nis', $request->nis)->select('grade_id')->first();
                $grade = Grade::where('id', $grade->grade_id)->first();
                $studentResponse->grade = $grade->name;

                Student::where('id', $studentResponse->id)->update([
                    'already_login' => true,
                ]);

                $token = $studentResponse->createToken('token')->plainTextToken;

                $data = [
                    'message' => 'Login Berhasil',
                    'errors' => null,
                    'data' => [
                        'student' => $studentResponse,
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                    ],
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'message' => 'NIS atau Password Salah',
                    'errors' => null,
                    'data' => null,
                ];

                return response()->json($data, 401);
            }
        }
    }

    public function logout(Request $request)
    {
        // Student::where('id', auth('sanctum')->user()->id)->update([
        //     'already_login' => false
        // ]);
        // Auth::guard('student')->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $request->user()->currentAccessToken()->delete();
        // Auth::guard('student')->user()->tokens()->delete();
        $data = [
            'message' => 'Logout Berhasil',
            'errors' => null,
            'data' => null,
        ];

        return response()->json($data, 200);
    }

    // public function edit(Request $request, $id)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'name'      => 'required',
    //         'email'      => 'required|email',
    //         'username'  => 'required',
    //         'password'  => 'required',
    //     ]);

    //     if ($validate->fails()) {
    //         $data = [
    //             'success'    => false,
    //             'msg'       => 'Validation error',
    //             'errors'    => $validate->errors(),
    //             'data'      => null
    //         ];

    //         return response()->json($data, 422);
    //     } else {
    //         $user = User::where('id', $id)->update([
    //             'name' => $request['name'],
    //             'email' => $request['email'],
    //             'username' => $request['username'],
    //             'password' => Hash::make($request['password']),
    //         ]);

    //         if ($user) {
    //             $data = [
    //                 'success'    => true,
    //                 'msg'       => 'Edit Admin Berhasil',
    //                 'errors'    => null,
    //                 'data'      => null
    //             ];

    //             return response()->json($data, 200);
    //         } else {
    //             $data = [
    //                 'success'    => false,
    //                 'msg'       => 'Edit Admin Gagal',
    //                 'errors'    => null,
    //                 'data'      => null
    //             ];

    //             return response()->json($data, 200);
    //         }
    //     }
    // }
}
