<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingCourse extends Model
{
    protected $fillable = ['course_id', 'name', 'description', 'start_date', 'end_date', 'seats'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')->withTimestamps();
    }

    public function availableSeats(): int
    {
        return max(0, $this->seats - ($this->students_count ?? $this->students()->count()));
    }
}
