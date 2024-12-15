<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;

class MasterKelasSiswaController extends Controller
{
    //
    public function index(){
        $arr = [];

        $kelas = Kelas::withCount('siswa')->get();        

        $arr['active'] = 'master_kelas';
        $arr['data_kelas'] = $kelas;
        return view('pages.master.kelas.index', $arr);
    }

    public function view_add(){
        $arr = [];
        $arr['active'] = 'master_kelas';
        return view('pages.master.kelas.add', $arr);
    }

    public function save_add(Request $req){
        $save = Kelas::create([
            "nama_kelas" => $req->nama_kelas,
            "tingkatan" => $req->tingkatan,
        ]);
        $enc_id = Crypt::encrypt($save->id);
        return redirect('master-data/kelas/detail/'.$enc_id);
    }

    public function view_detail(Request $req, $id){        
        try {
            $arr = [];        
            
            $dec_id = Crypt::decrypt($id);
            $kelas = Kelas::where("id", $dec_id)
                            ->with([
                                "siswa" => function($query) use($req){
                                    $query->orderBy('no_absen', 'ASC');
                                },
                            ])
                            ->first();            

            $arr['active'] = "master_kelas";
            $arr['enc_id'] = $id;
            $arr['kelas'] = $kelas;

            return view('pages.master.kelas.detail', $arr);
        } catch (DecryptException $e) {
            return redirect('master-data/kelas');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('master-data/kelas');
        }        
    }

    public function save_edit(Request $req, $id){
        try {
            $dec_id = Crypt::decrypt($id);
            $save = Kelas::where('id', $dec_id)
                    ->update([
                        "nama_kelas" => $req->nama_kelas,
                        "tingkatan" => $req->tingkatan,
                    ]);            
            return redirect('master-data/kelas/detail/'.$id);
        } catch (DecryptException $e) {
            return redirect('master-data/kelas');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('master-data/kelas');
        }        
    }

    public function add_siswa(Request $req, $id){
        $dec_id = Crypt::decrypt($id);        
        $validatedData = $req->validate([
            'no_absen' => [
                'required',
                'integer',
                Rule::unique('m_siswa', 'no_absen')                
            ],
            'no_induk' => [
                'required',
                Rule::unique('m_siswa', 'no_induk') // Check for uniqueness in the m_siswa table
            ],            
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', // Example: L for male, P for female
        ]);

        $siswa = Siswa::create([
            "id_kelas" => $dec_id,            
            "no_absen" => $req->no_absen,
            "no_induk" => $req->no_induk,
            "nama_siswa" => $req->nama_siswa,
            "jenis_kelamin" => $req->jenis_kelamin,
        ]);

        $id_siswa = $siswa->id;
        $password = $req->no_absen."_".$req->no_induk;

        $user = User::create([
            "name" => $req->nama_siswa,
            "email" => $req->no_induk,
            "password" => Hash::make($password),
            "id_role" => 3,
            "id_siswa" => $id_siswa,
            "created_at" => date("yyyy-mm-dd"),
        ]);

        return redirect('master-data/kelas/detail/'.$id);
    }

    public function edit_siswa(Request $req, $id_kelas, $id_siswa){
        $dec_id = Crypt::decrypt($id_kelas);        
        $validatedData = $req->validate([
            'no_absen' => [
                'required',
                'integer',
                Rule::unique('m_siswa', 'no_absen')->ignore($id_siswa)
            ],
            'no_induk' => [
                'required',
                Rule::unique('m_siswa', 'no_induk')->ignore($id_siswa) // Check for uniqueness in the m_siswa table
            ],            
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', // Example: L for male, P for female
        ]);

        $siswa = Siswa::where('id', $id_siswa)->update([
            "id_kelas" => $dec_id,            
            "no_absen" => $req->no_absen,
            "no_induk" => $req->no_induk,
            "nama_siswa" => $req->nama_siswa,
            "jenis_kelamin" => $req->jenis_kelamin,
        ]);

        return redirect('master-data/kelas/detail/'.$id_kelas);
    }

    public function ajax_cek_absen(Request $req){
        $dec_id = Crypt::decrypt($req->id_kelas);
        $siswa = Siswa::where('id_kelas', $dec_id)
                    ->where('no_absen', $req->no_absen)
                    ->first();
        return response()->json([
            "error" => false,
            "data" => [
                "exist" => (null !== $siswa)?true:false
            ],
        ], 200);
    }
}
