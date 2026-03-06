<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Product;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    #[Test]
    public function deve_listar_todos_os_produtos(): void
    {
        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');

        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('nome', $data[0]);
        $this->assertIsString($data[0]['nome']);
    }

    #[Test]
    public function nao_deve_listar_produtos_deletados(): void
    {
        $this->deleteJson('/api/produtos/2');

        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200);

        $ids = collect($response->json())->pluck('id');

        $this->assertFalse($ids->contains(2));
    }

    #[Test]
    public function um_produto_pode_ser_excluido(): void
    {
        $response = $this->deleteJson('/api/produtos/2');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('produtos', [
            'id' => 2
        ]);

        $produto = Produto::withTrashed()->find(2);

        $this->assertNotNull($produto->deleted_at);
    }

    #[Test]
    public function nao_deve_deletar_produto_inexistente(): void
    {
        $response = $this->deleteJson('/api/produtos/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function deve_listar_apenas_produtos_na_lixeira(): void
    {
        $this->deleteJson('/api/produtos/2');

        $response = $this->getJson('/api/produtos/trashed');

        $response->assertStatus(200)
            ->assertJsonCount(1);

        $data = $response->json();

        $this->assertArrayHasKey('deleted_at', $data[0]);
        $this->assertNotNull($data[0]['deleted_at']);
    }


    #[Test]
    public function lixeira_deve_vir_vazia_quando_nao_existirem_produtos_deletados(): void
    {
        $response = $this->getJson('/api/produtos/trashed');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }
}
