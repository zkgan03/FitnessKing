<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'password',
        'email',
        'gender',
        'phone_number',
        'birth_date',
        'creation_date',
        'profile_pic',
    ];

    //change the relationship for attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'customer_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, "customer_id");
    }

    public function gymClasses()
    {
        return $this->belongsToMany(GymClass::class, 'enrollments', 'customer_id', 'class_id')
            ->withPivot('is_present')
            ->withTimestamps();
    }
}
