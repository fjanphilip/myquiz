<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Card extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'study_set_id',
        'japanese_word',
        'japanese_reading',
        'meaning',
        'example_sentence',
        'pitch_accent',
        'is_mastered'
    ];

    public function studySet()
    {
        return $this->belongsTo(StudySet::class);
    }
}
