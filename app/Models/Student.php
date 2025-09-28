<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'matricule',
        'date_of_birth',
        'gender',
        'photo',
        'situation',
        'class_id',
        'year',
        'school_year_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the class that the student belongs to.
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    /**
     * Get the school year that the student belongs to.
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the avatar URL for the student.
     * Returns the uploaded photo if available, otherwise generates an avatar from ui-avatars.com
     */
    public function getAvatarUrlAttribute(): string
    {
        // If student has a custom uploaded photo, return it
        if ($this->photo) {
            return asset('storage/'.$this->photo);
        }

        // Generate avatar URL from ui-avatars.com with student's initials
        $name = urlencode($this->name);
        $background = $this->getAvatarBackgroundColor();

        return "https://ui-avatars.com/api/?name={$name}&background={$background}&color=fff&size=128&bold=true";
    }

    /**
     * Get a consistent background color for the avatar based on the student's name.
     * This ensures the same student always gets the same color.
     */
    private function getAvatarBackgroundColor(): string
    {
        // Generate a consistent color based on the student's name
        $colors = [
            '6366f1', // indigo-500
            '8b5cf6', // violet-500
            'ec4899', // pink-500
            'ef4444', // red-500
            'f97316', // orange-500
            'eab308', // yellow-500
            '22c55e', // green-500
            '06b6d4', // cyan-500
            '3b82f6', // blue-500
            'a855f7', // purple-500
        ];

        // Use the first character of the name to determine color
        $index = ord(strtoupper(substr($this->name, 0, 1))) % count($colors);

        return $colors[$index];
    }
}
