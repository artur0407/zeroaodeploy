<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Mail\ExportEmail;
use App\Models\Export;
use App\Services\PunkApiService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controller usar um serviço externo
 */
class BeerController extends Controller
{
    public function __construct()
    {

    }

    // laravel procura classe PunkApiService e já tenta criar uma instância dela
    public function index(BeerRequest $request, PunkApiService $service)
    {
        //return $service->getBeers(food:'cheese'); com parâmetros
        $beers = $service->getBeers(... $request->validated()); //... spread operator faz um match entre a chave do array e o nome dos argumentos

        return Inertia::render('Beers', [
            'beers' => $beers
        ]);
    }

    public function export(BeerRequest $request, PunkApiService $service)
    {
        $filename = "cervejas-encontradas-" . now()->format("Y-m-d - H:i") . ".xlsx";

        /**
         * withChain => pra rodar os jobs na ordem que estão escritos
         * primeiro exporta excel
         * segundo envia email
         * terceiro salva no banco
         **/
        ExportJob::withChain([
            new SendExportEmailJob($filename),
            new StoreExportDataJob(Auth::user(), $filename)
        ])->dispatch($request->validated(), $filename);

        return 'Relatório criado';
    }
}
