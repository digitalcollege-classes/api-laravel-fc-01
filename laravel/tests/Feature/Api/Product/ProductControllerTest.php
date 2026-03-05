<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Product;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase;
    protected $seed = true; // Isso força o 'php artisan db:seed' a cada refresh

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

    /** @test */
    public function um_produto_pode_ser_excluido()
    {
        $this ->markTestSkipped("skipado porque e um falso test");
        $response = $this->deleteJson("/api/produtos/2");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('produtos', ['id' => 2]);
    }

    public function test_deve_listar_apenas_produtos_na_lixeira_com_a_data_de_exclusao(): void
    {
        $this ->markTestSkipped("skipado porque e um falso test");

        // 1. Criar um produto ativo
        Produto::factory()->create(['nome' => 'Produto Ativo']);

        // 2. Criar um produto e deletar (Soft Delete)
        $produtoExcluido = Produto::factory()->create(['nome' => 'Produto na Lixeira']);
        $produtoExcluido->delete();

        // 3. Fazer a requisição para o endpoint de lixeira
        $response = $this->getJson('/api/produtos/trashed');

        // 4. Asserts
        $response->assertStatus(200)
            // Garante que o produto ativo NÃO está na lista
            ->assertJsonMissing(['nome' => 'Produto Ativo'])
            // Garante que o produto excluído ESTÁ na lista
            ->assertJsonFragment(['nome' => 'Produto na Lixeira'])
            // Garante que o campo 'deleted_at' ESTÁ presente no JSON
            ->assertJsonStructure([
                '*' => ['id', 'nome', 'deleted_at']
            ]);

        // Verifica se o campo deleted_at não veio nulo na resposta
        $this->assertNotNull($response->json()[0]['deleted_at']);
    }
}
