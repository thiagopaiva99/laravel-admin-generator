<?php

use App\Models\HealthPlan;
use App\Repositories\HealthPlanRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HealthPlanRepositoryTest extends TestCase
{
    use MakeHealthPlanTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var HealthPlanRepository
     */
    protected $healthPlanRepo;

    public function setUp()
    {
        parent::setUp();
        $this->healthPlanRepo = App::make(HealthPlanRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateHealthPlan()
    {
        $healthPlan = $this->fakeHealthPlanData();
        $createdHealthPlan = $this->healthPlanRepo->create($healthPlan);
        $createdHealthPlan = $createdHealthPlan->toArray();
        $this->assertArrayHasKey('id', $createdHealthPlan);
        $this->assertNotNull($createdHealthPlan['id'], 'Created HealthPlan must have id specified');
        $this->assertNotNull(HealthPlan::find($createdHealthPlan['id']), 'HealthPlan with given id must be in DB');
        $this->assertModelData($healthPlan, $createdHealthPlan);
    }

    /**
     * @test read
     */
    public function testReadHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $dbHealthPlan = $this->healthPlanRepo->find($healthPlan->id);
        $dbHealthPlan = $dbHealthPlan->toArray();
        $this->assertModelData($healthPlan->toArray(), $dbHealthPlan);
    }

    /**
     * @test update
     */
    public function testUpdateHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $fakeHealthPlan = $this->fakeHealthPlanData();
        $updatedHealthPlan = $this->healthPlanRepo->update($fakeHealthPlan, $healthPlan->id);
        $this->assertModelData($fakeHealthPlan, $updatedHealthPlan->toArray());
        $dbHealthPlan = $this->healthPlanRepo->find($healthPlan->id);
        $this->assertModelData($fakeHealthPlan, $dbHealthPlan->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteHealthPlan()
    {
        $healthPlan = $this->makeHealthPlan();
        $resp = $this->healthPlanRepo->delete($healthPlan->id);
        $this->assertTrue($resp);
        $this->assertNull(HealthPlan::find($healthPlan->id), 'HealthPlan should not exist in DB');
    }
}
