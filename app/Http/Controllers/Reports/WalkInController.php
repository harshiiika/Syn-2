<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Student\Inquiry;
use App\Models\Student\Onboard;
use App\Models\Master\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WalkinController extends Controller
{
    /**
     * Display Walk-in / Onboarding Analytics
     */
       public function index(Request $request)
    {
        try {
            $session = $request->get('session', session('current_session', '2025-2026'));
            
            // Get all courses for the dropdown
            $courses = Courses::where('status', 'active')->get();
            
            // Calculate analytics
            $analytics = $this->calculateAnalytics($session);
            
            // Course-wise data for charts
            $courseWiseData = $this->getCourseWiseConversion($session);
            
            // Get course type distribution
            $courseTypeData = $this->getCourseTypeDistribution($session);
            
            // Get board type distribution
            $boardTypeData = $this->getBoardTypeDistribution($session);
            
            // Get medium type distribution
            $mediumTypeData = $this->getMediumTypeDistribution($session);
            
            Log::info('🎯 FINAL Walk-in Report Data:', [
                'session' => $session,
                'analytics' => $analytics,
                'course_wise_data' => $courseWiseData,
                'course_wise_count' => count($courseWiseData),
                'course_type' => $courseTypeData,
                'board_type' => $boardTypeData,
                'medium_type' => $mediumTypeData
            ]);
            
            return view('reports.walkin', compact(
                'analytics',
                'courseWiseData',
                'courseTypeData',
                'boardTypeData',
                'mediumTypeData',
                'courses',
                'session'
            ));
            
        } catch (\Exception $e) {
            Log::error('Walk-in Report Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error loading walk-in report: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate main analytics numbers
     */
     private function calculateAnalytics($session)
    {
        // Total Walk-in
        $totalWalkin = Inquiry::count();
        
        // Total Onboarding
        $totalOnboarding = Onboard::count();
        
        // Get all onboarding records to analyze
        $allOnboard = Onboard::all();
        
        Log::info('📋 Sample onboard records', [
            'total_count' => $allOnboard->count(),
            'sample_data' => $allOnboard->take(2)->map(function($item) {
                return [
                    'name' => $item->name,
                    'courseName' => $item->courseName ?? 'N/A',
                    'courseContent' => $item->courseContent ?? 'N/A',
                    'courseType' => $item->courseType ?? 'N/A',
                    'deliveryMode' => $item->deliveryMode ?? 'N/A',
                    'board' => $item->board ?? 'N/A',
                    'medium' => $item->medium ?? 'N/A',
                ];
            })->toArray()
        ]);
        
        // Test series only
        $testSeriesOnly = $allOnboard->filter(function($item) {
            $content = strtolower($item->courseContent ?? '');
            return (str_contains($content, 'test series') || str_contains($content, 'test'))
                   && !str_contains($content, 'study material');
        })->count();
        
        // Study Material only
        $studyMaterialOnly = $allOnboard->filter(function($item) {
            $content = strtolower($item->courseContent ?? '');
            return str_contains($content, 'study material')
                   && !str_contains($content, 'test series')
                   && !str_contains($content, 'test');
        })->count();
        
        // Test series & Study Material combined
        $testSeriesAndStudyMaterial = $allOnboard->filter(function($item) {
            $content = strtolower($item->courseContent ?? '');
            return (str_contains($content, 'test series') || str_contains($content, 'test'))
                   && str_contains($content, 'study material');
        })->count();
        
        // Class room course
        $classRoomCourse = $allOnboard->filter(function($item) {
            $content = strtolower($item->courseContent ?? '');
            return str_contains($content, 'class') && str_contains($content, 'course');
        })->count();
        
        // Live online class course
        $liveOnlineClass = $allOnboard->filter(function($item) {
            $mode = strtolower($item->deliveryMode ?? '');
            return $mode === 'online';
        })->count();
        
        // Recorded online class course
        $recordedOnlineClass = $allOnboard->filter(function($item) {
            $mode = strtolower($item->deliveryMode ?? '');
            return $mode === 'hybrid' || str_contains($mode, 'record');
        })->count();
        
        $result = [
            'total_walkin' => $totalWalkin,
            'total_onboarding' => $totalOnboarding,
            'test_series_only' => $testSeriesOnly,
            'class_room_course' => $classRoomCourse,
            'study_material_only' => $studyMaterialOnly,
            'live_online_class' => $liveOnlineClass,
            'test_series_and_study_material' => $testSeriesAndStudyMaterial,
            'recorded_online_class' => $recordedOnlineClass,
        ];
        
        Log::info('✅ Analytics Calculated', $result);
        
        return $result;
    }
    
    
    /**
     * Get course-wise conversion data for bar chart
     * Uses BOTH Courses master AND actual data from collections
     */
     private function getCourseWiseConversion($session)
    {
        Log::info('🔍 Starting course-wise conversion analysis...');
        
        // Get ALL distinct course names from both collections
        $inquiryCourses = Inquiry::all()->pluck('course_name')->filter()->unique();
        $onboardCoursesField1 = Onboard::all()->pluck('courseName')->filter()->unique();
        $onboardCoursesField2 = Onboard::all()->pluck('course_name')->filter()->unique();
        
        Log::info('📚 Found course names in collections:', [
            'inquiry_courses' => $inquiryCourses->toArray(),
            'onboard_courseName_field' => $onboardCoursesField1->toArray(),
            'onboard_course_name_field' => $onboardCoursesField2->toArray(),
        ]);
        
        // Merge all course names
        $allCourseNames = $inquiryCourses
            ->merge($onboardCoursesField1)
            ->merge($onboardCoursesField2)
            ->unique()
            ->filter()
            ->values();
        
        Log::info('🔗 All unique course names found:', [
            'total_unique_courses' => $allCourseNames->count(),
            'course_list' => $allCourseNames->toArray()
        ]);
        
        $data = [];
        
        foreach ($allCourseNames as $courseName) {
            if (empty($courseName) || $courseName === 'N/A') continue;
            
            // Count in inquiries
            $walkinCount = Inquiry::where('course_name', $courseName)->count();
            
            // Count in onboard (check both possible field names)
            $onboardingCount = Onboard::where(function($q) use ($courseName) {
                $q->where('courseName', $courseName)
                  ->orWhere('course_name', $courseName);
            })->count();
            
            Log::info("📊 Course: {$courseName}", [
                'walkin' => $walkinCount,
                'onboarding' => $onboardingCount
            ]);
            
            if ($walkinCount > 0 || $onboardingCount > 0) {
                $data[] = [
                    'course' => $courseName,
                    'walkin' => $walkinCount,
                    'onboarding' => $onboardingCount
                ];
            }
        }
        
        Log::info('✅ Course wise data prepared', [
            'total_courses_with_data' => count($data),
            'data' => $data
        ]);
        
        return $data;
    }
    
    /**
     * Get course type distribution (Pre-Foundation, Pre-Medical, Pre-Engineering)
     */
     private function getCourseTypeDistribution($session)
    {
        $allOnboard = Onboard::all();
        
        $distribution = [
            'Pre-Foundation' => 0,
            'Pre-Medical' => 0,
            'Pre-Engineering' => 0,
        ];
        
        foreach ($allOnboard as $item) {
            $type = strtolower($item->courseType ?? $item->course_type ?? '');
            
            if (str_contains($type, 'foundation')) {
                $distribution['Pre-Foundation']++;
            } elseif (str_contains($type, 'medical') || str_contains($type, 'neet')) {
                $distribution['Pre-Medical']++;
            } elseif (str_contains($type, 'engineering') || str_contains($type, 'iit') || str_contains($type, 'jee')) {
                $distribution['Pre-Engineering']++;
            }
        }
        
        Log::info('Course type distribution', $distribution);
        
        return $distribution;
    }
    
    /**
     * Get board type distribution
     */
    private function getBoardTypeDistribution($session)
    {
        $allOnboard = Onboard::all();
        
        $distribution = [
            'CBSE' => 0,
            'RBSE' => 0,
        ];
        
        foreach ($allOnboard as $item) {
            $board = strtoupper(trim($item->board ?? ''));
            
            if (str_contains($board, 'CBSE')) {
                $distribution['CBSE']++;
            } elseif (str_contains($board, 'RBSE')) {
                $distribution['RBSE']++;
            }
        }
        
        Log::info('Board distribution', $distribution);
        
        return $distribution;
    }
    
    /**
     * Get medium type distribution
     */
    private function getMediumTypeDistribution($session)
    {
        $allOnboard = Onboard::all();
        
        $distribution = [
            'English' => 0,
            'Hindi' => 0,
        ];
        
        foreach ($allOnboard as $item) {
            $medium = ucfirst(strtolower(trim($item->medium ?? '')));
            
            if ($medium === 'English') {
                $distribution['English']++;
            } elseif ($medium === 'Hindi') {
                $distribution['Hindi']++;
            }
        }
        
        Log::info('Medium distribution', $distribution);
        
        return $distribution;
    }
}