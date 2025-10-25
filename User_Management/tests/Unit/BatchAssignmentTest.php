<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User\BatchAssignment;
use Carbon\Carbon;

class BatchAssignmentTest extends TestCase
{
    /** @test */
    public function it_assigns_correct_shift_based_on_time()
    {
        $morningTime = Carbon::createFromTime(9, 0, 0);
        $eveningTime = Carbon::createFromTime(18, 0, 0);

        $this->assertEquals('Morning', BatchAssignment::getShift($morningTime));
        $this->assertEquals('Evening', BatchAssignment::getShift($eveningTime));
    }
}
