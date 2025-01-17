<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coach extends Authenticatable implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coaches';
    protected $primaryKey = 'coach_id';
    public $timestamps = false;


    protected $fillable = [
        'coach_id',
        'first_name',
        'last_name',
        'password',
        'email',
        'gender',
        'phone_number',
        'birth_date',
        'creation_date',
        'profile_pic',
        'description',
        'coach_status',
    ];

    public function gymClasses(): HasMany
    {
        return $this->hasMany(GymClass::class, 'coach_id');
    }
}
