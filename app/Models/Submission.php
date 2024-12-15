<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = "t_submission";

    protected $fillable = [
        "materi_id",
        "siswa_id",
        "start_at",
        "submitted_at",
        "grade",
    ];
}
