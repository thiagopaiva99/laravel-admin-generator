<?php

use App\Models\Local;
use App\Repositories\LocalRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocalRepositoryTest extends TestCase
{
    use MakeLocalTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LocalRepository
     */
    protected $localRepo;

    public function setUp()
    {
        parent::setUp();
        $this->localRepo = App::make(LocalRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLocal()
    {
        $local = $this->fakeLocalData();
        $createdLocal = $this->localRepo->create($local);
        $createdLocal = $createdLocal->toArray();
        $this->assertArrayHasKey('id', $createdLocal);
        $this->assertNotNull($createdLocal['id'], 'Created Local must have id specified');
        $this->assertNotNull(Local::find($createdLocal['id']), 'Local with given id must be in DB');
        $this->assertModelData($local, $createdLocal);
    }

    /**
     * @test read
     */
    public function testReadLocal()
    {
        $local = $this->makeLocal();
        $dbLocal = $this->localRepo->find($local->id);
        $dbLocal = $dbLocal->toArray();
        $this->assertModelData($local->toArray(), $dbLocal);
    }

    /**
     * @test update
     */
    public function testUpdateLocal()
    {
        $local = $this->makeLocal();
        $fakeLocal = $this->fakeLocalData();
        $updatedLocal = $this->localRepo->update($fakeLocal, $local->id);
        $this->assertModelData($fakeLocal, $updatedLocal->toArray());
        $dbLocal = $this->localRepo->find($local->id);
        $this->assertModelData($fakeLocal, $dbLocal->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLocal()
    {
        $local = $this->makeLocal();
        $resp = $this->localRepo->delete($local->id);
        $this->assertTrue($resp);
        $this->assertNull(Local::find($local->id), 'Local should not exist in DB');
    }
}
