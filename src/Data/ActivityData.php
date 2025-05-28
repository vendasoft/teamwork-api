<?php

namespace Data;

use Spatie\LaravelData\Data;

class CustomFieldData extends Data
{
    public function __construct(
        public string $currencyCode,
        public string $description,
        public string $entity,
        public string $formula,
        public int $groupId,
        public bool $isPrivate,
        public string $name,
        public bool $required,
        public string $type,
        public int $unitId,
        public array $visibilities,
    ) {}
}
