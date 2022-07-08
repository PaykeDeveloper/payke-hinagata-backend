<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResourceCollectionExport implements FromArray, WithHeadings
{
    use Exportable;

    private Request $request;
    private ResourceCollection $resourceCollection;

    public function __construct(Request $request, ResourceCollection $collection)
    {
        $this->request = $request;
        $this->resourceCollection = $collection;
    }

    public function headings(): array
    {
        /** @var Collection $collection */
        $collection = $this->resourceCollection->resource;
        $data = $collection->first();
        return array_keys($data?->toArray($this->request) ?? []);
    }

    public function array(): array
    {
        /** @phpstan-ignore-next-line */
        return $this->resourceCollection->toArray($this->request);
    }
}
