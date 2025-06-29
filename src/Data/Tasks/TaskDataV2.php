<?php

namespace Teamwork\Data\Tasks;

use Spatie\LaravelData\Data;

class TaskDataV2 extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $priority = null,
        public ?string $status = "new",
        public ?int $parentTaskId = null,
        public ?bool $isArchived = false,
        public ?string $description,
        public ?string $descriptionContentType,
        public ?bool $canViewEstTime = true,
        public ?object $updatedBy = null,
        public ?object $createdBy = null,
        public ?string $dateCreated,
        public ?string $dateChanged,
        public ?string $dateLastModified,
        public ?bool $hasFollowers = false,
        public ?bool $hasLoggedTime = false,
        public ?bool $hasProofs = false,
        public ?bool $hasReminders = false,
        public ?bool $hasRemindersForUser = false,
        public ?bool $hasRelativeReminders = false,
        public ?bool $hasTemplateReminders = false,
        public ?bool $hasTickets = false,
        public ?bool $isPrivate = false,
        public ?int $installationId,
        public ?bool $privacyIsInherited = false,
        public int $lockdownId,
        public int $numMinutesLogged,
        public int $numBillableMinutesLogged,
        public int $numTotalMinutesLogged,
        public int $numActiveSubTasks,
        public int $numAttachments,
        public int $numComments,
        public int $numCommentsRead,
        public int $numCompletedSubTasks,
        public int $numDependencies,
        public int $numEstMins,
        public int $numPredecessors,
        public ?int $position,
        public int $projectId,
        public ?object $recurring = null,
        public ?string $startDate,
        public ?array $workflowStages = [],
        public ?string $dueDate,
        public ?bool $dueDateFromMilestone = false,
        public ?int $taskListId,
        public int $progress,
        public ?array $crmDealIds = null,
        public ?bool $followingChanges = false,
        public ?bool $followingComments = false,
        public ?bool $followingComplete = false,
        public ?string $changeFollowerIds = "",
        public ?string $commentFollowerIds = "",
        public ?string $completeFollowerIds = "",
        public ?array $teamsFollowingChanges = null,
        public ?array $teamsFollowingComments = null,
        public ?array $teamsFollowingComplete = null,
        public ?array $companiesFollowingChanges = null,
        public ?array $companiesFollowingComments = null,
        public ?array $companiesFollowingComplete = null,
        public ?int $order,
        public ?bool $canComplete = true,
        public ?bool $canEdit = true,
        public ?bool $canLogTime = true,
        public ?bool $canAddSubtasks = true,
        public ?bool $placeholder = false,
        public ?int $DLM,
    ) {}
}
