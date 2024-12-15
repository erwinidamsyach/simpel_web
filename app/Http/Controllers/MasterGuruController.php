<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\Guru;
use App\Models\User;
use App\Models\Matkul;

class MasterGuruController extends Controller
{
    //
    public function index(){
        $arr = [];
        $guru = Guru::all();
        $matkul = Matkul::all();

        $arr['active'] = "master_guru";
        $arr['list_guru'] = $guru;
        $arr['list_matkul'] = $matkul;

        return view('pages.master.guru.index', $arr);
    }

    public function save_add(Request $req){
        $validatedData = $req->validate([
            'no_registrasi' => [
                'required',
                'integer',
                Rule::unique('m_guru', 'no_registrasi')                
            ],            
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', // Example: L for male, P for female
        ]);

        $guru = Guru::create([
            "no_registrasi" => $req->no_registrasi,
            "nama_guru" => $req->nama_guru,
            "id_matkul" => $req->id_matkul,
            "jenis_kelamin" => $req->jenis_kelamin,
            "created_at" => date("yyyy-mm-dd"),
        ]);

        $id_guru = $guru->id;
        $password = "guru".$req->no_registrasi;

        $user = User::create([
            "name" => $req->nama_guru,
            "email" => $req->no_registrasi,
            "password" => Hash::make($password),
            "id_role" => 2,
            "id_guru" => $id_guru,
            "created_at" => date("Y-m-d"),
        ]);

        return redirect('/master-data/guru');
    }

    public function save_edit(Request $req, $id){
        $validatedData = $req->validate([
            'no_registrasi' => [
                'required',
                'integer',
                Rule::unique('m_guru', 'no_registrasi')->ignore($id)               
            ],            
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', // Example: L for male, P for female
        ]);

        $guru = Guru::where('id', $id)->update([
            "no_registrasi" => $req->no_registrasi,
            "nama_guru" => $req->nama_guru,
            "id_matkul" => $req->id_matkul,
            "jenis_kelamin" => $req->jenis_kelamin,
            "updated_at" => date("Y-m-d"),
        ]);        

        return redirect('/master-data/guru');
    }
}
