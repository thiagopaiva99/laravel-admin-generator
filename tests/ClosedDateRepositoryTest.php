<?php

use App\Models\ClosedDate;
use App\Repositories\ClosedDateRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClosedDateRepositoryTest extends TestCase
{
    use MakeClosedDateTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ClosedDateRepository
     */
    protected $closedDateRepo;

    public function setUp()
    {
        parent::setUp();
        $this->closedDateRepo = App::make(ClosedDateRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateClosedDate()
    {
        $closedDate = $this->fakeClosedDateData();
        $createdClosedDate = $this->closedDateRepo->create($closedDate);
        $createdClosedDate = $createdClosedDate->toArray();
        $this->assertArrayHasKey('id', $createdClosedDate);
        $this->assertNotNull($createdClosedDate['id'], 'Created ClosedDate must have id specified');
        $this->assertNotNull(ClosedDate::find($createdClosedDate['id']), 'ClosedDate with given id must be in DB');
        $this->assertModelData($closedDate, $createdClosedDate);
    }

    /**
     * @test read
     */
    public function testReadClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $dbClosedDate = $this->closedDateRepo->find($closedDate->id);
        $dbClosedDate = $dbClosedDate->toArray();
        $this->assertModelData($closedDate->toArray(), $dbClosedDate);
    }

    /**
     * @test update
     */
    public function testUpdateClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $fakeClosedDate = $this->fakeClosedDateData();
        $updatedClosedDate = $this->closedDateRepo->update($fakeClosedDate, $closedDate->id);
        $this->assertModelData($fakeClosedDate, $updatedClosedDate->toArray());
        $dbClosedDate = $this->closedDateRepo->find($closedDate->id);
        $this->assertModelData($fakeClosedDate, $dbClosedDate->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteClosedDate()
    {
        $closedDate = $this->makeClosedDate();
        $resp = $this->closedDateRepo->delete($closedDate->id);
        $this->assertTrue($resp);
        $this->assertNull(ClosedDate::find($closedDate->id), 'ClosedDate should not exist in DB');
    }
}
