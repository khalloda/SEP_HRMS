<?php

namespace Tests\Unit\Payroll;

use App\Services\Payroll\ExpressionEvaluator;
use InvalidArgumentException;
use Tests\TestCase;

class ExpressionEvaluatorTest extends TestCase
{
    public function test_legacy_engine_evaluates_basic_expression(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(14.0, $evaluator->evaluate('2 + 3 * 4', 'legacy'));
    }

    public function test_new_engine_evaluates_expression_without_eval(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(14.0, $evaluator->evaluate('2 + 3 * 4', 'new'));
        $this->assertSame(6.5, $evaluator->evaluate('(10 - 3.5)', 'new'));
    }

    public function test_new_engine_handles_unary_minus(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(-5.0, $evaluator->evaluate('-5', 'new'));
        $this->assertSame(-5.0, $evaluator->evaluate('5 + -10', 'new'));
    }

    public function test_new_engine_throws_on_division_by_zero(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->expectException(InvalidArgumentException::class);
        $evaluator->evaluate('10 / 0', 'new');
    }
}
