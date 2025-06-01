<?php

namespace Teamwork\Data\CustomFields;

use Spatie\LaravelData\Data;

class CustomFieldData extends Data
{
    public function __construct(
        public ?string $name = null,            // např. 'Total Cost'
        public ?string $description = null,     // např. 'This is a sample custom field.'
        public ?string $type = null,            // např. 'dropdown'
        public ?int $id = null,         // Je volitelne, protoze pro create neni potreba prenaset
        public ?CustomFieldOptionsData $options = null,            // např. 'dropdown'
        public ?string $currencyCode = null,    // např. 'USD'
        public ?string $entity = null,          // musí být jedna z hodnot ['project', 'task', 'company', 'cr-project', 'cr-task']
        public ?string $formula = null,         // např. 'price * quantity'
        public ?string $groupId = null,         // např. 101
        public ?string $isPrivate = null,       // např. true nebo false
        public ?string $privacy = null,         // specifikace o skupině uživatelů, příklad není uveden
        public ?int $projectId = null,          // např. 1234
        public ?bool $required = null,          // např. true nebo false
        public ?int $unitId = null,             // např. 987
        public ?array $visibilities = null      // např. ['admin', 'user']
    ) {}
}
