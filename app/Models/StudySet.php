<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StudySet extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'title',
        'description'
    ];

    public $incrementing = false;
    protected $keyType = 'string';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
