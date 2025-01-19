<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/subjects/', 'Mata Pelajaran');

        $subjects = Subject::latest();

        // if (request('search')) {
        //     $subjects->where('name', 'like', '%' . $_GET['search'] . "%");
        // }

        $data = [
            'title' => 'Semua Mata Pelajaran',
            'subjects' => $subjects->get(),
        ];

        return view('subject.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/subjects/create', 'Tambah Mata Pelajaran');

        $data = [
            'title' => 'Tambah Mata Pelajaran',
        ];

        return view('subject.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:subjects|max:255',
        ]);

        $validatedData['slug'] = Str::slug($request->name);

        Subject::create($validatedData);

        return redirect('/admin/subjects')->with('success', 'Mata Pelajaran Baru Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        Helper::addHistory('/admin/subjects/'.$subject->slug.'/edit', 'Ubah Mata Pelajaran');

        $data = [
            'title' => 'Edit Data Mata Pelajaran',
            'subject' => $subject,
        ];

        return view('subject.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        if ($request->oldName === $request->name) {
            return back()->with('nameError', 'Data Not Changed');
        } else {
            $validatedData = $request->validate([
                'name' => 'required|max:255|unique:subjects',
            ]);

            $validatedData['slug'] = Str::slug($request->name);

            Subject::where('id', $subject->id)->update($validatedData);
        }

        return redirect('/admin/subjects')->with('success', 'Data Kelas Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        Subject::destroy($subject->id);

        return redirect('/admin/subjects')->with('success', 'Mata Pelajaran '.$subject->name.' Berhasil Dihapus');
    }
}
