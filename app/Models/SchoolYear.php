<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolYearFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'year',
    ];

    /**
     * Get all classes belonging to this school year.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Classe>
     */
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
