<?php

use Faker\Factory as Faker;
use App\Models\Local;
use App\Repositories\LocalRepository;

trait MakeLocalTrait
{
    /**
     * Create fake instance of Local and save it in database
     *
     * @param array $localFields
     * @return Local
     */
    public function makeLocal($localFields = [])
    {
        /** @var LocalRepository $localRepo */
        $localRepo = App::make(LocalRepository::class);
        $theme = $this->fakeLocalData($localFields);
        return $localRepo->create($theme);
    }

    /**
     * Get fake instance of Local
     *
     * @param array $localFields
     * @return Local
     */
    public function fakeLocal($localFields = [])
    {
        return new Local($this->fakeLocalData($localFields));
    }

    /**
     * Get fake data of Local
     *
     * @param array $postFields
     * @return array
     */
    public function fakeLocalData($localFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'address' => $fake->word,
            'image_src' => $fake->word,
            'phone' => $fake->word,
            'amount' => $fake->word,
            'appointment_duration_in_minutes' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $localFields);
    }
}
