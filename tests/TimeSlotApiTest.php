<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimeSlotApiTest extends TestCase
{
    use MakeTimeSlotTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateTimeSlot()
    {
        $timeSlot = $this->fakeTimeSlotData();
        $this->json('POST', '/api/v1/timeSlots', $timeSlot);

        $this->assertApiResponse($timeSlot);
    }

    /**
     * @test
     */
    public function testReadTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $this->json('GET', '/api/v1/timeSlots/'.$timeSlot->id);

        $this->assertApiResponse($timeSlot->toArray());
    }

    /**
     * @test
     */
    public function testUpdateTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $editedTimeSlot = $this->fakeTimeSlotData();

        $this->json('PUT', '/api/v1/timeSlots/'.$timeSlot->id, $editedTimeSlot);

        $this->assertApiResponse($editedTimeSlot);
    }

    /**
     * @test
     */
    public function testDeleteTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $this->json('DELETE', '/api/v1/timeSlots/'.$timeSlot->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/timeSlots/'.$timeSlot->id);

        $this->assertResponseStatus(404);
    }
}
