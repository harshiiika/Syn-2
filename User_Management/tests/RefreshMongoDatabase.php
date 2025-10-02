<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

trait RefreshMongoDatabase
{
    protected function refreshMongo(): void
    {
        try {
            DB::connection('mongodb')->collection('inquiries')->delete();
        } catch (\Throwable $e) {
            // ignore if collection doesn't exist yet
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshMongo();
    }
}
