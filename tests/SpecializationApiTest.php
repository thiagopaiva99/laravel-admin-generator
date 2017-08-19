<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpecializationApiTest extends TestCase
{
    use MakeSpecializationTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSpecialization()
    {
        $specialization = $this->fakeSpecializationData();
        $this->json('POST', '/api/v1/specializations', $specialization);

        $this->assertApiResponse($specialization);
    }

    /**
     * @test
     */
    public function testReadSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $this->json('GET', '/api/v1/specializations/'.$specialization->id);

        $this->assertApiResponse($specialization->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $editedSpecialization = $this->fakeSpecializationData();

        $this->json('PUT', '/api/v1/specializations/'.$specialization->id, $editedSpecialization);

        $this->assertApiResponse($editedSpecialization);
    }

    /**
     * @test
     */
    public function testDeleteSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $this->json('DELETE', '/api/v1/specializations/'.$specialization->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/specializations/'.$specialization->id);

        $this->assertResponseStatus(404);
    }
}
