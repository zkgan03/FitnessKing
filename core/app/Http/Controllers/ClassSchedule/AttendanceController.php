<?php

namespace App\Http\Controllers\ClassSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GymClass;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'class_type' => 'nullable|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'classroom' => 'nullable|string|regex:/^[A-Z][-]?\d{3,4}$/|max:255',
            'time' => 'nullable|date_format:H:i'
        ]);

        $classType = $validatedData['class_type'] ?? null;
        $classroom = $validatedData['classroom'] ?? null;
        $time = $validatedData['time'] ?? null;

        $gymClasses = GymClass::query();

        if ($classType) {
            $gymClasses->where('class_type', $classType);
        }

        if ($classroom) {
            $gymClasses->where('classroom', $classroom);
        }

        if ($time) {
            $startTime = date('H:i', strtotime($time));
            $endTime = date('H:i', strtotime('+59 minutes', strtotime($startTime)));

            $gymClasses->where('start_time', '>=', $startTime)
                ->where('start_time', '<=', $endTime);
        }

        // Retrieve gym classes along with attendance and enrolled customers
        $gymClasses = $gymClasses->with(['attendances', 'customers'])->get();

        $inputFormHtml = $this->transformInputXmlToHtml();

        return view('attendance.index', [
            'inputFormHtml' => $inputFormHtml,
            'gymClasses' => $gymClasses,
            'classType' => $classType,
            'classroom' => $classroom,
            'time' => $time
        ]);
    }

    private function transformInputXmlToHtml()
    {
        $xmlPath = storage_path('app/classType/classes.xml');
        $xslPath = storage_path('app/classType/classes.xsl');

        if (!file_exists($xmlPath) || !file_exists($xslPath)) {
            return "Error: XML or XSL file not found. Please check the paths.";
        }

        $xml = new \DOMDocument;
        if (!$xml->load($xmlPath)) {
            return "Error: Could not load XML file.";
        }

        $xsl = new \DOMDocument;
        if (!$xsl->load($xslPath)) {
            return "Error: Could not load XSL file.";
        }

        $proc = new \XSLTProcessor;
        $proc->importStyleSheet($xsl);

        return $proc->transformToXML($xml);
    }

    public function markAttendance(Request $request)
    {
        $classId = $request->input('class_id');
        $attendances = $request->input('attendances', []);

        $request->validate([
            'class_id' => 'required|exists:gym_classes,class_id',
            'attendances' => 'array',
            'attendances.*' => 'boolean',
        ]);

        foreach ($attendances as $customerId => $isPresent) {
            Attendance::updateOrCreate(
                ['class_id' => $classId, 'customer_id' => $customerId],
                ['is_present' => $isPresent] 
            );
        }

        return response()->json(['status' => 'success']);
    }
}
