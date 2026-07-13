<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\MalaheAlRahlatController;
use App\Repository\MalaheAlRahlatRepository;
use App\Entity\MalaheAlRahlat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;
use Symfony\Component\Paginator\PaginatorInterface;

class TestMalaheAlRahlat extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(MalaheAlRahlatRepository::class);
        $this->controller = new MalaheAlRahlatController($this->repository);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne(): void
    {
        $id = 1;
        $expectedResponse = ['data' => new MalaheAlRahlat()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getOne($id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test'];
        $expectedResponse = ['data' => new MalaheAlRahlat()];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $expectedResponse = ['data' => new MalaheAlRahlat()];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $this->controller->update($id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}