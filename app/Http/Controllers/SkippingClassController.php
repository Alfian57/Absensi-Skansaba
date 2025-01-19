<?php

namespace App\Http\Controllers;

use App\Exports\SkippingClassExport;
use App\Helper;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\SkippingClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class SkippingClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/skippingClass', 'Siswa Bolos');

        $attendances = Attendance::whereIn('desc', ['masuk', 'terlambat', 'masuk (bolos)'])
            ->where('present_date', date('Y-m-d'))
            ->pluck('student_id');

        if ($attendances->isEmpty()) {
            $studentsId = [0];
        } else {
            $studentsId = Student::whereIn('id', $attendances)->pluck('id');
        }

        $data = [
            'title' => 'Siswa Bolos',
            'skippingClasses' => SkippingClass::whereIn('student_id', $studentsId)->with('subject', 'student')->get(),
        ];

        return view('skippingClass.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $attendances = Attendance::where('present_date', date('Y-m-d'))
            ->whereIn('desc', ['masuk', 'terlambat', 'masuk (bolos)'])
            ->pluck('student_id');

        $data = [
            'title' => 'Tambah Siswa Bolos',
            'students' => Student::whereIn('id', $attendances)->get(),
            'subjects' => Subject::latest()->get(),
        ];

        return view('skippingClass.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
        ]);

        SkippingClass::create($validatedData);

        Alert::success('Berhasil', 'Berhasil Menambahkan Data');

        return redirect('/admin/skippingClass');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SkippingClass::destroy($id);

        return redirect('/admin/skippingClass')->with('success', 'Data Berhasil Dihapus');
    }

    public function exportExcel()
    {
        if (! request('grade') || ! request('date')) {
            return redirect()->back();
        }

        $grade = Grade::where('slug', request('grade'))->first();
        $fileName = "Rekap Siswa Bolos Kelas $grade->name | ".request('date').'.xlsx';

        return Excel::download(new SkippingClassExport(request('grade'), request('date')), $fileName);
    }
}
