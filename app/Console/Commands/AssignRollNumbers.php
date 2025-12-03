<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Student\SMstudents;
// use App\Services\RollNumberService;

// class AssignRollNumbers extends Command
// {
// protected $signature = 'students:assign-roll-numbers 
//                             {--batch= : Assign only for specific batch ID}
//                             {--course= : Assign only for specific course ID}
//                             {--dry-run : Preview without saving}';
//         protected $description = 'Assign roll numbers to students who don\'t have them';

//     public function handle()
//     {
//         $dryRun = $this->option('dry-run');

//         $this->info('🎓 Finding students without roll numbers...');
//         $this->newLine();

//         // Find students without roll numbers
//         $students = SMstudents::where(function($query) {
//             $query->whereNull('roll_no')
//                   ->orWhere('roll_no', '')
//                   ->orWhere('roll_no', 'like', 'SM%'); // Old format
//         })->get();

//         if ($students->isEmpty()) {
//             $this->info('  All students already have roll numbers!');
//             return 0;
//         }

//         $this->info("Found {$students->count()} students needing roll numbers");
//         $this->newLine();

//         if ($dryRun) {
//             $this->warn('🔍 DRY RUN MODE - No changes will be saved');
//             $this->newLine();
//         }

//         $bar = $this->output->createProgressBar($students->count());
//         $bar->start();

//         $assigned = 0;
//         $failed = 0;

//         foreach ($students as $student) {
//             try {
//                 $oldRollNo = $student->roll_no ?? 'NONE';
                
//                 $newRollNo = RollNumberService::generateUniqueRollNumber(
//                     $student->course_id ?? null,
//                     $student->course_name ?? $student->courseName ?? null,
//                     $student->batch_id ?? null,
//                     $student->batch_name ?? $student->batchName ?? null
//                 );

//                 if (!$dryRun) {
//                     $student->roll_no = $newRollNo;
//                     $student->save();
//                 }

//                 $assigned++;
                
//                 $this->newLine();
//                 $this->line("  {$student->student_name}: {$oldRollNo} → {$newRollNo}");
                
//             } catch (\Exception $e) {
//                 $failed++;
//                 $this->newLine();
//                 $this->error("❌ Failed for {$student->student_name}: " . $e->getMessage());
//             }

//             $bar->advance();
//         }

//         $bar->finish();
//         $this->newLine(2);

//         // Summary
//         $this->info('=================================');
//         $this->info('📊 SUMMARY:');
//         $this->info('=================================');
//         $this->info("  Assigned: {$assigned}");
//         $this->error("❌ Failed: {$failed}");
//         $this->info("📝 Total: {$students->count()}");
        
//         if ($dryRun) {
//             $this->newLine();
//             $this->warn('⚠️  This was a DRY RUN. Run without --dry-run to save changes.');
//         } else {
//             $this->newLine();
//             $this->info('  Roll numbers have been assigned and saved!');
//         }

//         return 0;
//     }
// }


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student\SMstudents;
use App\Services\RollNumberService;
use Illuminate\Support\Facades\Log;

class AssignRollNumbers extends Command
{
    protected $signature = 'students:assign-roll-numbers 
                            {--batch= : Assign only for specific batch ID}
                            {--course= : Assign only for specific course ID}
                            {--dry-run : Preview without saving}';
    
    protected $description = 'Assign roll numbers to students who don\'t have them';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $batchFilter = $this->option('batch');
        $courseFilter = $this->option('course');

        $this->info('🎓 Finding students without roll numbers...');
        $this->newLine();

        // Build query
        $query = SMstudents::where(function($q) {
            $q->whereNull('roll_no')
              ->orWhere('roll_no', '')
              ->orWhere('roll_no', 'N/A')
              ->orWhere('roll_no', 'like', 'SM%') // Old format
              ->orWhere('roll_no', 'like', 'STU%'); // Old format
        });

        // Apply filters
        if ($batchFilter) {
            $query->where('batch_id', $batchFilter);
            $this->info("📚 Filtering by batch: {$batchFilter}");
        }

        if ($courseFilter) {
            $query->where('course_id', $courseFilter);
            $this->info("🎯 Filtering by course: {$courseFilter}");
        }

        $students = $query->with(['course', 'batch'])->get();

        if ($students->isEmpty()) {
            $this->info('✅ All students already have roll numbers!');
            return 0;
        }

        $this->info("Found {$students->count()} students needing roll numbers");
        $this->newLine();

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be saved');
            $this->newLine();
        }

        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        $assigned = 0;
        $failed = 0;
        $results = [];

        foreach ($students as $student) {
            try {
                $oldRollNo = $student->roll_no ?? 'NONE';
                
                // Get course name
                $courseName = null;
                if ($student->course) {
                    $courseName = $student->course->name ?? $student->course->course_name;
                } else {
                    $courseName = $student->course_name ?? $student->courseName;
                }

                // Get batch name
                $batchName = null;
                if ($student->batch) {
                    $batchName = $student->batch->batch_id ?? $student->batch->name;
                } else {
                    $batchName = $student->batch_name ?? $student->batchName;
                }

                // Generate new roll number
                $newRollNo = RollNumberService::generateUniqueRollNumber(
                    $student->course_id ?? null,
                    $courseName,
                    $student->batch_id ?? null,
                    $batchName
                );

                // Check if roll number already exists
                $exists = SMstudents::where('roll_no', $newRollNo)
                    ->where('_id', '!=', $student->_id)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Roll number {$newRollNo} already exists");
                }

                if (!$dryRun) {
                    $student->roll_no = $newRollNo;
                    $student->save();
                    
                    Log::info('✅ Roll number assigned', [
                        'student_id' => (string)$student->_id,
                        'name' => $student->student_name ?? $student->name,
                        'old_roll_no' => $oldRollNo,
                        'new_roll_no' => $newRollNo
                    ]);
                }

                $assigned++;
                $results[] = [
                    'name' => $student->student_name ?? $student->name,
                    'old' => $oldRollNo,
                    'new' => $newRollNo,
                    'course' => $courseName ?? 'N/A',
                    'batch' => $batchName ?? 'N/A'
                ];
                
            } catch (\Exception $e) {
                $failed++;
                $results[] = [
                    'name' => $student->student_name ?? $student->name ?? 'Unknown',
                    'error' => $e->getMessage()
                ];
                
                Log::error('❌ Failed to assign roll number', [
                    'student_id' => (string)$student->_id,
                    'error' => $e->getMessage()
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Show detailed results
        $this->info('=================================');
        $this->info('📋 DETAILED RESULTS:');
        $this->info('=================================');
        $this->newLine();

        foreach ($results as $result) {
            if (isset($result['error'])) {
                $this->error("❌ {$result['name']}: {$result['error']}");
            } else {
                $this->line("✅ {$result['name']}");
                $this->line("   Course: {$result['course']} | Batch: {$result['batch']}");
                $this->line("   {$result['old']} → {$result['new']}");
                $this->newLine();
            }
        }

        // Summary
        $this->newLine();
        $this->info('=================================');
        $this->info('📊 SUMMARY:');
        $this->info('=================================');
        $this->info("✅ Assigned: {$assigned}");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
        }
        $this->info("📝 Total: {$students->count()}");
        
        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  This was a DRY RUN. Run without --dry-run to save changes.');
        } else {
            $this->newLine();
            $this->info('✅ Roll numbers have been assigned and saved!');
        }

        return $failed > 0 ? 1 : 0;
    }
}