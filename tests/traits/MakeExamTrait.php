<?php

use Faker\Factory as Faker;
use App\Models\Exam;
use App\Repositories\ExamRepository;

trait MakeExamTrait
{
    /**
     * Create fake instance of Exam and save it in database
     *
     * @param array $examFields
     * @return Exam
     */
    public function makeExam($examFields = [])
    {
        /** @var ExamRepository $examRepo */
        $examRepo = App::make(ExamRepository::class);
        $theme = $this->fakeExamData($examFields);
        return $examRepo->create($theme);
    }

    /**
     * Get fake instance of Exam
     *
     * @param array $examFields
     * @return Exam
     */
    public function fakeExam($examFields = [])
    {
        return new Exam($this->fakeExamData($examFields));
    }

    /**
     * Get fake data of Exam
     *
     * @param array $postFields
     * @return array
     */
    public function fakeExamData($examFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $examFields);
    }
}
