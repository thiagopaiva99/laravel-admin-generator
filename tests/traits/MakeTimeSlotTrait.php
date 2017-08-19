<?php

use Faker\Factory as Faker;
use App\Models\TimeSlot;
use App\Repositories\TimeSlotRepository;

trait MakeTimeSlotTrait
{
    /**
     * Create fake instance of TimeSlot and save it in database
     *
     * @param array $timeSlotFields
     * @return TimeSlot
     */
    public function makeTimeSlot($timeSlotFields = [])
    {
        /** @var TimeSlotRepository $timeSlotRepo */
        $timeSlotRepo = App::make(TimeSlotRepository::class);
        $theme = $this->fakeTimeSlotData($timeSlotFields);
        return $timeSlotRepo->create($theme);
    }

    /**
     * Get fake instance of TimeSlot
     *
     * @param array $timeSlotFields
     * @return TimeSlot
     */
    public function fakeTimeSlot($timeSlotFields = [])
    {
        return new TimeSlot($this->fakeTimeSlotData($timeSlotFields));
    }

    /**
     * Get fake data of TimeSlot
     *
     * @param array $postFields
     * @return array
     */
    public function fakeTimeSlotData($timeSlotFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'slot_time' => $fake->randomDigitNotNull,
            'day_of_week' => $fake->randomDigitNotNull,
            'slot_time_start' => $fake->word,
            'slot_time_end' => $fake->word,
            'slot_date' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $timeSlotFields);
    }
}
