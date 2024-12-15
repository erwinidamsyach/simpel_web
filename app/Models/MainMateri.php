<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Matkul;
use App\Models\Kelas;
use App\Models\Guru;

class MainMateri extends Model
{
    use HasFactory;

    protected $table = "t_main_materi";

    protected $fillable = [
        "matkul_id",
        "deskripsi",
        "owner_id",
        "status",
    ];

    public function matkul(){
        return $this->hasOne(Matkul::class, "id", "matkul_id");
    }

    public function kelas(){
        return $this->hasManyThrough(
            Kelas::class,
            KelasMatkul::class,
            'main_materi_id', // Foreign key on KelasMatkul table
            'id',             // Foreign key on Kelas table
            'id',             // Local key on MainMateri table
            'kelas_id'        // Local key on KelasMatkul table
        );
    }

    public function materi(){
        return $this->hasMany(Materi::class, "main_materi_id");
    }

    public function guru(){
        return $this->belongsTo(Guru::class, "owner_id", "id");
    }
}
