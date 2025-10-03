<?php

namespace App\Services\Payroll;

use InvalidArgumentException;

class ExpressionFunctionRegistry
{
    /**
     * @var array<string, callable>
     */
    protected array $functions = [];

    public function __construct()
    {
        $this->register('IF', function (array $arguments) {
            if (count($arguments) !== 3) {
                throw new InvalidArgumentException('IF function expects exactly three arguments.');
            }

            [$condition, $trueValue, $falseValue] = $arguments;

            return abs($condition) > 1e-12 ? $trueValue : $falseValue;
        });
    }

    public function register(string $name, callable $handler): void
    {
        $this->functions[strtoupper($name)] = $handler;
    }

    public function has(string $name): bool
    {
        return array_key_exists(strtoupper($name), $this->functions);
    }

    public function invoke(string $name, array $arguments): float
    {
        $key = strtoupper($name);

        if (! $this->has($key)) {
            throw new InvalidArgumentException('Unsupported function: ' . $name);
        }

        $result = $this->functions[$key]($arguments);

        if (! is_numeric($result)) {
            throw new InvalidArgumentException('Function must return a numeric value.');
        }

        return (float) $result;
    }
}
 