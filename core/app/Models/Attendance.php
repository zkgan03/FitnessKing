<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id'; 
    protected $fillable = ['class_id', 'customer_id', 'is_present'];

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
