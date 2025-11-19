<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student\SMstudents;
use App\Services\RollNumberService;

class AssignRollNumbers extends Command
{
    protected $signature = 'students:assign-roll-numbers {--dry-run : Preview without saving}';
    protected $description = 'Assign roll numbers to students who don\'t have them';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🎓 Finding students without roll numbers...');
        $this->newLine();

        // Find students without roll numbers
        $students = SMstudents::where(function($query) {
            $query->whereNull('roll_no')
                  ->orWhere('roll_no', '')
                  ->orWhere('roll_no', 'like', 'SM%'); // Old format
        })->get();

        if ($students->isEmpty()) {
            $this->info('  All students already have roll numbers!');
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

        foreach ($students as $student) {
            try {
                $oldRollNo = $student->roll_no ?? 'NONE';
                
                $newRollNo = RollNumberService::generateUniqueRollNumber(
                    $student->course_id ?? null,
                    $student->course_name ?? $student->courseName ?? null,
                    $student->batch_id ?? null,
                    $student->batch_name ?? $student->batchName ?? null
                );

                if (!$dryRun) {
                    $student->roll_no = $newRollNo;
                    $student->save();
                }

                $assigned++;
                
                $this->newLine();
                $this->line("  {$student->student_name}: {$oldRollNo} → {$newRollNo}");
                
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("❌ Failed for {$student->student_name}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=================================');
        $this->info('📊 SUMMARY:');
        $this->info('=================================');
        $this->info("  Assigned: {$assigned}");
        $this->error("❌ Failed: {$failed}");
        $this->info("📝 Total: {$students->count()}");
        
        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  This was a DRY RUN. Run without --dry-run to save changes.');
        } else {
            $this->newLine();
            $this->info('  Roll numbers have been assigned and saved!');
        }

        return 0;
    }
}