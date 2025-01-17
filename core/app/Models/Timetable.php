<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Observers\Subject;
use App\Observers\Observer;

class Timetable extends Model implements Subject
{
    use HasFactory;

    protected $table = 'timetables';
    protected $primaryKey = 'timetable_id';
    public $timestamps = false;

    protected $fillable = [
        'class_id',
        'day',
        'timeslot',
        'is_assigned',
    ];

    private $observers = [];

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer)
    {
        $this->observers = array_filter($this->observers, function ($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notify(string $message)
    {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }

    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    public function updateClassSchedule(string $classDetails)
    {
        // notify observers
        $this->notify("Class schedule updated: {$classDetails}");
    }
}
