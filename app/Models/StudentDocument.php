<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    protected $fillable = ['original_name', 'path'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
