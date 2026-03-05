<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Product;

use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function testEndpointGetAllProducts(): void
    {
        $response = $this->get('/api/produtos');

        $data = $response->json();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertIsArray($data);
        $this->assertTrue(count($data) >= 1);

        $this->assertArrayHasKey('id', $data[0]);

        $this->assertIsString($data[0]['nome']);

//        $response->assertExactJson([
//            [
//                'id' => 1,
//                'nome' => 'Limao',
//            ]
//        ]);

    }
}
