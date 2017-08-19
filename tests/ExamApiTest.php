<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExamApiTest extends TestCase
{
    use MakeExamTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateExam()
    {
        $exam = $this->fakeExamData();
        $this->json('POST', '/api/v1/exams', $exam);

        $this->assertApiResponse($exam);
    }

    /**
     * @test
     */
    public function testReadExam()
    {
        $exam = $this->makeExam();
        $this->json('GET', '/api/v1/exams/'.$exam->id);

        $this->assertApiResponse($exam->toArray());
    }

    /**
     * @test
     */
    public function testUpdateExam()
    {
        $exam = $this->makeExam();
        $editedExam = $this->fakeExamData();

        $this->json('PUT', '/api/v1/exams/'.$exam->id, $editedExam);

        $this->assertApiResponse($editedExam);
    }

    /**
     * @test
     */
    public function testDeleteExam()
    {
        $exam = $this->makeExam();
        $this->json('DELETE', '/api/v1/exams/'.$exam->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/exams/'.$exam->id);

        $this->assertResponseStatus(404);
    }
}
