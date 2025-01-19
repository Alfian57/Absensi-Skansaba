<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class MeController extends Controller
{
    public function changePassword()
    {
        session()->push('history', [
            'route' => 'admin/changePassword',
            'name' => 'Ganti Password',
        ]);

        $user = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
        } else {
            $user = Auth::guard('user')->user();
        }
        $data = [
            'title' => 'Ganti Password',
            'user' => $user,
        ];

        return view('login.changePass', $data);
    }

    public function updatePassword(Request $request)
    {
        if (! Hash::check($request->oldPassword, $request->password)) {
            Alert::error('Password Lama Salah');

            return back();
        }

        $request->validate([
            'newPassword' => 'required|min:8',
        ]);

        if (Auth::guard('teacher')->check()) {
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->newPassword),
            ]);
        } else {
            Teacher::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->newPassword),
            ]);
        }

        return redirect('/admin/home')->with('success', 'Password Admin '.Auth::user()->name.' Berhasil Diubah');
    }

    public function changePic()
    {
        session()->push('history', [
            'route' => 'admin/changePic',
            'name' => 'Ganti Foto',
        ]);

        $user = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
        } else {
            $user = Auth::guard('user')->user();
        }

        $data = [
            'title' => 'Ganti Foto Profile',
            'user' => $user,
        ];

        return view('login.changePic', $data);
    }

    public function updatePic(Request $request)
    {
        $validatedData = $request->validate([
            'profile_pic' => 'image|file|max:10000',
        ]);

        if ($request->deleteImage == 'true') {
            Storage::delete($request->old_profile_pic);
            $validatedData['profile_pic'] = null;
        } else {
            if ($request->profile_pic !== $request->old_profile_pic) {
                if ($request->profile_pic) {
                    if (Auth::guard('user')) {
                        $validatedData['profile_pic'] = $request->file('profile_pic')->store('user_profile_pic', 'public');
                    } else {
                        $validatedData['profile_pic'] = $request->file('profile_pic')->store('teacher_profile_pic', 'public');
                    }
                }
                if ($request->old_profile_pic) {
                    Storage::delete($request->old_profile_pic);
                }
            } else {
                $validatedData['profile_pic'] = $request->profile_pic;
            }
        }

        $profilePic = $validatedData['profile_pic'];

        if (Auth::guard('user')) {
            User::where('id', Auth::guard('user')->user()->id)->update([
                'profile_pic' => $profilePic,
            ]);
        } else {
            Teacher::where('id', Auth::guard('teacher')->user()->id)->update([
                'profile_pic' => $profilePic,
            ]);
        }

        return redirect('/admin/home')->with('success', 'Profile User Berhasil Diperbarui');
    }
}
