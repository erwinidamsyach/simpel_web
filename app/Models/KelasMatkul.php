<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMatkul extends Model
{
    use HasFactory;

    protected $table = "t_kelas_matkul";

    protected $fillable = [
        "main_materi_id",
        "kelas_id",
    ];
}
