<?php

namespace App\Services\Payroll;

use InvalidArgumentException;

class ExpressionEvaluator
{
    public function evaluate(string $expression, ?string $engine = null): float
    {
        $engine ??= $this->resolveEngine();

        return match ($engine) {
            'new' => $this->evaluateWithParser($expression),
            default => $this->evaluateLegacy($expression),
        };
    }

    protected function resolveEngine(): string
    {
        if (function_exists('config')) {
            try {
                $configured = config('payroll.expression_engine');
                if ($configured) {
                    return $configured;
                }
            } catch (\Throwable $e) {
                // Config repository may not be bound (e.g. plain unit tests).
            }
        }

        return 'legacy';
    }

    protected function evaluateLegacy(string $expression): float
    {
        $cleanExpression = preg_replace('/[^0-9+\-*\/\(\)\.\s]/', '', $expression);

        if ($cleanExpression === null || $cleanExpression === '') {
            throw new InvalidArgumentException('Expression is empty after sanitisation.');
        }

        if (! $this->isValidMathExpression($cleanExpression)) {
            throw new InvalidArgumentException('Expression contains invalid characters or unbalanced parentheses.');
        }

        try {
            $result = eval("return {$cleanExpression};");
        } catch (\Throwable $e) {
            throw new InvalidArgumentException('Failed to evaluate expression.', 0, $e);
        }

        if (! is_numeric($result) || ! is_finite((float) $result)) {
            throw new InvalidArgumentException('Expression produced an invalid numeric result.');
        }

        return (float) $result;
    }

    protected function evaluateWithParser(string $expression): float
    {
        $tokens = $this->tokenise($expression);
        $rpn = $this->toReversePolish($tokens);

        return $this->evaluateReversePolish($rpn);
    }

    protected function isValidMathExpression(string $expression): bool
    {
        $balance = 0;
        $length = strlen($expression);

        for ($i = 0; $i < $length; $i++) {
            $char = $expression[$i];

            if ($char === '(') {
                $balance++;
            } elseif ($char === ')') {
                $balance--;

                if ($balance < 0) {
                    return false;
                }
            } elseif (! preg_match('/[0-9+\-*\/\.\s]/', $char)) {
                return false;
            }
        }

        return $balance === 0;
    }

    protected function tokenise(string $expression): array
    {
        $expression = trim($expression);

        if ($expression === '') {
            throw new InvalidArgumentException('Expression is empty.');
        }

        $tokens = [];
        $numberBuffer = '';
        $length = strlen($expression);
        $previousToken = null;

        for ($i = 0; $i < $length; $i++) {
            $char = $expression[$i];

            if (ctype_space($char)) {
                continue;
            }

            $twoCharOperator = $this->matchMultiCharOperator($expression, $i);

            if ($twoCharOperator !== null) {
                if ($numberBuffer !== '') {
                    $tokens[] = $numberBuffer;
                    $numberBuffer = '';
                }

                $tokens[] = $twoCharOperator;
                $previousToken = $twoCharOperator;
                $i += strlen($twoCharOperator) - 1;
                continue;
            }

            if ($this->isDigitOrDot($char)) {
                $numberBuffer .= $char;
                $previousToken = 'number';
                continue;
            }

            if ($char === '-' && ($previousToken === null || $previousToken === '(' || $this->isOperator($previousToken))) {
                $numberBuffer .= $char;
                $previousToken = 'number';
                continue;
            }

            if (ctype_alpha($char)) {
                if ($numberBuffer !== '') {
                    $tokens[] = $numberBuffer;
                    $numberBuffer = '';
                }

                $word = $this->consumeAlphaToken($expression, $i);

                if (! $this->isOperator($word)) {
                    throw new InvalidArgumentException('Invalid token encountered: ' . $word);
                }

                $tokens[] = $word;
                $previousToken = $word;
                $i += strlen($word) - 1;
                continue;
            }

            if ($numberBuffer !== '') {
                $tokens[] = $numberBuffer;
                $numberBuffer = '';
            }

            if ($this->isOperator($char) || $char === '(' || $char === ')') {
                $tokens[] = $char;
                $previousToken = $char;
                continue;
            }

            throw new InvalidArgumentException('Invalid character encountered: ' . $char);
        }

        if ($numberBuffer !== '') {
            $tokens[] = $numberBuffer;
        }

        return $tokens;
    }

    protected function toReversePolish(array $tokens): array
    {
        $output = [];
        $stack = [];

        foreach ($tokens as $token) {
            if ($this->isNumber($token)) {
                $output[] = (float) $token;
                continue;
            }

            if ($this->isOperator($token)) {
                while (! empty($stack) && $this->isOperator(end($stack)) &&
                    $this->precedence(end($stack)) >= $this->precedence($token)) {
                    $output[] = array_pop($stack);
                }

                $stack[] = $token;
                continue;
            }

            if ($token === '(') {
                $stack[] = $token;
                continue;
            }

            if ($token === ')') {
                while (! empty($stack) && end($stack) !== '(') {
                    $output[] = array_pop($stack);
                }

                if (empty($stack)) {
                    throw new InvalidArgumentException('Mismatched parentheses in expression.');
                }

                array_pop($stack);
            }
        }

        while (! empty($stack)) {
            $operator = array_pop($stack);

            if ($operator === '(' || $operator === ')') {
                throw new InvalidArgumentException('Mismatched parentheses in expression.');
            }

            $output[] = $operator;
        }

        return $output;
    }

    protected function evaluateReversePolish(array $tokens): float
    {
        $stack = [];

        foreach ($tokens as $token) {
            if (is_float($token) || is_int($token)) {
                $stack[] = $token;
                continue;
            }

            if (count($stack) < 2) {
                throw new InvalidArgumentException('Invalid expression structure.');
            }

            $right = array_pop($stack);
            $left = array_pop($stack);

            $stack[] = match ($token) {
                '+' => $left + $right,
                '-' => $left - $right,
                '*' => $left * $right,
                '/' => $this->divide($left, $right),
                '>' => $this->booleanResult($left > $right),
                '<' => $this->booleanResult($left < $right),
                '>=' => $this->booleanResult($left >= $right),
                '<=' => $this->booleanResult($left <= $right),
                '==' => $this->booleanResult(abs($left - $right) < 1e-12),
                '!=' => $this->booleanResult(abs($left - $right) >= 1e-12),
                'AND' => $this->booleanResult($this->toBoolean($left) && $this->toBoolean($right)),
                'OR' => $this->booleanResult($this->toBoolean($left) || $this->toBoolean($right)),
                default => throw new InvalidArgumentException('Unsupported operator: ' . $token),
            };
        }

        if (count($stack) !== 1) {
            throw new InvalidArgumentException('Invalid expression evaluation state.');
        }

        $result = array_pop($stack);

        if (! is_finite($result)) {
            throw new InvalidArgumentException('Expression produced a non-finite result.');
        }

        return $result;
    }

    protected function divide(float $left, float $right): float
    {
        if (abs($right) < 1e-12) {
            throw new InvalidArgumentException('Division by zero.');
        }

        return $left / $right;
    }

    protected function isNumber(string $token): bool
    {
        return is_numeric($token);
    }

    protected function isOperator(string $token): bool
    {
        return in_array($token, ['+', '-', '*', '/', '>', '<', '>=', '<=', '==', '!=', 'AND', 'OR'], true);
    }

    protected function precedence(string $operator): int
    {
        return match ($operator) {
            'OR' => 1,
            'AND' => 2,
            '>', '<', '>=', '<=', '==', '!=' => 3,
            '+', '-' => 4,
            '*', '/' => 5,
            default => 0,
        };
    }

    protected function isDigitOrDot(string $char): bool
    {
        return ($char >= '0' && $char <= '9') || $char === '.';
    }

    protected function matchMultiCharOperator(string $expression, int $position): ?string
    {
        $operators = ['>=', '<=', '==', '!='];

        foreach ($operators as $operator) {
            $length = strlen($operator);

            if (substr($expression, $position, $length) === $operator) {
                return $operator;
            }
        }

        return null;
    }

    protected function consumeAlphaToken(string $expression, int $startIndex): string
    {
        $length = strlen($expression);
        $buffer = '';

        for ($i = $startIndex; $i < $length; $i++) {
            $char = $expression[$i];

            if (! ctype_alpha($char)) {
                break;
            }

            $buffer .= $char;
        }

        return strtoupper($buffer);
    }

    protected function toBoolean(float $value): bool
    {
        return abs($value) > 1e-12;
    }

    protected function booleanResult(bool $value): float
    {
        return $value ? 1.0 : 0.0;
    }
}
