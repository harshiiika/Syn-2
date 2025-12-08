<?php

namespace App\Http\Controllers\Master;

use App\Models\Master\Holiday;
use App\Models\TestSeries\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;



/**
 * CalendarController - Manages academic calendar events
 * Handles holidays, test scheduling, and calendar view operations with MongoDB integration
 */
class CalendarController extends Controller
{
  /**
     * Display the calendar page with holidays and tests
     * Fetches session-specific events and formats them for calendar display
     * @return \Illuminate\View\View
     */
   public function index(): mixed
{
    try {
        $currentSession = session('current_session');

        // Get all holidays
        $holidays = Holiday::all()->map(function ($holiday) {
            return [
                'id' => (string) $holiday->_id,
                'date' => $holiday->date,
                'description' => $holiday->description,
            ];
        });

        // Get all tests for the current session - ONLY TESTS WITH DATES
        $tests = Test::when($currentSession, function($query) use ($currentSession): mixed {
            return $query->where('session_id', $currentSession);
        })
        ->whereNotNull('date')  // ✓ FIX: Filter out tests without dates
        ->orderBy('date', 'asc')
        ->get()
        ->map(function($test) {
            return [
                'id' => (string) $test->_id,
                'date' => $test->date->format('Y-m-d'),
                'description' => $test->description,
                'test_name' => $test->test_name ?? $test->description,
                'formatted_date' => $test->date->format('d M Y')
            ];
        });

        return view('master.calendar.calendar', compact('holidays', 'tests'));
        
    } catch (\Exception $e) {
        Log::error('Calendar Index Error: ' . $e->getMessage());
        return view('master.calendar.calendar')
            ->with('error', 'Unable to load calendar. Please try again.');
    }
}

    
    /**
     * Store a new holiday in the database
     * Validates input, checks for duplicates, and creates holiday record
     * @param Request $request - Contains holiday date, description, and session
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeHoliday(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'session_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = Carbon::parse($request->date);
            $sessionId = $request->session_id ?? session('current_session_id');
            
            // Check if holiday already exists for this date and session
            $exists = Holiday::where('date', $date)
                           ->when($sessionId, function($query) use ($sessionId) {
                               return $query->where('session_id', $sessionId);
                           })
                           ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'A holiday already exists for this date'
                ], 422);
            }

            // Create holiday
            $holiday = Holiday::create([
                'date' => $date,
                'description' => $request->description,
                'type' => 'holiday',
                'session_id' => $sessionId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Holiday added successfully',
                'data' => [
                    'id' => (string) $holiday->_id,
                    'date' => $holiday->date->format('Y-m-d'),
                    'description' => $holiday->description,
                    'type' => $holiday->type,
                    'formatted_date' => $holiday->date->format('d M Y')
                ]
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Store Holiday Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a holiday
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteHoliday($id)
    {
        try {
            // Find holiday by ID (handles both string and ObjectId)
            $holiday = Holiday::where('_id', $id)->first();
            
            if (!$holiday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Holiday not found'
                ], 404);
            }

            // Delete holiday
            $holiday->delete();

            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Delete Holiday Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     /**
     * Store a new test in the database
     * Validates test data and creates test record with optional time/marks fields
     * @param Request $request - Contains test details (date, name, time, marks)
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTest(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'test_name' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'session_id' => 'nullable|string',
            'batch_id' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'passing_marks' => 'nullable|integer|min:1',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = Carbon::parse($request->date);
            $sessionId = $request->session_id ?? session('current_session_id');
            
            // Prepare test data
            $testData = [
                'date' => $date,
                'description' => $request->description,
                'test_name' => $request->test_name ?? $request->description,
                'session_id' => $sessionId,
                'batch_id' => $request->batch_id,
                'status' => $request->status ?? 'scheduled'
            ];

            // Add optional time fields
            if ($request->start_time) {
                $testData['start_time'] = Carbon::parse($request->date . ' ' . $request->start_time);
            }
            if ($request->end_time) {
                $testData['end_time'] = Carbon::parse($request->date . ' ' . $request->end_time);
            }
            
            // Add optional numeric fields
            if ($request->duration) {
                $testData['duration'] = (int) $request->duration;
            }
            if ($request->total_marks) {
                $testData['total_marks'] = (int) $request->total_marks;
            }
            if ($request->passing_marks) {
                $testData['passing_marks'] = (int) $request->passing_marks;
            }

            // Create test
            $test = Test::create($testData);

            return response()->json([
                'success' => true,
                'message' => 'Test added successfully',
                'data' => [
                    'id' => (string) $test->_id,
                    'date' => $test->date->format('Y-m-d'),
                    'description' => $test->description,
                    'test_name' => $test->test_name,
                    'formatted_date' => $test->date->format('d M Y'),
                    'start_time' => $test->start_time ? $test->start_time->format('H:i') : null,
                    'end_time' => $test->end_time ? $test->end_time->format('H:i') : null
                ]
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Store Test Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a test
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTest($id)
    {
        try {
            // Find test by ID (handles both string and ObjectId)
            $test = Test::where('_id', $id)->first();
            
            if (!$test) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test not found'
                ], 404);
            }

            // Delete test
            $test->delete();

            return response()->json([
                'success' => true,
                'message' => 'Test deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Delete Test Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * Mark all Sundays in a month as holidays
 * 
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function markSundays(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'year' => 'required|integer|min:2020|max:2100',
        'month' => 'required|integer|min:1|max:12',
        'session_id' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $year = $request->year;
        $month = $request->month;
        $sessionId = $request->session_id ?? session('current_session_id');

        // Get all Sundays in the month
        $sundays = [];
        $sundayIds = [];
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Loop through each day in the month
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            if ($date->isSunday()) {
                // Check if holiday already exists for this Sunday
                $exists = Holiday::where('date', $date->copy()->startOfDay())
                               ->when($sessionId, function($query) use ($sessionId) {
                                   return $query->where('session_id', $sessionId);
                               })
                               ->first();
                
                if (!$exists) {
                    // Create Sunday holiday
                    $holiday = Holiday::create([
                        'date' => $date->copy()->startOfDay(),
                        'description' => 'Sunday Holiday',
                        'type' => 'sunday',
                        'session_id' => $sessionId
                    ]);
                    
                    $sundayData = [
                        'id' => (string) $holiday->_id,
                        'date' => $holiday->date->format('Y-m-d'),
                        'description' => $holiday->description,
                        'type' => $holiday->type,
                        'formatted_date' => $holiday->date->format('d M Y')
                    ];
                    
                    $sundays[] = $sundayData;
                    $sundayIds[$holiday->date->format('Y-m-d')] = (string) $holiday->_id;
                } else {
                    // Sunday already exists, just add its ID to the map
                    $sundayIds[$date->format('Y-m-d')] = (string) $exists->_id;
                }
            }
        }

        $message = count($sundays) > 0 
            ? count($sundays) . ' Sunday(s) marked as holidays'
            : 'All Sundays are already marked as holidays';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $sundays,
            'ids' => $sundayIds
        ], count($sundays) > 0 ? 201 : 200);
        
    } catch (\Exception $e) {
        Log::error('Mark Sundays Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark Sundays',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get all events (holidays and tests) for calendar
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents(Request $request)
    {
        try {
            $sessionId = $request->session_id ?? session('current_session_id');
            
            // Get holidays
            $holidays = Holiday::when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->get()
            ->map(function($holiday) {
                return [
                    'id' => 'holiday-' . (string) $holiday->_id,
                    'title' => $holiday->description,
                    'start' => $holiday->date->format('Y-m-d'),
                    'allDay' => true,
                    'className' => $holiday->type === 'sunday' ? 'fc-event-sunday' : 'fc-event-holiday',
                    'backgroundColor' => $holiday->type === 'sunday' ? '#ffc107' : '#dc3545',
                    'borderColor' => $holiday->type === 'sunday' ? '#ffc107' : '#dc3545',
                    'extendedProps' => [
                        'type' => 'holiday',
                        'eventId' => (string) $holiday->_id,
                        'holidayType' => $holiday->type
                    ]
                ];
            });

            // Get tests
            $tests = Test::when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->get()
            ->map(function($test) {
                return [
                    'id' => 'test-' . (string) $test->_id,
                    'title' => $test->description,
                    'start' => $test->date->format('Y-m-d'),
                    'allDay' => true,
                    'className' => 'fc-event-test',
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#007bff',
                    'extendedProps' => [
                        'type' => 'test',
                        'eventId' => (string) $test->_id,
                        'test_name' => $test->test_name,
                        'start_time' => $test->start_time ? $test->start_time->format('H:i') : null,
                        'end_time' => $test->end_time ? $test->end_time->format('H:i') : null
                    ]
                ];
            });

            // Merge both collections
            $events = $holidays->concat($tests)->values();

            return response()->json([
                'success' => true,
                'data' => $events
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Get Events Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a holiday
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHoliday(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $holiday = Holiday::where('_id', $id)->first();
            
            if (!$holiday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Holiday not found'
                ], 404);
            }

            $holiday->update([
                'date' => Carbon::parse($request->date),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully',
                'data' => [
                    'id' => (string) $holiday->_id,
                    'date' => $holiday->date->format('Y-m-d'),
                    'description' => $holiday->description
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update Holiday Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a test
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $test = Test::where('_id', $id)->first();
            
            if (!$test) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test not found'
                ], 404);
            }

            $test->update([
                'date' => Carbon::parse($request->date),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test updated successfully',
                'data' => [
                    'id' => (string) $test->_id,
                    'date' => $test->date->format('Y-m-d'),
                    'description' => $test->description
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update Test Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update test',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}