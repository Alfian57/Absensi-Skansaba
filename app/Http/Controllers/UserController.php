<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/admins', 'Admin');

        $users = User::latest();

        // if (request('search')) {
        //     $users->where('name', 'like', '%' . request('search') . "%")
        //         ->orWhere('username', 'like', '%' . request('search') . '%');
        // }

        $data = [
            'title' => 'Semua Admin',
            'users' => $users->get(),
        ];

        return view('admins.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/admins/create', 'Tambah Admin');

        $data = [
            'title' => 'Tambah Admin',
        ];

        return view('admins.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|max:255|regex:/^\S*$/u|unique:users',
            'password' => 'required|min:8',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['remember_token'] = Str::random(10);

        User::create($validatedData);

        return redirect('/admin/admins')->with('success', 'Data Admin Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::addHistory('/admin/admins/'.$id.'/edit', 'Ubah Admin');

        $data = [
            'title' => 'Edit Data Admin',
            'user' => User::where('id', $id)->first(),
        ];

        return view('admins.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => 'required|regex:/^\S*$/u|max:255',
            'email' => 'required|email',
        ];

        if ($request->oldUsername !== $request->username) {
            $rules['username'] = 'required|regex:/^\S*$/u|max:255|unique:users';
        }

        if ($request->oldEmail !== $request->email) {
            $rules['email'] = 'required|email|unique:users';
        }

        $validatedData = $request->validate($rules);
        $validatedData['password'] = $request->password;
        $validatedData['remember_token'] = $request->token;

        User::where('id', $id)->update($validatedData);

        return redirect('/admin/admins')->with('success', 'Data Admin Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('/admin/admins')->with('success', 'User Berhasil Dihapus');
    }
}
