<?php

namespace Data\Project;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
