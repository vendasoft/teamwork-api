<?php

namespace Teamwork\Interfaces;

use Teamwork\Data\CustomFields\CustomFieldData;
use Teamwork\Data\Project\ProjectData;
use Teamwork\Exceptions\TeamworkApiException;

interface TeamworkApiInterface
{
    /**
     * @throws TeamworkApiException
     */
    public function getUsers(): array;

    /**
     * @throws TeamworkApiException
     */
    public function removeTask(int $taskId): void;

    /**
     * @throws TeamworkApiException
     */
    public function updateTaskById(int $taskId, array $taskData): void;

    /**
     * @throws TeamworkApiException
     */
    public function updateTaskByIdWithV1(int $taskId, array $taskData): void;

    /**
     * @throws TeamworkApiException
     */
    public function updateTasksStage(int $taskId, int $workflowId, int $stageId): void;

    /**
     * @throws TeamworkApiException
     */
    public function getTimeEntriesByTaskId(int $taskId): array;

    /**
     * @throws TeamworkApiException
     */
    public function getTasks(?array $query = []): array;

    /**
     * @throws TeamworkApiException
     */
    public function getAllSubtaskByTaskId(int $taskId, ?array $query = []): array;

    /**
     * @throws TeamworkApiException
     */
    public function createRecurringTask(int $taskId, \Carbon\Carbon $dueDate): object;

    /**
     * @throws TeamworkApiException
     */
    public function getTimeEntries(?array $query = []): array;

    /**
     * @throws TeamworkApiException
     */
    public function createCustomField(CustomFieldData $customField): CustomFieldData;

    /**
     * @throws TeamworkApiException
     */
    public function updateCustomField(CustomFieldData $customField): CustomFieldData;

    /**
     * @return CustomFieldData[]
     */
    public function getCustomFields(array $query = []): array;

    /**
     * @throws TeamworkApiException
     */
    public function upsertCustomFieldByName(CustomFieldData $customField): CustomFieldData;

    public function createProjectsCustomField(int $projectId, CustomFieldData $customField): CustomFieldData;

    /**
     * @throws TeamworkApiException
     */
    public function upsertProjectsCustomFieldByName(int $projectId, CustomFieldData $customField): CustomFieldData;

    /**
     * @return ProjectData[]
     *
     * @throws TeamworkApiException
     */
    public function getCompanies(array $query = []): array;

    /**
     * @return ProjectData[]
     *
     * @throws TeamworkApiException
     */
    public function getProjects(array $query = []): array;

    /**
     * @throws TeamworkApiException
     */
    public function getProjectById(int $projectId): ProjectData;

    /**
     * @throws TeamworkApiException
     */
    public function getTaskById(int $taskId): object;

    /**
     * @throws TeamworkApiException
     */
    public function getTaskCustomFieldValue(int $taskId): array;
}