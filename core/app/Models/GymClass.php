<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class GymClass extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'gym_classes';
    protected $primaryKey = 'class_id';
    public $timestamps = false;


    protected $fillable = [
        'class_id',
        'class_name',
        'class_image',
        'description',
        'class_type',
        'classroom',
        'class_date',
        'start_time',
        'end_time',
        'coach_id',
        'package_id',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function classPackage(): BelongsTo
    {
        return $this->belongsTo(ClassPackage::class, 'package_id');
    }

    public function timetable(): HasOne
    {
        return $this->hasOne(Timetable::class, 'class_id');
    }

    //change the relationship for classSchedule (customer & enrollments)
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Attendance::class, 'class_id', 'customer_id', 'class_id', 'customer_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'package_id', 'package_id');
    }

}
