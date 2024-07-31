<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDailyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'points',
        'day_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
