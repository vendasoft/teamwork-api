<?php

namespace Teamwork\Data\Project;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $addressOne,
        public ?string $addressTwo,
        public ?string $city,
        public ?string $companyNameUrl,
        public ?string $countryCode,
        public ?string $createdAt,
        public ?string $emailOne,
        public ?string $emailTwo,
        public ?string $emailThree,
        public ?string $state,
        public ?string $updatedAt,
    ) {}
}
