<?php

namespace App\Exceptions;

use RuntimeException;

class PayrollDependencyCycleException extends RuntimeException
{
    /**
     * @var array<int, string>
     */
    protected array $cycle;

    protected ?int $structureId;

    public function __construct(array $cycle, ?int $structureId = null)
    {
        $message = 'Circular payroll component dependency detected: ' . implode(' -> ', $cycle);

        parent::__construct($message);

        $this->cycle = $cycle;
        $this->structureId = $structureId;
    }

    /**
     * Get the component codes that form the cycle.
     *
     * @return array<int, string>
     */
    public function getCycle(): array
    {
        return $this->cycle;
    }

    public function getStructureId(): ?int
    {
        return $this->structureId;
    }
}
