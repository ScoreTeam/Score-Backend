<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'image_url', 'type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
