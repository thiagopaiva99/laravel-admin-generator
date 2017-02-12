<?php

use Faker\Factory as Faker;
use App\Models\Specialization;
use App\Repositories\SpecializationRepository;

trait MakeSpecializationTrait
{
    /**
     * Create fake instance of Specialization and save it in database
     *
     * @param array $specializationFields
     * @return Specialization
     */
    public function makeSpecialization($specializationFields = [])
    {
        /** @var SpecializationRepository $specializationRepo */
        $specializationRepo = App::make(SpecializationRepository::class);
        $theme = $this->fakeSpecializationData($specializationFields);
        return $specializationRepo->create($theme);
    }

    /**
     * Get fake instance of Specialization
     *
     * @param array $specializationFields
     * @return Specialization
     */
    public function fakeSpecialization($specializationFields = [])
    {
        return new Specialization($this->fakeSpecializationData($specializationFields));
    }

    /**
     * Get fake data of Specialization
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSpecializationData($specializationFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $specializationFields);
    }
}
