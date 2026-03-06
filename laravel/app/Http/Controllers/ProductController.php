<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Produto;
use App\Service\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    public function list(): JsonResponse
    {
        return new JsonResponse(
            $this->productService->findAll()
        );
    }

    public function lixeira(): JsonResponse
    {
        return response()->json(
            $this->productService->findAllTrashed()
        );
    }

    public function destroy(Produto $produto): JsonResponse
    {
        $this->productService->remove($produto);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}


