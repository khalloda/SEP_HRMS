<?php

namespace App\Exports;

use Generator;
use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GenericReportExport implements FromGenerator, WithHeadings, ShouldAutoSize, WithStrictNullComparison
{
    /**
     * @var callable
     */
    protected $generator;

    /**
     * @var array
     */
    protected array $headings;

    public function __construct(callable $generator, array $headings)
    {
        $this->generator = $generator;
        $this->headings = $headings;
    }

    public function generator(): Generator
    {
        $collection = ($this->generator)();

        if ($collection instanceof LazyCollection) {
            return $collection->getIterator();
        }

        if ($collection instanceof Generator) {
            return $collection;
        }

        if ($collection instanceof \Traversable) {
            return (function () use ($collection) {
                foreach ($collection as $row) {
                    yield $row;
                }
            })();
        }

        $array = is_array($collection) ? $collection : (array) $collection;

        return (function () use ($array) {
            foreach ($array as $row) {
                yield $row;
            }
        })();
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
