<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppointmentApiTest extends TestCase
{
    use MakeAppointmentTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAppointment()
    {
        $appointment = $this->fakeAppointmentData();
        $this->json('POST', '/api/v1/appointments', $appointment);

        $this->assertApiResponse($appointment);
    }

    /**
     * @test
     */
    public function testReadAppointment()
    {
        $appointment = $this->makeAppointment();
        $this->json('GET', '/api/v1/appointments/'.$appointment->id);

        $this->assertApiResponse($appointment->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAppointment()
    {
        $appointment = $this->makeAppointment();
        $editedAppointment = $this->fakeAppointmentData();

        $this->json('PUT', '/api/v1/appointments/'.$appointment->id, $editedAppointment);

        $this->assertApiResponse($editedAppointment);
    }

    /**
     * @test
     */
    public function testDeleteAppointment()
    {
        $appointment = $this->makeAppointment();
        $this->json('DELETE', '/api/v1/appointments/'.$appointment->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/appointments/'.$appointment->id);

        $this->assertResponseStatus(404);
    }
}
