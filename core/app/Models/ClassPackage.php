<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassPackage extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'class_packages';
    protected $primaryKey = 'package_id';
    public $timestamps = false;


    protected $fillable = [
        'package_id',
        'package_name',
        'description',
        'start_date',
        'end_date',
        'price',
        'max_capacity',
        'package_image'
    ];


    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'package_id');
    }

    public function gymClasses(): HasMany
    {
        return $this->hasMany(GymClass::class, 'package_id');
    }
}
