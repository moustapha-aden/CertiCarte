<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentImportError extends Model
{
    protected $fillable = [
        'student_import_id',
        'row_number',
        'error_type',
        'error_message',
        'row_data',
    ];

    protected $casts = [
        'row_data' => 'array',
    ];

    /**
     * Get the import that this error belongs to.
     */
    public function studentImport(): BelongsTo
    {
        return $this->belongsTo(StudentImport::class);
    }

    /**
     * Get formatted row data for display.
     */
    public function getFormattedRowDataAttribute(): ?string
    {
        if (! $this->row_data) {
            return null;
        }

        return json_encode($this->row_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get error type badge color.
     */
    public function getBadgeColorAttribute(): string
    {
        return match ($this->error_type) {
            'validation' => 'yellow',
            'missing_field' => 'red',
            'school_year' => 'blue',
            'class' => 'indigo',
            'database' => 'red',
            default => 'gray',
        };
    }
}
