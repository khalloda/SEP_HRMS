<?php

namespace Tests\Unit\Payroll;

use App\Exceptions\PayrollDependencyCycleException;
use App\Models\SalaryComponent;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureComponent;
use App\Services\PayrollCalculationService;
use Tests\TestCase;

class FormulaGraphTest extends TestCase
{
    public function test_detects_circular_dependencies(): void
    {
        $service = app(PayrollCalculationService::class);

        $structure = new SalaryStructure(['id' => 42]);

        $base = new SalaryStructureComponent(['depends_on' => ['BONUS']]);
        $base->setRelation('component', new SalaryComponent(['code' => 'BASE']));

        $bonus = new SalaryStructureComponent(['depends_on' => ['BASE']]);
        $bonus->setRelation('component', new SalaryComponent(['code' => 'BONUS']));

        $structure->setRelation('structureComponents', collect([$base, $bonus]));

        $this->expectException(PayrollDependencyCycleException::class);
        $this->expectExceptionMessage('Circular payroll component dependency detected: BASE -> BONUS -> BASE');

        $service->assertNoCircularDependencies($structure);
    }

    public function test_allows_acyclic_dependencies(): void
    {
        $service = app(PayrollCalculationService::class);

        $structure = new SalaryStructure(['id' => 77]);

        $base = new SalaryStructureComponent(['depends_on' => ['ALLOW']]);
        $base->setRelation('component', new SalaryComponent(['code' => 'BASE']));

        $allowance = new SalaryStructureComponent(['depends_on' => ['TAX']]);
        $allowance->setRelation('component', new SalaryComponent(['code' => 'ALLOW']));

        $tax = new SalaryStructureComponent(['depends_on' => []]);
        $tax->setRelation('component', new SalaryComponent(['code' => 'TAX']));

        $structure->setRelation('structureComponents', collect([$base, $allowance, $tax]));

        $service->assertNoCircularDependencies($structure);

        $this->assertTrue(true);
    }

    public function test_ignores_dependencies_not_in_structure(): void
    {
        $service = app(PayrollCalculationService::class);

        $structure = new SalaryStructure(['id' => 88]);

        $bonus = new SalaryStructureComponent(['depends_on' => ['NON_EXISTENT']]);
        $bonus->setRelation('component', new SalaryComponent(['code' => 'BONUS']));

        $base = new SalaryStructureComponent(['depends_on' => []]);
        $base->setRelation('component', new SalaryComponent(['code' => 'BASE']));

        $structure->setRelation('structureComponents', collect([$bonus, $base]));

        $service->assertNoCircularDependencies($structure);

        $this->assertTrue(true);
    }
}
