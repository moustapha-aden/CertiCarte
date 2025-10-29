<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentImport extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'total_rows',
        'success_count',
        'failed_count',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that performed the import.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the errors for this import.
     */
    public function errors(): HasMany
    {
        return $this->hasMany(StudentImportError::class);
    }

    /**
     * Get the formatted duration of the import.
     */
    public function getDurationAttribute(): ?string
    {
        if (! $this->started_at || ! $this->completed_at) {
            return null;
        }

        $duration = $this->started_at->diffInSeconds($this->completed_at);

        if ($duration < 60) {
            return "{$duration} secondes";
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return "{$minutes} minute(s) et {$seconds} seconde(s)";
    }

    /**
     * Check if the import is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the import failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the import is still processing.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }
}
