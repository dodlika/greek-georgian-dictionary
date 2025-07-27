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
        'english_translation',       // ADD THIS
        'word_type',
        'present_tense',             // ADD THIS
        'past_tense',                // ADD THIS
        'future_tense'               // ADD THIS
    ];

    protected $casts = [
        'present_tense' => 'array',  // CAST JSON fields to arrays
        'past_tense' => 'array',
        'future_tense' => 'array',
    ];

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function scopeSearchGeorgian($query, $search)
    {
        return $query->where('georgian_translation', 'like', '%' . $search . '%');
    }
}
