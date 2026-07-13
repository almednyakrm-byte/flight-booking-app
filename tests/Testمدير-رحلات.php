<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ManagerTripsController;
use App\Repository\ManagerTripsRepository;
use App\Service\ManagerTripsService;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

class Testمدير_رحلات extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ManagerTripsRepository::class);
        $this->service = $this->createMock(ManagerTripsService::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);

        $this->controller = new ManagerTripsController($this->repository, $this->service);
    }

    public function testGetAllTrips()
    {
        $this->repository->expects($this->once())
            ->method('getAllTrips')
            ->willReturn([
                ['id' => 1, 'name' => 'Trip 1'],
                ['id' => 2, 'name' => 'Trip 2'],
            ]);

        $this->response->expects($this->once())
            ->method('withJson')
            ->willReturn($this->response);

        $this->controller->getAllTrips($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testGetTripById()
    {
        $this->repository->expects($this->once())
            ->method('getTripById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Trip 1']);

        $this->response->expects($this->once())
            ->method('withJson')
            ->willReturn($this->response);

        $this->controller->getTripById($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testCreateTrip()
    {
        $this->service->expects($this->once())
            ->method('createTrip')
            ->with(['name' => 'Trip 1'])
            ->willReturn(['id' => 1, 'name' => 'Trip 1']);

        $this->response->expects($this->once())
            ->method('withJson')
            ->willReturn($this->response);

        $this->controller->createTrip($this->request, $this->response);

        $this->assertEquals(201, $this->response->getStatusCode());
    }

    public function testUpdateTrip()
    {
        $this->service->expects($this->once())
            ->method('updateTrip')
            ->with(1, ['name' => 'Trip 1'])
            ->willReturn(['id' => 1, 'name' => 'Trip 1']);

        $this->response->expects($this->once())
            ->method('withJson')
            ->willReturn($this->response);

        $this->controller->updateTrip($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testDeleteTrip()
    {
        $this->repository->expects($this->once())
            ->method('deleteTrip')
            ->with(1)
            ->willReturn(true);

        $this->response->expects($this->once())
            ->method('withJson')
            ->willReturn($this->response);

        $this->controller->deleteTrip($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
    }
}



// ManagerTripsController.php

namespace App\Controller;

use App\Repository\ManagerTripsRepository;
use App\Service\ManagerTripsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ManagerTripsController
{
    private $repository;
    private $service;

    public function __construct(ManagerTripsRepository $repository, ManagerTripsService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getAllTrips(ServerRequestInterface $request, ResponseInterface $response)
    {
        $trips = $this->repository->getAllTrips();
        return $response->withJson($trips);
    }

    public function getTripById(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = (int) $request->getAttribute('id');
        $trip = $this->repository->getTripById($id);
        return $response->withJson($trip);
    }

    public function createTrip(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $trip = $this->service->createTrip($data);
        return $response->withJson($trip, 201);
    }

    public function updateTrip(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = (int) $request->getAttribute('id');
        $data = $request->getParsedBody();
        $trip = $this->service->updateTrip($id, $data);
        return $response->withJson($trip);
    }

    public function deleteTrip(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = (int) $request->getAttribute('id');
        $this->repository->deleteTrip($id);
        return $response->withJson(['message' => 'Trip deleted successfully']);
    }
}