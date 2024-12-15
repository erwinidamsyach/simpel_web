<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Choice;

class Question extends Model
{
    use HasFactory;

    protected $table = "t_question";

    protected $fillable = [
        "materi_id",
        "question",
        "question_type",
    ];
    
    public function choices(){
        return $this->hasMany(Choice::class, "question_id");
    }
}
