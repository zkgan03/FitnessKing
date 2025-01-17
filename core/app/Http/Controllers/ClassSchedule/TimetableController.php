<?php

namespace App\Http\Controllers\ClassSchedule;

use App\Models\Timetable;
use App\Models\GymClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; 
use Exception;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::with('gymClass')->get();
        $assignedClassIds = $timetables->pluck('class_id')->toArray();
        $unassignedClasses = GymClass::whereNotIn('class_id', $assignedClassIds)->get();

        return view('timetable.index', compact('timetables', 'unassignedClasses'));
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:gym_classes,class_id',
            'day' => 'required|integer|min:0|max:6',
            'timeslot' => 'required|string'
        ]);

        try {
            \DB::beginTransaction();

            Timetable::where('day', $validated['day'])
                ->where('timeslot', $validated['timeslot'])
                ->delete();

            $timetable = Timetable::create([
                'class_id' => $validated['class_id'],
                'day' => $validated['day'],
                'timeslot' => $validated['timeslot'],
                'is_assigned' => true
            ]);

            // Update class schedule notification
            $notificationMessage = "Updated!! Class at " . $validated['timeslot'] . " on day " . $validated['day'];
            $timetable->updateClassSchedule($notificationMessage);

            $this->sendNotification($notificationMessage);

            \DB::commit();

            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            \DB::rollback();
            Log::error('Error assigning timetable: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:gym_classes,class_id'
        ]);

        try {
            \DB::beginTransaction();

            $timetable = Timetable::where('class_id', $validated['class_id'])->first();

            if (!$timetable) {
                return response()->json(['status' => 'error', 'message' => 'Timetable entry not found.'], 404);
            }

            $timetable->delete();

            $notificationMessage = "Updated!! Class at " . $timetable->timeslot . " on day " . $timetable->day . " has been removed.";
            $this->sendNotification( $notificationMessage);

            \DB::commit();

            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            \DB::rollback();
            Log::error('Error removing timetable: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Send notification to the external notification service.
     *
     * @param string $message
     * @return void
     */
    private function sendNotification($message, $type = 'update')
    {
        try {
            $notificationUrl = env('NOTIFICATION_SERVICE_URL', 'http://localhost:3000/notify');

            $response = Http::post($notificationUrl, [
                'message' => $message,
                'type' => $type
            ]);

            if ($response->failed()) {
                Log::error("Failed to notify: " . $response->body());
            }
        } catch (Exception $e) {
            Log::error("Exception when notifying: " . $e->getMessage());
        }
    }

}
