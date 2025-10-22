<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates and boots the Laravel application for testing.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        // Load the Laravel bootstrap file
        $app = require __DIR__ . '/../bootstrap/app.php';

        // Boot the app kernel
        $app->make(Kernel::class)->bootstrap();

        // Return the application instance to PHPUnit
        return $app;
    }
}
