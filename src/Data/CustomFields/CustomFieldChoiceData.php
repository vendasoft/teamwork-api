<?php

namespace Teamwork\Data\CustomFields;

use Spatie\LaravelData\Data;

class CustomFieldChoiceData extends Data
{
    public function __construct(
        public string $value,
        public string $color,
    ) {}
}
