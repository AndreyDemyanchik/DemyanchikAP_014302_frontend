<?php

declare(strict_types=1);

namespace App\Contracts;

interface AggregatorsInterface
{
    /**
     * @return array
     */
    public function aggregate(): array;
}
