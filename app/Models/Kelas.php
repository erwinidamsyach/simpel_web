<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Siswa;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'm_kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkatan',        
    ];

    public function siswa(){
        return $this->hasMany(Siswa::class, "id_kelas");
    }
}
