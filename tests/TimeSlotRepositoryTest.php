<?php

use App\Models\TimeSlot;
use App\Repositories\TimeSlotRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimeSlotRepositoryTest extends TestCase
{
    use MakeTimeSlotTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var TimeSlotRepository
     */
    protected $timeSlotRepo;

    public function setUp()
    {
        parent::setUp();
        $this->timeSlotRepo = App::make(TimeSlotRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateTimeSlot()
    {
        $timeSlot = $this->fakeTimeSlotData();
        $createdTimeSlot = $this->timeSlotRepo->create($timeSlot);
        $createdTimeSlot = $createdTimeSlot->toArray();
        $this->assertArrayHasKey('id', $createdTimeSlot);
        $this->assertNotNull($createdTimeSlot['id'], 'Created TimeSlot must have id specified');
        $this->assertNotNull(TimeSlot::find($createdTimeSlot['id']), 'TimeSlot with given id must be in DB');
        $this->assertModelData($timeSlot, $createdTimeSlot);
    }

    /**
     * @test read
     */
    public function testReadTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $dbTimeSlot = $this->timeSlotRepo->find($timeSlot->id);
        $dbTimeSlot = $dbTimeSlot->toArray();
        $this->assertModelData($timeSlot->toArray(), $dbTimeSlot);
    }

    /**
     * @test update
     */
    public function testUpdateTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $fakeTimeSlot = $this->fakeTimeSlotData();
        $updatedTimeSlot = $this->timeSlotRepo->update($fakeTimeSlot, $timeSlot->id);
        $this->assertModelData($fakeTimeSlot, $updatedTimeSlot->toArray());
        $dbTimeSlot = $this->timeSlotRepo->find($timeSlot->id);
        $this->assertModelData($fakeTimeSlot, $dbTimeSlot->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteTimeSlot()
    {
        $timeSlot = $this->makeTimeSlot();
        $resp = $this->timeSlotRepo->delete($timeSlot->id);
        $this->assertTrue($resp);
        $this->assertNull(TimeSlot::find($timeSlot->id), 'TimeSlot should not exist in DB');
    }
}
