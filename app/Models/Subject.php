<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function skippingClasses()
    {
        return $this->hasMany(SkippingClass::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
