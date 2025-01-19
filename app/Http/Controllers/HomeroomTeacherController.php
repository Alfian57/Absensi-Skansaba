<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\StoreHomeroomTeacherRequest;
use App\Http\Requests\UpdateHomeroomTeacherRequest;
use App\Models\Grade;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;

class HomeroomTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/homeroom-teachers', 'Wali Kelas');

        $homeroomTeachers = HomeroomTeacher::latest()->with('teacher', 'grade');

        $data = [
            'title' => 'Semua Wali Kelas',
            'homeroomTeachers' => $homeroomTeachers->get(),
        ];

        return view('homeroomTeacher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/homeroom-teachers/create', 'Tambah Wali Kelas');

        $homeroomTeachers = HomeroomTeacher::pluck('teacher_id');
        if ($homeroomTeachers->isEmpty()) {
            $homeroomTeachers = [0];
        }

        $gradesForShow = HomeroomTeacher::pluck('grade_id');
        if ($gradesForShow->isEmpty()) {
            $gradesForShow = [0];
        }

        $data = [
            'title' => 'Tambah Wali Kelas',
            'teachers' => Teacher::latest()->whereNotIn('id', $homeroomTeachers)->get(),
            'grades' => Grade::latest()->whereNotIn('id', $gradesForShow)->get(),
        ];

        return view('homeroomTeacher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomeroomTeacherRequest $request)
    {
        $validatedData = $request->validate([
            'teacher_id' => 'required',
            'grade_id' => 'required',
        ]);

        HomeroomTeacher::create($validatedData);

        return redirect('/admin/homeroom-teachers')->with('success', 'Data Wali Kelas Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(HomeroomTeacher $homeroomTeacher) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeroomTeacher $homeroomTeacher)
    {
        Helper::addHistory('/admin/homeroom-teachers/'.$homeroomTeacher->id.'/edit', 'Ubah Wali Kelas');

        $homeroomTeachers = HomeroomTeacher::pluck('teacher_id');
        if ($homeroomTeachers->isEmpty()) {
            $homeroomTeachers = [0];
        }

        $gradesForShow = HomeroomTeacher::pluck('grade_id');
        if ($gradesForShow->isEmpty()) {
            $gradesForShow = [0];
        }

        $data = [
            'title' => 'Tambah Wali Kelas',
            'homeroomTeacher' => $homeroomTeacher,
            'teachers' => Teacher::latest()->whereNotIn('id', $homeroomTeachers)->get(),
            'grades' => Grade::latest()->whereNotIn('id', $gradesForShow)->get(),
        ];

        return view('homeroomTeacher.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomeroomTeacherRequest $request, HomeroomTeacher $homeroomTeacher)
    {
        $validatedData = $request->validate([
            'teacher_id' => 'required',
            'grade_id' => 'required',
        ]);

        HomeroomTeacher::where('id', $homeroomTeacher->id)->update($validatedData);

        return redirect('/admin/homeroom-teachers')->with('success', 'Data Wali Kelas Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomeroomTeacher $homeroomTeacher)
    {
        HomeroomTeacher::destroy($homeroomTeacher->id);

        return redirect('/admin/homeroom-teachers')->with('success', 'Data Wali Kelas '.$homeroomTeacher->teacher->name.' Berhasil Dihapus');
    }
}
