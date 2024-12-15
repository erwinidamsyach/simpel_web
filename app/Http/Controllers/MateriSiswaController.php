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
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Submission;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Answer;

class MateriSiswaController extends Controller
{
    //
    public function index(Request $req){
        $arr = [];
        $user = Auth::user()->load('siswa.kelas');        
        $materi = MainMateri::with([
            'guru',
            'matkul',
            'kelas' => function($query) use($req, $user){
                return $query->where('m_kelas.id', $user->siswa->kelas->id);
            },            
        ])
        ->withCount(['materi'])
        ->get();

        $arr['active'] = 'materi';
        $arr['materi'] = $materi;

        return view('pages.materi.siswa.index', $arr);
    }

    public function detail_materi(Request $req, $id){
        try {
            $arr = [];        
            $user = Auth::user();
            $dec_id = Crypt::decrypt($id);
            
            $main_materi = MainMateri::with(['matkul', 'guru'])                            
                            ->where('id', $dec_id)
                            ->first();
            $materi = Materi::with([
                                'submission' => function($query) use($req, $user){
                                    return $query->where('siswa_id', $user->id_siswa)
                                                ->whereNotNull('submitted_at');
                                }
                            ])
                            ->where('main_materi_id', $main_materi->id)
                            ->get();
                                        
            $arr['active'] = "materi";
            $arr['main_materi'] = $main_materi;
            $arr['materi'] = $materi;
            $arr['id'] = $id;            
            return view('pages.materi.siswa.detail', $arr);

        } catch (DecryptException $e) {
            return redirect('/siswa/materi');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/siswa/materi');
        }  
    }

    public function do_test(Request $req, $submateri_id){
        DB::beginTransaction();
        try{
            $arr = [];
            $user = Auth::user();
            $dec_id = Crypt::decrypt($submateri_id);

            // Create submission
            $submission = Submission::where("materi_id", $dec_id,)
                            ->where("siswa_id", $user->id_siswa)
                            ->first();
            if($submission == null){
                $submission = Submission::create([
                    "materi_id" => $dec_id,
                    "siswa_id" => $user->id_siswa,
                    "start_at" => date("Y-m-d H:i:s"),
                ]);
            }            
            $sub_id = $submission->id;

            // Materi Info
            $materi = Materi::with([
                'submission' => function($query) use($req, $user){
                    return $query->where('siswa_id', $user->id_siswa);
                }
            ])
            ->where('id', $dec_id)
            ->first();
            
            // Get Question List            
            $question_list = Question::with(['choices'])
                    ->where('materi_id', $dec_id)
                    ->get();
            DB::commit();

            $arr['active'] = 'materi';            
            $arr['submission'] = $submission;
            $arr['materi'] = $materi;
            $arr['question_list'] = $question_list;

            return view('pages.materi.siswa.test', $arr);

        }catch(DecryptException $e){
            DB::rollback();
            return redirect('siswa/materi');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return redirect('/siswa/materi');
        }
    }

    public function submit_test(Request $req, $submateri_id){
        DB::beginTransaction();
        try{
            foreach($req->question as $key => $item){
                if($item['type'] == "essay"){
                    $val = [
                        'submission_id' => $req->submission_id,
                        'question_id' => $key,
                        'answer_text' => $item['answer_text']
                    ];
                }else{
                    $val = [
                        'submission_id' => $req->submission_id,
                        'question_id' => $key,
                        "choice_id" => $item['choice_id']
                    ];
                }

                Answer::create($val);
            }

            Submission::where('id', $req->submission_id)
                    ->update([
                        "submitted_at" => date("Y-m-d H:i:s")
                    ]);
            
            $submission = Submission::where('id', $req->submission_id)->first();
            $materi = Materi::with(['main_materi'])->where('id', $submission->materi_id)->first();

            DB::commit();

            return redirect('siswa/materi/'.Crypt::encrypt($materi->main_materi->id));
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->withInput()->with("error", "Tejadi kesalahan saat mengirimkan jawaban, silahkan coba kembali!");
        }
    }

    public function view_jobsheet(Request $req, $submateri_id){
        try{
            $arr = [];
            $user = Auth::user();
            $dec_id = Crypt::decrypt($submateri_id);

            $materi = Materi::with(['main_materi.matkul'])
                    ->where('id', $dec_id)
                    ->first();            

            $arr['active'] = 'materi';                        
            $arr['materi'] = $materi;

            return view('pages.materi.siswa.jobsheet', $arr);

        }catch(DecryptException $e){            
            return redirect('siswa/materi');
        }
    }
}