<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Card extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'id',
        'set_id',
        'japanese_word',
        'japanese_reading',
        'meaning',
        'example_sentence',
        'pitch_accent',
        'is_mastered'
    ];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
}
