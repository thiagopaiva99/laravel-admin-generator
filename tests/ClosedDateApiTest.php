<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClosedDateApiTest extends TestCase
{
    use MakeClosedDateTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateClosedDate()
    {
        $closedDate = $this->fakeClosedDateData();
        $this->json('POST', '/api/v1/closedDates', $closedDate);

        $this->assertApiResponse($closedDate);
    }

    /**
     * @test
     */
    public function testReadClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $this->json('GET', '/api/v1/closedDates/'.$closedDate->id);

        $this->assertApiResponse($closedDate->toArray());
    }

    /**
     * @test
     */
    public function testUpdateClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $editedClosedDate = $this->fakeClosedDateData();

        $this->json('PUT', '/api/v1/closedDates/'.$closedDate->id, $editedClosedDate);

        $this->assertApiResponse($editedClosedDate);
    }

    /**
     * @test
     */
    public function testDeleteClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $this->json('DELETE', '/api/v1/closedDates/'.$closedDate->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/closedDates/'.$closedDate->id);

        $this->assertResponseStatus(404);
    }
}
