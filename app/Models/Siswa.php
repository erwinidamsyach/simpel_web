<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Kelas;

class Siswa extends Model
{
    use HasFactory;

    protected $table = "m_siswa";

    protected $fillable = [
        "no_absen",
        "no_induk",
        "nama_siswa",
        "jenis_kelamin",
        "id_kelas",
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class, "id_kelas");
    }
}
