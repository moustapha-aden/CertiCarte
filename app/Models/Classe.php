<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    /** @use HasFactory<\Database\Factories\ClasseFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'year_id',
    ];

    /**
     * Get the students for the class.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'classe_id');
    }

    /**
     * Get the school year that the class belongs to.
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'year_id');
    }
}
