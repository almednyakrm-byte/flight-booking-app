<?php

namespace App\Tests\Controller;

use App\Controller\DiscountController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestDiscounts extends TestCase
{
    private $controller;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->controller = new DiscountController($this->pdo);
    }

    public function testGetDiscounts()
    {
        $expectedResponse = ['discounts' => []];
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM discounts')
            ->willReturn($this->createMock(PDOStatement::class));

        $response = $this->controller->getDiscounts();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateDiscount()
    {
        $data = ['name' => 'Test Discount', 'amount' => 10];
        $expectedResponse = ['message' => 'Discount created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO discounts (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $response = $this->controller->createDiscount($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateDiscount()
    {
        $data = ['id' => 1, 'name' => 'Updated Discount', 'amount' => 20];
        $expectedResponse = ['message' => 'Discount updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE discounts SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $response = $this->controller->updateDiscount($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteDiscount()
    {
        $id = 1;
        $expectedResponse = ['message' => 'Discount deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM discounts WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $response = $this->controller->deleteDiscount($id);
        $this->assertEquals($expectedResponse, $response);
    }
}