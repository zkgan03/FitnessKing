<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'enrollments';
    protected $primaryKey = 'enrollment_id';
    public $timestamps = false;


    protected $fillable = [
        'customer_id',
        'package_id',
        'payment_id',
    ];

    public function classPackage(): BelongsTo
    {
        return $this->belongsTo(ClassPackage::class, "package_id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id");
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, "payment_id");
    }

    //change the relationship for classSchedule
    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'package_id', 'package_id');
    }
}

