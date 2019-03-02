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

use Barryvdh\DomPDF\Facade;
class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['magang'] = Magang::with(['konstruktor.user','pembimbing_asal'])->where('user_id', auth()->user()->id)->first();
        $data['biodata'] = Biodata::where('user_id',auth()->user()->id)->first();
        $data['konstruktor'] = User::whereHas('roles', function($query){
            $query->where('name','konstruktor');
        })->get();
        //$data['penilaian'] = $this->getnilai(Magang::);
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
            'surat_permohonan' => 'required|mimes:pdf',
            'asal' => 'required'
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
       $magang->asal = $request->input('asal');
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
            // 'user_id' => 'required'
        ]);
        //dd($request->input());
        // $user = User::whereHas('roles', function($query){
        //     $query->where('name','konstruktor');
        // })->findOrFail($request->input('user_id'));
        //dd($user);
        $magang = Magang::where('user_id', auth()->user()->id)->where('is_completed',0)->first(); //hanya bisa mengedit pembimbing jika magang belum selesai

        // $konstruktor = Konstruktor::firstOrNew(['magang_id'=>$magang->id]);
        // $konstruktor->user_id=$user->id;
        // $konstruktor->save();

        $pembimbing = \App\PembimbingAsal::firstOrNew(['magang_id'=>$magang->id]);
        $pembimbing->name = $request->input('pembimbing_asal');
        $pembimbing->save();
        return redirect('/magang')->with('success', 'Berhasil edit data pembimbing');

    }
    public function getnilai($magang_id){
        $aspek = \App\AspekNilai::all();
        foreach($aspek as $aspek_){
            foreach($aspek_->sub_aspek_nilai as $subaspek_){
                $penilaian = $subaspek_->penilaian()->where('magang_id',$magang_id)->first();
                $subaspek_->nilai = $penilaian!=null ? $penilaian->nilai:0;
                $subaspek_->nilai_huruf = $this->konversiNilai($subaspek_->nilai);
            }
        }
        return ['magang'=>\App\Magang::with('konstruktor.user','users')->findOrFail($magang_id), 'penilaian'=>$aspek];
    }
    public function downloadSertifikat($magang_id){
        $magang = Magang::whereHas('users',function($query){
            $query->where('user_id',auth()->user()->id);
        })->with('users')->findOrFail($magang_id);

        $nama = $magang->users->name;
        $gambar = asset('assets/images/sertifikat.jpg');
        //return $gambar;
        $image = imagecreatefromjpeg('https://drive.google.com/uc?export=view&id=12HA9hVJQZHX38EL3zvP1AcfqadYh2TEB');
        $white = imageColorAllocate($image, 255, 255, 255);
        $black = imageColorAllocate($image, 0, 0, 0);
        $font = storage_path('QuinchoScript_PersonalUse.ttf');
        $size = 50;
        //definisikan lebar gambar agar posisi teks selalu ditengah berapapun jumlah hurufnya
        $image_width = imagesx($image);  
        //membuat textbox agar text centered
        $text_box = imagettfbbox($size,0,$font,$nama);
        $text_width = $text_box[2]-$text_box[0]; // lower right corner - lower left corner
        $text_height = $text_box[3]-$text_box[1];
        $x = ($image_width/2) - ($text_width/2);

        //generate sertifikat beserta namanya
        imagettftext($image, $size, 0, $x, 430, $black, $font, $nama);
        $font = storage_path('times.ttf');
        imagettftext($image, 21, 0, 600, 542, $black, $font, $magang->from);
        imagettftext($image, 21, 0, 809, 542, $black, $font, $magang->until);

        //tampilkan di browser
        header("Content-type:  image/jpeg");
        imagejpeg($image);
        imagedestroy($image);
        

    }
    public function test(){
         $data['magang'] = Magang::with(['users'])->first();
        return view('pdf.penilaian',$data);
       $pdf = \PDF::loadView('pdf.penilaian');
        return $pdf->stream('invoice.pdf');
    }
}
