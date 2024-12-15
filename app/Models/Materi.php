<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\MainMateri;
use App\Models\Question;
use App\Models\Submission;

class Materi extends Model
{
    use HasFactory;

    protected $table = "t_materi";

    protected $fillable = [
        "main_materi_id",
        "judul_materi",
        "deskripsi",
        "link_jobsheet",
        "tanggal_mulai",
        "tanggal_selesai",
        "link_video",
        "status",
    ];

    public function main_materi(){
        return $this->belongsTo(MainMateri::class, "main_materi_id");
    }

    public function questions(){
        return $this->hasMany(Question::class, "materi_id");
    }

    public function submission(){
        return $this->hasOne(Submission::class, 'materi_id');
    }
}
