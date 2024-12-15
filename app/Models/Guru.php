<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Matkul;

class Guru extends Model
{
    use HasFactory;

    protected $table = "m_guru";

    protected $fillable = [
        "no_registrasi",
        "nama_guru",
        "id_matkul",
    ];

    public function matkul(){
        return $this->hasMany(Matkul::class, 'id', 'id_matkul');
    }
}
