<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CollectionExport implements FromCollection, WithHeadings
{
    private Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function headings(): array
    {
        $data = $this->collection->first();
        return array_keys($data?->toArray() ?? []);
    }

    public function collection(): Collection
    {
        return $this->collection;
    }
}
