<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Produto;

class ProductService
{
    public function findAll(): iterable
    {
        //gerar logs

        return Produto::all();
    }

    public function findAllTrashed(): iterable
    {
        $products = Produto::onlyTrashed()->get();

        $products->makeVisible('deleted_at');

        return $products;
    }

    public function remove(Produto $produto): void
    {
        $produto->delete();
    }
}
