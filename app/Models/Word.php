<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'greek_word',
        'greek_present',
        'greek_past',
        'greek_future',
        'georgian_translation',
        'word_type'
    ];

    public function scopeSearchGeorgian($query, $search)
    {
        return $query->where('georgian_translation', 'like', '%' . $search . '%');
    }
}