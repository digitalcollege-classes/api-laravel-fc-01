<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Produto;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function list(): JsonResponse
    {
//        return response()->json('teste');
//        $data = json_decode(
//            file_get_contents(__DIR__ . '/mock/produtos.json')
//        );

        $data = Produto::all(); //SELECT * FROM produtos

        return new JsonResponse($data);
    }
}
