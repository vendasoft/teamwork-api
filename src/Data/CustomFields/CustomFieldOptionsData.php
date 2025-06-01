<?php

namespace Teamwork\Data\CustomFields;

use Spatie\LaravelData\Data;

class CustomFieldOptionsData extends Data
{
    public function __construct(
        public array $choices
    ) {}
}
