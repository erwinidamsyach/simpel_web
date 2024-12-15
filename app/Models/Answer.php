<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = "t_answer";

    protected $fillable = [
        "submission_id",
        "question_id",
        "answer_text",
        "choice_id",
    ];
}
