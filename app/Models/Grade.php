<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function homeroomTeacher()
    {
        return $this->belongsTo(HomeroomTeacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
