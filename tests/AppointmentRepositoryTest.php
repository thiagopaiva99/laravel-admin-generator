<?php

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppointmentRepositoryTest extends TestCase
{
    use MakeAppointmentTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->appointmentRepo = App::make(AppointmentRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAppointment()
    {
        $appointment = $this->fakeAppointmentData();
        $createdAppointment = $this->appointmentRepo->create($appointment);
        $createdAppointment = $createdAppointment->toArray();
        $this->assertArrayHasKey('id', $createdAppointment);
        $this->assertNotNull($createdAppointment['id'], 'Created Appointment must have id specified');
        $this->assertNotNull(Appointment::find($createdAppointment['id']), 'Appointment with given id must be in DB');
        $this->assertModelData($appointment, $createdAppointment);
    }

    /**
     * @test read
     */
    public function testReadAppointment()
    {
        $appointment = $this->makeAppointment();
        $dbAppointment = $this->appointmentRepo->find($appointment->id);
        $dbAppointment = $dbAppointment->toArray();
        $this->assertModelData($appointment->toArray(), $dbAppointment);
    }

    /**
     * @test update
     */
    public function testUpdateAppointment()
    {
        $appointment = $this->makeAppointment();
        $fakeAppointment = $this->fakeAppointmentData();
        $updatedAppointment = $this->appointmentRepo->update($fakeAppointment, $appointment->id);
        $this->assertModelData($fakeAppointment, $updatedAppointment->toArray());
        $dbAppointment = $this->appointmentRepo->find($appointment->id);
        $this->assertModelData($fakeAppointment, $dbAppointment->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAppointment()
    {
        $appointment = $this->makeAppointment();
        $resp = $this->appointmentRepo->delete($appointment->id);
        $this->assertTrue($resp);
        $this->assertNull(Appointment::find($appointment->id), 'Appointment should not exist in DB');
    }
}
