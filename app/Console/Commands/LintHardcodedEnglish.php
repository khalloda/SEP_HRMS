<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class LintHardcodedEnglish extends Command
{
    protected $signature = 'i18n:lint-hardcoded {--fix= : (reserved) not implemented}';
    protected $description = 'Detect hard-coded English strings in Blade/PHP files (excluding lang/vendor).';

    public function handle(): int
    {
        $patterns = [
            // naive patterns: words with spaces and letters that are not within __() or @lang
            '/>([^<]*[A-Za-z][^<]*)</',
            "@\{\{\s*'[^']*[A-Za-z][^']*'\s*\}\}@",
            '@\{\{\s*\"[^\"]*[A-Za-z][^\"]*\"\s*\}\}@',
        ];

        $finder = new Finder();
        $finder->files()
            ->in(base_path())
            ->exclude([
                'vendor',
                'storage',
                'node_modules',
                'tests',
                'resources/lang',
                // Ignore Laravel default scaffolds and error layouts
                'resources/views/errors',
            ])
            ->name('*.blade.php')
            ->name('*.php')
            // Also ignore the default Laravel welcome page
            ->notName('welcome.blade.php');

        $violations = [];
        foreach ($finder as $file) {
            $path = $file->getRealPath();
            $content = $file->getContents();

            // Skip files already in lang directories
            if (str_contains($path, DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR)) {
                continue;
            }

            // Skip if file appears to be mostly PHP array of translations
            if (preg_match('/return\s+\[/m', $content) && !str_contains($path, '.blade.php')) {
                continue;
            }

            foreach ($patterns as $regex) {
                if (preg_match_all($regex, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[1] ?? $matches[0] as $match) {
                        [$text, $offset] = $match;
                        // Heuristic: ignore placeholders, URLs, and blade directives
                        if (preg_match('/^\s*([@:{]|http|https|route\(|asset\(|\$)/', trim($text))) {
                            continue;
                        }
                        // If it already uses __() or @lang nearby, skip
                        $near = substr($content, max(0, $offset - 30), 60);
                        if (str_contains($near, '__(') || str_contains($near, '@lang')) {
                            continue;
                        }
                        $line = substr_count(substr($content, 0, $offset), "\n") + 1;
                        $violations[] = [
                            'file' => $path,
                            'line' => $line,
                            'text' => trim($text),
                        ];
                    }
                }
            }
        }

        if (empty($violations)) {
            $this->info('No hard-coded English strings detected.');
            return self::SUCCESS;
        }

        $this->error('Hard-coded English strings detected:');
        foreach ($violations as $v) {
            $this->line(sprintf('%s:%d  %s', str_replace(base_path() . DIRECTORY_SEPARATOR, '', $v['file']), $v['line'], $v['text']));
        }

        $this->newLine();
        $this->line('Guidance: Replace with translation keys using __(...) or @lang(...).');

        return self::FAILURE;
    }
}
