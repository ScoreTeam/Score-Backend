<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'points_number', 'duration_minutes',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
