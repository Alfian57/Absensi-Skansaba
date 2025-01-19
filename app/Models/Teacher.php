<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $guard = 'teacher';

    public function homeroomTeacher()
    {
        return $this->belongsTo(HomeroomTeacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getRouteKeyName()
    {
        return 'nip';
    }
}
