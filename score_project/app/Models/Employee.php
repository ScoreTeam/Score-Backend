<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'phone_number',
        'address',
        'shift_starts',
        'shift_ends',
        'info_description',
        'salary',
        'position',
        'total_score',
        'birth_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function mainImages()
    // {
    //     return $this->hasMany(MainImage::class);
    //     //has only one but i can work with that
    // }

    // public function secondaryImages()
    // {
    //     return $this->hasMany(SecondaryImage::class);
    // }

    public function employeeDailyPoints()
    {
        return $this->hasMany(EmployeeDailyPoint::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_services');
    }
}
