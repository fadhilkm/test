<?php

use Illuminate\Database\Seeder;
use App\AspekNilai;
use App\SubAspekNilai;
class AspekNilaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new AspekNilai;
        $data->name = "Penilaian Non Teknis";
        $data->save();

        $sub_aspek = ['Kedisplinan','Kerjasama','Inisiatif','Kreativitas','Tanggung jawab','Kemandirian','Kejujuran','Kerapian'];
        foreach($sub_aspek as $njir){
        	$fck = new SubAspekNilai(['name'=>$njir]);
        	$data->sub_aspek_nilai()->save($fck);
        }

        $data = new AspekNilai;
        $data->name = "Penilaian Teknis";
        $data->save();
        $sub_aspek = ['Orientasi Kerja','Orientasi Pengembangan Multimedia  Pembelajaran','Perancangan Konten Multimedia Pembelajaran','Pembuatan Multimedia Pembelajaran'];
        foreach($sub_aspek as $njir){
        	$fck = new SubAspekNilai(['name'=>$njir]);
        	$data->sub_aspek_nilai()->save($fck);
        }
    }
}
