<?php

namespace Teamwork\Data\Project;

use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public object $activePages,
        public int $id,
        public string $name,
        public object $company,
        public bool $isBillable,
        public ?string $status,
        public ?string $subStatus,
        public ?string $startAt,
        public ?string $startDate,
        public ?string $updatedAt,
        public ?bool $allowNotifyAnyone,
        public ?string $announcement,
        public ?int $categoryId,
        public ?string $createdAt,
        public ?int $createdBy,
        public ?array $customFieldValueIds,
        public ?array $customFieldValues,
        public ?string $defaultPrivacy,
        public ?string $description,
        public ?bool $directFileUploadsEnabled,
        public ?string $endAt,
        public ?string $endDate,
        public ?int $financialBudgetId,
        public ?bool $harvestTimersEnabled,
        public ?int $ownedBy = null,

    ) {}
}
