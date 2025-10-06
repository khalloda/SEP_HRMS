<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetSalaryHistoryExport implements WithMultipleSheets
{
    /** @var array<string,array{headings:array,rows:array}> */
    protected array $sheetsData;

    /**
     * @param array<string,array{headings:array,rows:array}> $sheetsData
     */
    public function __construct(array $sheetsData)
    {
        $this->sheetsData = $sheetsData;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheetsData as $title => $data) {
            $sheets[] = new class($title, $data['headings'], $data['rows']) extends ArrayExport implements \Maatwebsite\Excel\Concerns\WithTitle {
                protected string $title;
                public function __construct(string $title, array $headings, array $rows)
                {
                    parent::__construct($headings, $rows);
                    $this->title = $title;
                }
                public function title(): string { return $this->title; }
            };
        }
        return $sheets;
    }
}


