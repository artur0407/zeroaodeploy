<?php

namespace App\Jobs;

use App\Exports\BeerExport;
use App\Services\PunkApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $data,
        protected string $filename,
        protected PunkApiService $service = new PunkApiService)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $beers = $this->service->getBeers(... $this->data);

        $filteredBeers = array_map(function($value) {
            return collect($value)
                ->only(['name', 'tagline', 'first_brewed', 'description'])
                ->toArray();
        }, $beers);

        Excel::store(
            new BeerExport($filteredBeers),
            $this->filename,
            's3');
    }
}