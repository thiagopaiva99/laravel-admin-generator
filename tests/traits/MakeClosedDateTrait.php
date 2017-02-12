<?php

use Faker\Factory as Faker;
use App\Models\ClosedDate;
use App\Repositories\ClosedDateRepository;

trait MakeClosedDateTrait
{
    /**
     * Create fake instance of ClosedDate and save it in database
     *
     * @param array $closedDateFields
     * @return ClosedDate
     */
    public function makeClosedDate($closedDateFields = [])
    {
        /** @var ClosedDateRepository $closedDateRepo */
        $closedDateRepo = App::make(ClosedDateRepository::class);
        $theme = $this->fakeClosedDateData($closedDateFields);
        return $closedDateRepo->create($theme);
    }

    /**
     * Get fake instance of ClosedDate
     *
     * @param array $closedDateFields
     * @return ClosedDate
     */
    public function fakeClosedDate($closedDateFields = [])
    {
        return new ClosedDate($this->fakeClosedDateData($closedDateFields));
    }

    /**
     * Get fake data of ClosedDate
     *
     * @param array $postFields
     * @return array
     */
    public function fakeClosedDateData($closedDateFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'start_datetime' => $fake->date('Y-m-d H:i:s'),
            'end_datetime' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $closedDateFields);
    }
}
