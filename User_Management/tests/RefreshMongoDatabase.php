<?php

namespace Tests;

trait RefreshMongoDatabase 
{
    protected function mongoModelsToRefresh(): array
    {
        return [
            \App\Models\Master\FeesMaster::class, // only FeesMaster
        ];
    }

    protected function refreshMongoCollections(): void
    {
        foreach ($this->mongoModelsToRefresh() as $model) {
            if (!class_exists($model)) continue;
            try {
                $model::query()->delete();
            } catch (\Throwable $e) {
                // ignore cleanup errors
            }
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshMongoCollections();
    }

    protected function tearDown(): void
    {
        $this->refreshMongoCollections();
        parent::tearDown();
    }
}
