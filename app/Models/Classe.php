<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class representing a school class.
 *
 * @property int $id
 * @property string $label
 * @property int $year_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\SchoolYear $schoolYear
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 */
class Classe extends Model
{
    /** @use HasFactory<\Database\Factories\ClasseFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'year_id',
    ];

    /**
     * Get the students belonging to this class.
     *
     * @return HasMany<\App\Models\Student>
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'classe_id');
    }

    /**
     * Get the school year that this class belongs to.
     *
     * @return BelongsTo<\App\Models\SchoolYear, \App\Models\Classe>
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'year_id');
    }
}
