<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\MainMateri;
use App\Models\Materi;
use App\Models\Matkul;
use App\Models\Kelas;
use App\Models\KelasMatkul;
use App\Models\Question;
use App\Models\Choice;

class MateriController extends Controller
{
    //
    public function index(){
        $arr = [];

        $user = Auth::user();
        $list_materi = MainMateri::with(['matkul'])
                            ->withCount(['kelas', 'materi'])
                            ->where('owner_id', $user->id_guru)
                            ->get();
        
        $arr['active'] = "materi";
        $arr['list_materi'] = $list_materi;

        return view('pages.materi.guru.index', $arr);
    }

    public function buat_materi(){
        $arr = [];

        $guru = Auth::user()->guru;
        $matkul = $guru->matkul;
        $kelas = Kelas::all();

        $arr['active'] = "materi";
        $arr['guru'] = $guru;
        $arr['matkul'] = $matkul;
        $arr['kelas'] = $kelas;
        
        return view('pages.materi.guru.create', $arr);
    }

    public function simpan_materi(Request $req){
        DB::beginTransaction();

        try{
            // Ambil data user yang login
            $user = Auth::user();

            // Buat record di tabel t_main_materi
            $main_materi = MainMateri::create([
                "matkul_id" => $req->matkul_id,
                "deskripsi" => $req->deskripsi,
                "owner_id" => $user->id_guru,
                "created_at" => date("Y-m-d"),
            ]);

            // Buat record di tabel t_kelas_matkul
            $id_main_materi = $main_materi->id;

            foreach($req->kelas as $kelas){
                KelasMatkul::create([
                    "main_materi_id" => $id_main_materi,
                    "kelas_id" => $kelas,
                    "created_at" => date('Y-m-d'),
                ]);
            }

            DB::commit();
            $enc_id = Crypt::encrypt($id_main_materi);
            return redirect('/guru/materi/'.$enc_id);

        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
    
            return redirect()->back()->withInput()->withErrors(['error' => "Gagal membuat materi!"]);
        }
    }

    public function detail_materi(Request $req, $id){
        try {
            $arr = [];        
            $user = Auth::user();
            $dec_id = Crypt::decrypt($id);
            
            $materi = MainMateri::with(['matkul', 'kelas', 'materi', 'guru'])                            
                            ->where('id', $dec_id)
                            ->where('owner_id', $user->id_guru)
                            ->first();

            $arr['active'] = "materi";
            $arr['materi'] = $materi;            
            $arr['id'] = $id;            
            return view('pages.materi.guru.detail', $arr);

        } catch (DecryptException $e) {
            return redirect('/guru/materi');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/guru/materi');
        }  
    }

    public function create_submateri(Request $req, $id){
        try {
            $arr = [];        
            $user = Auth::user();
            $dec_id = Crypt::decrypt($id);
            
            $materi = MainMateri::with(['matkul', 'kelas', 'materi', 'guru'])                            
                            ->where('id', $dec_id)
                            ->where('owner_id', $user->id_guru)
                            ->first();

            $arr['active'] = "materi";
            $arr['materi'] = $materi;
            $arr['id'] = $id;
            return view('pages.materi.guru.create_sub', $arr);

        } catch (DecryptException $e) {
            return redirect('/guru/materi');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/guru/materi');
        }
    }

    public function save_create_submateri(Request $req, $id){        
        DB::beginTransaction();
        try{
            $arr = [];        
            $user = Auth::user();
            $dec_id = Crypt::decrypt($id);

            $req->validate([
                'file' => 'required|file|mimes:pdf|max:5120', // Add valid mime types and size constraints
            ]);

            $file = $req->file('file');

            // Generate a unique name for the file
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Save the file to a disk (e.g., 'public' disk)
            $filePath = $file->storeAs('jobsheet', $fileName, 'public');


            // Save to sub materi
            $materi = Materi::create([
                "main_materi_id" => $dec_id,
                "judul_materi" => $req->judul_materi,
                "deskripsi" => $req->deskripsi,
                "link_jobsheet" => $filePath,
                "tanggal_mulai" => $req->tanggal_mulai,
                "tanggal_selesai" => $req->tanggal_selesai,
                "link_video" => $req->link_video,
                "status" => ($req->status)?"Aktif":"Tidak Aktif",
            ]);
            $materi_id = $materi->id;

            // Save Question and Choice
            foreach($req->questions as $key => $get){
                $question = Question::create([
                    "materi_id" => $materi_id,
                    "question" => $get['text'],
                    "question_type" => $get['type'],
                ]);

                if($get['type'] == "multiple_choice"){
                    $question_id = $question->id;
                    foreach($get['choices'] as $ind => $val){
                        $is_correct = (array_key_exists('is_correct', $val))?1:0;

                        $choice = Choice::create([
                            "question_id" => $question_id,
                            "choice" => $val['text'],
                            "is_correct" => $is_correct
                        ]);
                    }                    
                }
            }

            DB::commit();

            return redirect('/guru/materi/'.$id)->with(["success" => "Sub materi berhasil disimpan"]);
        }catch(DecryptException $e){
            DB::rollback();
            return redirect('/guru/materi');
        }catch(\Exception $e){
            DB::rollback();
            return redirect('/guru/materi/'.$id."/buat-sub-materi")->with(["errors" => "Gagal menyimpan", "message" => $e->getMessage()]);
        }
    }

    public function set_materi_status(Request $req, $materi_id, $state){
        if($state == 0){
            $status = "Tidak Aktif";
        }else{
            $status = "Aktif";
        }

        Materi::where('id', $materi_id)
                ->update([
                    'status' => $status
                ]);


        return redirect()->back();
    }

    public function edit_submateri(Request $req, $submateri_id){
        try{
            $arr = [];
            $dec_id = Crypt::decrypt($submateri_id);
            
            $materi = Materi::with(["questions.choices"])
                            ->where('id', $dec_id)
                            ->first();

            $arr['materi'] = $materi;
            $arr['active'] = "materi";

            return view('pages.materi.guru.edit_sub', $arr);
        }catch(DecryptException $e){
            return redirect()->back();
        }
    }

    public function save_edit_submateri(Request $req, $id){
        DB::beginTransaction();
        try{
            $arr = [];        
            $user = Auth::user();
            $dec_id = Crypt::decrypt($id);

            $update_list = [                    
                "judul_materi" => $req->judul_materi,
                "deskripsi" => $req->deskripsi,
                "link_jobsheet" => "",
                "tanggal_mulai" => $req->tanggal_mulai,
                "tanggal_selesai" => $req->tanggal_selesai,
                "link_video" => $req->link_video,
                "status" => ($req->status)?"Aktif":"Tidak Aktif",
            ];

            if($req->has('file')){
                $file = $req->file('file');

                // Generate a unique name for the file
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Save the file to a disk (e.g., 'public' disk)
                $filePath = $file->storeAs('jobsheet', $fileName, 'public');
                $update_list['link_jobsheet'] = $filePath;
            }

            // Save to sub materi
            $materi = Materi::where('id', $dec_id)
                ->update($update_list);            
            $materi_id = $dec_id;
            // Save Question and Choice
            foreach($req->questions as $key => $get){
                if(array_key_exists('id', $get)){
                    $question = Question::where('id', $get['id'])
                    ->update([
                        "materi_id" => $materi_id,
                        "question" => $get['text'],
                        "question_type" => $get['type'],
                    ]);
                    $question_id = $get['id'];
                }else{
                    $question = Question::create([
                        "materi_id" => $materi_id,
                        "question" => $get['text'],
                        "question_type" => $get['type'],
                    ]);
                    $question_id = $question->id;
                }      
                
                if($get['type'] == "multiple_choice"){                    
                    foreach($get['choices'] as $ind => $val){
                        $is_correct = (array_key_exists('is_correct', $val))?1:0;
                        if(array_key_exists('id', $val)){
                            $choice = Choice::where('id', $val['id'])
                            ->update([
                                "question_id" => $question_id,
                                "choice" => $val['text'],
                                "is_correct" => $is_correct
                            ]);
                        }else{
                            $choice = Choice::create([
                                "question_id" => $question_id,
                                "choice" => $val['text'],
                                "is_correct" => $is_correct
                            ]);
                        }                        
                    }                    
                }
            }            

            DB::commit();
            $main_id = Materi::where('id', $dec_id)->first();
            return redirect('/guru/materi/'.Crypt::encrypt($main_id->main_materi_id))->with(["success" => "Sub materi berhasil disimpan"]);
        }catch(DecryptException $e){
            DB::rollback();
            return redirect('/guru/materi');
        }catch(\Exception $e){
            DB::rollback();
            return redirect('/guru/materi/submateri/edit/'.$id)->with(["errors" => "Gagal menyimpan", "message" => $e->getMessage()]);
        }
    }
}
