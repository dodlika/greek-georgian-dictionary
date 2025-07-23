<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Word;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'can_manage_words',
        'best_quiz_score',
        'best_quiz_total',
        'best_quiz_date',
        'total_quizzes_taken',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'best_quiz_date' => 'datetime',
            'can_manage_words' => 'boolean',
        ];
    }

    /**
     * The user's favorite words relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
 public function favoriteWords()
{
    return $this->belongsToMany(Word::class, 'favorites')->withTimestamps();
}


    /**
     * Check if user has taken any quizzes
     */
    public function hasQuizHistory(): bool
    {
        return $this->total_quizzes_taken > 0;
    }

    /**
     * Get user's best quiz percentage
     */
    public function getBestQuizPercentage(): float
    {
        if ($this->best_quiz_total == 0) {
            return 0;
        }
        return round(($this->best_quiz_score / $this->best_quiz_total) * 100, 2);
    }

    /**
     * Check if user has a best score
     */
    public function hasBestScore(): bool
    {
        return $this->best_quiz_total > 0;
    }

    /**
     * Get formatted best score string
     */
    public function getBestScoreFormatted(): string
    {
        if (!$this->hasBestScore()) {
            return 'No quizzes taken yet';
        }
        
        return "{$this->best_quiz_score}/{$this->best_quiz_total} ({$this->getBestQuizPercentage()}%)";
    }

    /**
     * Get quiz statistics
     */
    public function getQuizStats(): array
    {
        return [
            'total_taken' => $this->total_quizzes_taken,
            'best_score' => $this->best_quiz_score,
            'best_total' => $this->best_quiz_total,
            'best_percentage' => $this->getBestQuizPercentage(),
            'best_date' => $this->best_quiz_date,
            'has_history' => $this->hasQuizHistory(),
        ];
    }
}