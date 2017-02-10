<?php

use App\Models\Exam;
use App\Repositories\ExamRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExamRepositoryTest extends TestCase
{
    use MakeExamTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ExamRepository
     */
    protected $examRepo;

    public function setUp()
    {
        parent::setUp();
        $this->examRepo = App::make(ExamRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateExam()
    {
        $exam = $this->fakeExamData();
        $createdExam = $this->examRepo->create($exam);
        $createdExam = $createdExam->toArray();
        $this->assertArrayHasKey('id', $createdExam);
        $this->assertNotNull($createdExam['id'], 'Created Exam must have id specified');
        $this->assertNotNull(Exam::find($createdExam['id']), 'Exam with given id must be in DB');
        $this->assertModelData($exam, $createdExam);
    }

    /**
     * @test read
     */
    public function testReadExam()
    {
        $exam = $this->makeExam();
        $dbExam = $this->examRepo->find($exam->id);
        $dbExam = $dbExam->toArray();
        $this->assertModelData($exam->toArray(), $dbExam);
    }

    /**
     * @test update
     */
    public function testUpdateExam()
    {
        $exam = $this->makeExam();
        $fakeExam = $this->fakeExamData();
        $updatedExam = $this->examRepo->update($fakeExam, $exam->id);
        $this->assertModelData($fakeExam, $updatedExam->toArray());
        $dbExam = $this->examRepo->find($exam->id);
        $this->assertModelData($fakeExam, $dbExam->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteExam()
    {
        $exam = $this->makeExam();
        $resp = $this->examRepo->delete($exam->id);
        $this->assertTrue($resp);
        $this->assertNull(Exam::find($exam->id), 'Exam should not exist in DB');
    }
}
