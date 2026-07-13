<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Response;

class Testرحلات extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetرحلات()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM رحلات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $rahlatController = new RahlatController($this->pdo);
        $response = $rahlatController->getRahlat($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostرحلات()
    {
        $data = ['name' => 'Test Rahlat', 'description' => 'Test Description'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO رحلات (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $rahlatController = new RahlatController($this->pdo);
        $response = $rahlatController->postRahlat($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutرحلات()
    {
        $data = ['name' => 'Test Rahlat', 'description' => 'Test Description'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE رحلات SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $rahlatController = new RahlatController($this->pdo);
        $response = $rahlatController->putRahlat($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteرحلات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM رحلات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $rahlatController = new RahlatController($this->pdo);
        $response = $rahlatController->deleteRahlat($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}

class RahlatController
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRahlat(ServerRequestInterface $request, ResponseInterface $response)
    {
        $stmt = $this->pdo->query('SELECT * FROM رحلات');
        $rahlat = $stmt->fetchAll();

        $response->getBody()->write(json_encode($rahlat));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function postRahlat(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('INSERT INTO رحلات (name, description) VALUES (:name, :description)');
        $stmt->execute($data);

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function putRahlat(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('UPDATE رحلات SET name = :name, description = :description WHERE id = :id');
        $stmt->execute($data);

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function deleteRahlat(ServerRequestInterface $request, ResponseInterface $response)
    {
        $stmt = $this->pdo->prepare('DELETE FROM رحلات WHERE id = :id');
        $stmt->execute(['id' => 1]);

        return $response->withStatus(204);
    }
}