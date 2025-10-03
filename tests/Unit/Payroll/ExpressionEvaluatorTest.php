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

    public function test_new_engine_supports_comparison_operators(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(1.0, $evaluator->evaluate('5 > 3', 'new'));
        $this->assertSame(0.0, $evaluator->evaluate('5 < 3', 'new'));
        $this->assertSame(1.0, $evaluator->evaluate('5 >= 5', 'new'));
        $this->assertSame(1.0, $evaluator->evaluate('5 == 5', 'new'));
        $this->assertSame(0.0, $evaluator->evaluate('5 != 5', 'new'));
    }

    public function test_new_engine_supports_logical_operators(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(1.0, $evaluator->evaluate('5 > 3 AND 2 < 4', 'new'));
        $this->assertSame(0.0, $evaluator->evaluate('5 > 3 AND 2 > 4', 'new'));
        $this->assertSame(1.0, $evaluator->evaluate('5 > 3 OR 2 > 4', 'new'));
        $this->assertSame(0.0, $evaluator->evaluate('5 < 3 OR 2 > 4', 'new'));
    }

    public function test_new_engine_preserves_arithmetic_precedence_with_comparisons(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->assertSame(1.0, $evaluator->evaluate('2 + 3 * 4 > 10', 'new'));
        $this->assertSame(0.0, $evaluator->evaluate('2 + 3 * 4 < 10', 'new'));
        $this->assertSame(1.0, $evaluator->evaluate('(2 + 3) * 4 == 20', 'new'));
    }

    public function test_new_engine_throws_on_division_by_zero(): void
    {
        $evaluator = new ExpressionEvaluator();

        $this->expectException(InvalidArgumentException::class);
        $evaluator->evaluate('10 / 0', 'new');
    }
}
