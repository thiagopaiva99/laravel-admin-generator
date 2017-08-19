<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HealthPlanApiTest extends TestCase
{
    use MakeHealthPlanTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateHealthPlan()
    {
        $healthPlan = $this->fakeHealthPlanData();
        $this->json('POST', '/api/v1/healthPlans', $healthPlan);

        $this->assertApiResponse($healthPlan);
    }

    /**
     * @test
     */
    public function testReadHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $this->json('GET', '/api/v1/healthPlans/'.$healthPlan->id);

        $this->assertApiResponse($healthPlan->toArray());
    }

    /**
     * @test
     */
    public function testUpdateHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $editedHealthPlan = $this->fakeHealthPlanData();

        $this->json('PUT', '/api/v1/healthPlans/'.$healthPlan->id, $editedHealthPlan);

        $this->assertApiResponse($editedHealthPlan);
    }

    /**
     * @test
     */
    public function testDeleteHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $this->json('DELETE', '/api/v1/healthPlans/'.$healthPlan->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/healthPlans/'.$healthPlan->id);

        $this->assertResponseStatus(404);
    }
}
