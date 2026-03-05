<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function list(): JsonResponse
    {
        $data = Produto::all(); //SELECT * FROM produtos

        return new JsonResponse($data);
    }

    public function lixeira(): JsonResponse
    {
        $produtosExcluidos = Produto::onlyTrashed()->get();

        $produtosExcluidos->makeVisible('deleted_at');

        return response()->json($produtosExcluidos);
    }

    public function destroy(Produto $produto): JsonResponse
    {
        try {
            $produto->delete();

            return response()->json([
                'message' => 'Produto excluído com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir produto.',
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}


