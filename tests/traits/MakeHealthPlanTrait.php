<?php

use Faker\Factory as Faker;
use App\Models\HealthPlan;
use App\Repositories\HealthPlanRepository;

trait MakeHealthPlanTrait
{
    /**
     * Create fake instance of HealthPlan and save it in database
     *
     * @param array $healthPlanFields
     * @return HealthPlan
     */
    public function makeHealthPlan($healthPlanFields = [])
    {
        /** @var HealthPlanRepository $healthPlanRepo */
        $healthPlanRepo = App::make(HealthPlanRepository::class);
        $theme = $this->fakeHealthPlanData($healthPlanFields);
        return $healthPlanRepo->create($theme);
    }

    /**
     * Get fake instance of HealthPlan
     *
     * @param array $healthPlanFields
     * @return HealthPlan
     */
    public function fakeHealthPlan($healthPlanFields = [])
    {
        return new HealthPlan($this->fakeHealthPlanData($healthPlanFields));
    }

    /**
     * Get fake data of HealthPlan
     *
     * @param array $postFields
     * @return array
     */
    public function fakeHealthPlanData($healthPlanFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $healthPlanFields);
    }
}
