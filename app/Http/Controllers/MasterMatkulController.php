<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\Matkul;

class MasterMatkulController extends Controller
{
    //
    public function index(){
        $arr = [];

        $data = Matkul::all();

        $arr['active'] = "master_matkul";
        $arr['list_matkul'] = $data;

        return view('pages.master.matkul.index', $arr);
    }

    public function save_add(Request $req){
        $validate = $req->validate([
            "kode_matkul" => [
                'required',
                Rule::unique('matkul', 'kode_matkul')
            ],
            "nama_matkul" => [
                'required',
            ]
        ]);

        $insert = Matkul::create([
            "kode_matkul" => $req->kode_matkul,
            "nama_matkul" => $req->nama_matkul,
            "status" => $req->status,
            "keterangan" => $req->keterangan,
            "created_at" => date('Y-m-d'),
        ]);

        return redirect('/master-data/matkul');
    }
}
