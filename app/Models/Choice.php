<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $table = "t_choice";

    protected $fillable = [
        "question_id",
        "choice",
        "is_correct",
    ];
}
