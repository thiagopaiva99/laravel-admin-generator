<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocalApiTest extends TestCase
{
    use MakeLocalTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLocal()
    {
        $local = $this->fakeLocalData();
        $this->json('POST', '/api/v1/locals', $local);

        $this->assertApiResponse($local);
    }

    /**
     * @test
     */
    public function testReadLocal()
    {
        $local = $this->makeLocal();
        $this->json('GET', '/api/v1/locals/'.$local->id);

        $this->assertApiResponse($local->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLocal()
    {
        $local = $this->makeLocal();
        $editedLocal = $this->fakeLocalData();

        $this->json('PUT', '/api/v1/locals/'.$local->id, $editedLocal);

        $this->assertApiResponse($editedLocal);
    }

    /**
     * @test
     */
    public function testDeleteLocal()
    {
        $local = $this->makeLocal();
        $this->json('DELETE', '/api/v1/locals/'.$local->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/locals/'.$local->id);

        $this->assertResponseStatus(404);
    }
}
