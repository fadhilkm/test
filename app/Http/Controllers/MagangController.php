<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Magang;
use App\Surat;
class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['magang'] = Magang::where('user_id',auth()->user()->id)->first();
        return view('pages.magang', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Magang::where('user_id', auth()->user()->id)->where('is_completed',0)->first();
        if($check!=null)return back()->with('error', 'Data magang sudah ada');
        
        $magang = new Magang;

       $request->validate([
            'from' =>'required|date',
            'until' => 'required|date',
            'surat_permohonan' => 'required|mimes:pdf'
       ]);
       $surat=[];
       if($request->hasFile('proposal')){
            $request->validate([
                 'proposal' => 'required|mimes:pdf'
            ]);
            $path = $request->file('proposal')->store('proposal');     

            $surat_proposal = new Surat;
            $surat_proposal->jenis_surat_id = 2;
            $surat_proposal->path_upload = $path;
            $surat_proposal->save();
            $surat[] = $surat_proposal->id;
       }
       $path = $request->file('surat_permohonan')->store('surat_permohonan');
        
       $surat_permohonan = new Surat;
       $surat_permohonan->jenis_surat_id = 1;
       $surat_permohonan->path_upload = $path;
       $surat_permohonan->save();
       $surat[] = $surat_permohonan->id;

        $magang->user_id = auth()->user()->id;
       $magang->from = $request->from;
       $magang->until = $request->until;
       $magang->save();

       $magang->surats()->sync($surat);
       return redirect('/magang')->with('success',' Sukses mengajukan permohonan magang, silahkan tunggu validasi dari Admin');


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
     * @param  \Illuminate\Http\Request  $request
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
        //
    }
    public function test(){
        return Magang::with('users','surats.jenis_surat')->get();
    }
}
