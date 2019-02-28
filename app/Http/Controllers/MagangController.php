<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Magang;
use App\Surat;
use App\Biodata;
use App\User;
use App\Konstruktor;
class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['magang'] = Magang::with(['konstruktor','pembimbing_asal'])->where('user_id', auth()->user()->id)->where('is_completed',0)->first();
        $data['biodata'] = Biodata::where('user_id',auth()->user()->id)->first();
        $data['konstruktor'] = User::whereHas('roles', function($query){
            $query->where('name','konstruktor');
        })->get();
        
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
            $surat_proposal->jenis_surat_id = \App\JenisSurat::where('name','Surat Proposal')->firstOrFail()->id;
            $surat_proposal->path_upload = $path;
            $surat_proposal->save();
            $surat[] = $surat_proposal->id;
       }
       $path = $request->file('surat_permohonan')->store('surat_permohonan');
        
       $surat_permohonan = new Surat;
       $surat_permohonan->jenis_surat_id = \App\JenisSurat::where('name','Surat Permohonan Magang')->firstOrFail()->id;
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
    public function addkonstruktor(Request $request){
        $request->validate([
            'pembimbing_asal' => 'required',
            'user_id' => 'required'
        ]);
        //dd($request->input());
        $user = User::whereHas('roles', function($query){
            $query->where('name','konstruktor');
        })->findOrFail($request->input('user_id'));
        //dd($user);
        $magang = Magang::where('user_id', auth()->user()->id)->where('is_completed',0)->first();

        $konstruktor = Konstruktor::updateOrCreate(['magang_id'=>$magang->id, 'user_id'=>$user->id]);
        $pembimbing = \App\PembimbingAsal::firstOrNew(['magang_id'=>$magang->id]);
        $pembimbing->name = $request->input('pembimbing_asal');
        $pembimbing->save();
        return redirect('/magang')->with('success', 'Berhasil edit data pembimbing');

    }
    public function test(){
        $asu = \App\Role::get();
         return $asu;
    }
}
