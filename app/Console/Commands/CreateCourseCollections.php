<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Master\Courses;
use Illuminate\Support\Facades\DB;

class CreateCourseCollections extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'students:create-course-collections {--force : Force recreation of collections}';

    /**
     * The console command description.
     */
    protected $description = 'Create MongoDB collections for each course to store student data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting course collection creation...');
        
        $courses = Courses::all();
        $created = 0;
        $skipped = 0;
        $errors = 0;

        if ($courses->isEmpty()) {
            $this->error(' No courses found in the database!');
            return Command::FAILURE;
        }

        $this->info("  Found {$courses->count()} courses");
        
        $db = DB::connection('mongodb')->getMongoDB();
        
        foreach ($courses as $course) {
            try {
                $collectionName = $course->generateStudentCollectionName();
                
                // Check if collection already exists
                $existingCollections = iterator_to_array($db->listCollections([
                    'filter' => ['name' => $collectionName]
                ]));
                
                if (!empty($existingCollections) && !$this->option('force')) {
                    $this->warn("   Collection '{$collectionName}' already exists - skipping");
                    $skipped++;
                    continue;
                }
                
                if (!empty($existingCollections) && $this->option('force')) {
                    $this->warn("  Dropping existing collection '{$collectionName}'");
                    $db->dropCollection($collectionName);
                }
                
                // Create collection
                $db->createCollection($collectionName);
                
                // Create indexes
                $collection = $db->selectCollection($collectionName);
                
                // Index on roll_no (unique)
                $collection->createIndex(['roll_no' => 1], ['unique' => true]);
                
                // Index on course_id
                $collection->createIndex(['course_id' => 1]);
                
                // Index on email
                $collection->createIndex(['email' => 1]);
                
                // Index on status
                $collection->createIndex(['status' => 1]);
                
                // Index on fee_status
                $collection->createIndex(['fee_status' => 1]);
                
                // Index on admission_date
                $collection->createIndex(['admission_date' => -1]);
                
                // Update course with collection name
                $course->student_collection_name = $collectionName;
                $course->save();
                
                $this->info("  Created collection: {$collectionName} (Course: {$course->course_name})");
                $created++;
                
            } catch (\Exception $e) {
                $this->error(" Failed to create collection for '{$course->course_name}': " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->newLine();
        $this->info('  Summary:');
        $this->info("     Created: {$created}");
        $this->info("      Skipped: {$skipped}");
        $this->info("    Errors: {$errors}");
        
        if ($errors > 0) {
            return Command::FAILURE;
        }
        
        $this->newLine();
        $this->info('✨ Course collections created successfully!');
        
        return Command::SUCCESS;
    }
}