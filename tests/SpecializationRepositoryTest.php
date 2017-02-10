<?php

use App\Models\Specialization;
use App\Repositories\SpecializationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpecializationRepositoryTest extends TestCase
{
    use MakeSpecializationTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SpecializationRepository
     */
    protected $specializationRepo;

    public function setUp()
    {
        parent::setUp();
        $this->specializationRepo = App::make(SpecializationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSpecialization()
    {
        $specialization = $this->fakeSpecializationData();
        $createdSpecialization = $this->specializationRepo->create($specialization);
        $createdSpecialization = $createdSpecialization->toArray();
        $this->assertArrayHasKey('id', $createdSpecialization);
        $this->assertNotNull($createdSpecialization['id'], 'Created Specialization must have id specified');
        $this->assertNotNull(Specialization::find($createdSpecialization['id']), 'Specialization with given id must be in DB');
        $this->assertModelData($specialization, $createdSpecialization);
    }

    /**
     * @test read
     */
    public function testReadSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $dbSpecialization = $this->specializationRepo->find($specialization->id);
        $dbSpecialization = $dbSpecialization->toArray();
        $this->assertModelData($specialization->toArray(), $dbSpecialization);
    }

    /**
     * @test update
     */
    public function testUpdateSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $fakeSpecialization = $this->fakeSpecializationData();
        $updatedSpecialization = $this->specializationRepo->update($fakeSpecialization, $specialization->id);
        $this->assertModelData($fakeSpecialization, $updatedSpecialization->toArray());
        $dbSpecialization = $this->specializationRepo->find($specialization->id);
        $this->assertModelData($fakeSpecialization, $dbSpecialization->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSpecialization()
    {
        $specialization = $this->makeSpecialization();
        $resp = $this->specializationRepo->delete($specialization->id);
        $this->assertTrue($resp);
        $this->assertNull(Specialization::find($specialization->id), 'Specialization should not exist in DB');
    }
}
