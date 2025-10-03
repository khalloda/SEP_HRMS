<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class PayslipCsvExport
{
    /**
     * Stream a CSV download for the provided headings and rows.
     */
    public static function download(array $headings, array $rows, string $filename): StreamedResponse
    {
        $callback = static function () use ($headings, $rows): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headings);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
