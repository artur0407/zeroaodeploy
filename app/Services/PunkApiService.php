<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PunkApiService
{
    public function getBeers(
        ?string $beer_name = null,
        ?string $food = null,
        ?string $malt = null,
        ?int $ibu_gt = null
    )
    {
        // pega os parâmetros do método e transforma em um array onde a chave do item é o nome do parâmetro
        // array_filter remove todos os valores nulos
        $params = array_filter(get_defined_vars());

        //função punkapi() criada no provider
        return Http::punkapi()
        ->get('/beers', $params)
        ->throw()
        ->json();

        // return Http::punkapi()
        //     ->get('/beers', [
        //         'beer_name' => $beer_name,
        //         'food' => $food,
        //         'malt' => $malt,
        //         'ibu_gt' => $ibu_gt
        //     ])
        //     ->throw()
        //     ->json();
    }
}
