<?php

use Faker\Factory as Faker;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;

trait MakeAppointmentTrait
{
    /**
     * Create fake instance of Appointment and save it in database
     *
     * @param array $appointmentFields
     * @return Appointment
     */
    public function makeAppointment($appointmentFields = [])
    {
        /** @var AppointmentRepository $appointmentRepo */
        $appointmentRepo = App::make(AppointmentRepository::class);
        $theme = $this->fakeAppointmentData($appointmentFields);
        return $appointmentRepo->create($theme);
    }

    /**
     * Get fake instance of Appointment
     *
     * @param array $appointmentFields
     * @return Appointment
     */
    public function fakeAppointment($appointmentFields = [])
    {
        return new Appointment($this->fakeAppointmentData($appointmentFields));
    }

    /**
     * Get fake data of Appointment
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAppointmentData($appointmentFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'appointment_start' => $fake->word,
            'appointment_end' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $appointmentFields);
    }
}
