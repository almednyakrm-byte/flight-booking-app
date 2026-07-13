<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\PDO;

class Testدوائر_رحلات extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = $this->mockPDO();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->pdo = null;
    }

    private function mockPDO()
    {
        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo->method('query')
            ->willReturn($this->getMockBuilder(\PDOStatement::class)
                ->disableOriginalConstructor()
                ->getMock());

        return $pdo;
    }

    public function test_get_دوائر_رحلات()
    {
        $response = $this->get('/api/دوائر_رحلات');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_post_دوائر_رحلات()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $response = $this->post('/api/دوائر_رحلات', $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_put_دوائر_رحلات()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $response = $this->put('/api/دوائر_رحلات/1', $data);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_دوائر_رحلات()
    {
        $response = $this->delete('/api/دوائر_رحلات/1');
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}


This test file uses PHPUnit to verify CRUD API operations on the 'دوائر_رحلات' module. It includes tests for GET, POST, PUT, and DELETE requests. The `mockPDO` method is used to mock the PDO statements for the tests.