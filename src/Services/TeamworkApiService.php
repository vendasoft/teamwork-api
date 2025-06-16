<?php

namespace Teamwork\Services;

use Carbon\Carbon;
use Teamwork\Data\CustomFields\CustomFieldData;
use Teamwork\Data\People\PeopleDetailData;
use Teamwork\Data\Project\CompanyData;
use Teamwork\Data\Project\ProjectData;
use Teamwork\Data\Tasks\TaskData;
use Teamwork\Exceptions\TeamworkApiException;
use Illuminate\Support\Facades\Cache;

class TeamworkApiService extends BaseHttpService
{
    /**
     * @throws TeamworkApiException
     */
    public function getUsers(): array
    {
        $allUsers = [];
        $query['pageSize'] = 300;
        $query['page'] = 1;
        do {
            $response = $this->get('/projects/api/v3/people', query: $query);
            $this->handleError($response, '/projects/api/v3/people');
            $allUsers = array_merge($allUsers, $response->people);
            $query['page'] += 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return $allUsers;
    }

    /**
     * @throws TeamworkApiException
     */
    public function getUserDetail(int $peopleId): PeopleDetailData
    {
        $response = $this->get(
            "/projects/api/v2/people/{$peopleId}.json",
            query: [
                'fullprofile' => '1',
            ]
        );
        if($response->STATUS !== "OK"){
            throw new TeamworkApiException($response->STATUS);
        }
        return PeopleDetailData::from($response->person);
    }

    /**
     * @param int $taskId
     * @return void
     * @throws TeamworkApiException
     */
    public function removeTask(int $taskId): void
    {
        $response = $this->delete("/projects/api/v3/tasks/{$taskId}.json");
        $this->handleError($response);
    }

    /**
     * @param int $taskId
     * @param array $taskData
     * @return void
     * @throws TeamworkApiException
     */
    public function updateTaskById(int $taskId, array $taskData): void
    {
        $response = $this->patch("/projects/api/v3/tasks/{$taskId}.json", [
            'task' => $taskData,
        ]);

        $this->handleError($response);
    }

    /**
     * @param int $taskId
     * @param array $taskData
     * @return void
     * @throws TeamworkApiException
     */
    public function updateTaskByIdWithV1(int $taskId, array $taskData): void
    {
        $response = $this->put("/tasks/{$taskId}.json", [
            'todo-item' => $taskData,
        ]);

        if ($response->STATUS !== 'OK') {
            throw new TeamworkApiException('Teamwork API ERROR: '.json_encode($response));
        }
    }

    /**
     * @param int $taskId
     * @param int $workflowId
     * @param int $stageId
     * @return void
     * @throws TeamworkApiException
     */
    public function updateTasksStage(int $taskId, int $workflowId, int $stageId): void
    {
        $response = $this->patch("/projects/api/v3/tasks/{$taskId}/workflows/{$workflowId}.json", [
            'stageId' => $stageId,
            'workflowId' => $workflowId,
            'positionAfterTask' => 0,
        ]);

        $this->handleError(
            $response,
            "/projects/api/v3/tasks/{$taskId}/workflows/{$workflowId}.json",
            true
        );
    }

    /**
     * @param int $taskId
     * @return array
     * @throws TeamworkApiException
     */
    public function getTimeEntriesByTaskId(int $taskId): array
    {
        $allTimeEntries = [];
        $query = [
            'pageSize' => 500,
            'page' => 1,
        ];

        do {
            $response = $this->get("/projects/api/v3/tasks/{$taskId}/time.json", query: $query);
            $this->handleError($response, "/projects/api/v3/tasks/{$taskId}/time.json");
            $allTimeEntries = array_merge($allTimeEntries, $response->timelogs);
            $query['page'] = $query['page'] + 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return $allTimeEntries;
    }

    /**
     * @param array|null $query
     * @return TaskData[]
     * @throws TeamworkApiException
     */
    public function getTasks(?array $query = []): array
    {
        $query = array_merge($query, [
            'pageSize' => 500,
            'page' => 1,
        ]);

        $allTasks = [];

        do {
            $response = $this->get('/projects/api/v3/tasks.json', query: $query);
            $this->handleError($response, '/projects/api/v3/tasks.json');
            $allTasks = array_merge($allTasks, $response->tasks);
            $query['page'] = $query['page'] + 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return TaskData::collect($allTasks);
    }

    /**
     * @param int $taskId
     * @param array|null $query
     * @return array
     * @throws TeamworkApiException
     */
    public function getAllSubtaskByTaskId(int $taskId, ?array $query = []): array
    {
        $query = array_merge($query, [
            'pageSize' => 500,
            'page' => 1,
        ]);

        $allSubtasks = [];

        do {
            $response = $this->get("/projects/api/v3/tasks/{$taskId}/subtasks.json", query: $query);
            $this->handleError($response, "/projects/api/v3/tasks/{$taskId}/subtasks.json");
            $allSubtasks = array_merge($allSubtasks, $response->tasks);
            $query['page'] = $query['page'] + 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return $allSubtasks;
    }

    /**
     * @param int $taskId
     * @param Carbon $dueDate
     * @return object|mixed
     * @throws TeamworkApiException
     */
    public function createRecurringTask(int $taskId, Carbon $dueDate): object
    {
        $lastDayOfMonth = $dueDate->format('Ymd');

        $response = $this->post("/tasks/{$taskId}/recurring.json", [
            'dueDate' => $lastDayOfMonth,
        ]);

        if (is_null($response)) {
            throw new TeamworkApiException("Response from TeamworkAPI is null. Endpoint /tasks/{$taskId}/recurring.json");
        }
        $stringifyObject = json_encode(get_object_vars($response));
        if (! isset($response->taskId)) {
            throw new TeamworkApiException("Error in teamwork response: {$stringifyObject}");
        }

        return $response;
    }

    /**
     * @param array|null $query
     * @return array
     * @throws TeamworkApiException
     */
    public function getTimeEntries(?array $query = []): array
    {
        $query['pageSize'] = 300;
        $query['page'] = 1;
        $allTimeEntries = [];
        do {
            $response = $this->get('/projects/api/v3/time.json', query: $query);
            $this->handleError($response, '/projects/api/v3/time.json');
            $allTimeEntries = array_merge($allTimeEntries, $response->timelogs);
            $query['page'] = $query['page'] + 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return $allTimeEntries;
    }

    /**
     * @param CustomFieldData $customField
     * @return CustomFieldData
     * @throws TeamworkApiException
     */
    public function createCustomField(CustomFieldData $customField): CustomFieldData
    {
        $customFieldArray = array_filter($customField->toArray(), function ($value) {
            return ! is_null($value);
        });

        $data = [
            'customField' => $customFieldArray,
        ];

        $response = $this->post('/projects/api/v3/customfields.json', $data);
        $this->handleError($response, '/projects/api/v3/customfields.json');

        return CustomFieldData::from($response->customfield);
    }

    /**
     * @param object|null $response
     * @param string|null $url
     * @param bool|null $nullResponse
     * @return void
     * @throws TeamworkApiException
     */
    protected function handleError(?object $response, ?string $url = '', ?bool $nullResponse = false): void
    {
        if (is_null($response)) {
            if ($nullResponse) {
                return;
            }
            throw new TeamworkApiException("Response from TeamworkAPI is null. Endpoint {$url}");
        }

        if (! empty($response->errors)) {
            $message = '';
            foreach ($response->errors as $error) {
                if ($message !== '') {
                    $message = "{$message},";
                }
                $message = "{$message} {$error->title}:{$error->detail}";
            }
            throw new TeamworkApiException($message);
        }
    }

    /**
     * @param CustomFieldData $customField
     * @return CustomFieldData
     * @throws TeamworkApiException
     */
    public function updateCustomField(CustomFieldData $customField): CustomFieldData
    {
        $id = $customField->id;
        if (! $id) {
            throw new TeamworkApiException('ID is required for update custom field.');
        }
        $customField->id = null;

        $data = [
            'customField' => $customField->toArray(),
        ];

        $response = $this->patch("/projects/api/v3/customfields/$id.json", $data);

        $this->handleError($response, "/projects/api/v3/customfields/$id.json", true);

        return CustomFieldData::from($response->customfield);
    }

    /**
     * @param array $query
     * @return array
     */
    public function getCustomFields(array $query = []): array
    {
        $queryHash = md5(json_encode($query));
        $cacheKey = "teamwork_custom_fields_{$queryHash}";

        return Cache::remember($cacheKey, 180, function () use ($query) {
            $allCustomFields = [];
            $query['pageSize'] = 300;
            $query['page'] = 1;

            do {
                $response = $this->get('/projects/api/v3/customfields.json', query: $query);
                $this->handleError($response, '/projects/api/v3/customfields.json');
                $allCustomFields = array_merge($allCustomFields, $response->customfields);
                $query['page'] += 1;
                $hasMore = $response->meta->page->hasMore;
            } while ($hasMore);

            return CustomFieldData::collect($allCustomFields);
        });
    }

    /**
     * @param CustomFieldData $customField
     * @return CustomFieldData
     * @throws TeamworkApiException
     */
    public function upsertCustomFieldByName(CustomFieldData $customField): CustomFieldData
    {
        $customFieldCollection = collect($this->getCustomFields());
        if (! $customField->name) {
            throw new TeamworkApiException('Name is required for upsert custom field by name.');
        }
        $currentCustomField = $customFieldCollection
            ->where('name', $customField->name)
            ->first();
        if (! $currentCustomField) {
            return $this->createCustomField($customField);
        }
        $customField->id = $currentCustomField->id;

        return $this->updateCustomField($customField);
    }

    public function createProjectsCustomField(int $projectId, CustomFieldData $customField): CustomFieldData
    {
        $data = [
            'customField' => $customField->toArray(true),
        ];
        $response = $this->post('/projects/api/v3/customfields.json', $data);

        return CustomFieldData::from($response->customfieldProject);
    }

    /**
     * @throws TeamworkApiException
     */
    public function upsertProjectsCustomFieldByName(int $projectId, CustomFieldData $customField): CustomFieldData
    {
        $customFieldCollection = collect($this->getCustomFields(['projectId' => $projectId]));
        if (! $customField->name) {
            throw new TeamworkApiException('Name is required for upsert custom field by name.');
        }
        $currentCustomField = $customFieldCollection
            ->where('name', $customField->name)
            ->first();

        if (! $currentCustomField) {
            return $this->createCustomField($customField);
        }
        $customField->id = $currentCustomField->id;

        return $this->updateCustomField($customField);
    }

    /**
     * @return ProjectData[]
     *
     * @throws TeamworkApiException
     */
    public function getCompanies(array $query = []): array
    {
        $allCompanies = [];
        $query['pageSize'] = 300;
        $query['page'] = 1;
        do {
            $response = $this->get('/projects/api/v3/companies', query: $query);
            $this->handleError($response, '/projects/api/v3/companies');
            $allCompanies = array_merge($allCompanies, $response->companies);
            $query['page'] += 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return CompanyData::collect($allCompanies);
    }

    /**
     * @param array $query
     * @return ProjectData[]
     * @throws TeamworkApiException
     */
    public function getProjects(array $query = []): array
    {
        $allProjects = [];

        $query['pageSize'] = 300;
        $query['page'] = 1;
        do {
            $response = $this->get('/projects/api/v3/projects', query: $query);
            $this->handleError($response, '/projects/api/v3/projects');
            $allProjects = array_merge($allProjects, $response->projects);
            $query['page'] += 1;
            $hasMore = $response->meta->page->hasMore;
        } while ($hasMore);

        return ProjectData::collect($allProjects);
    }

    /**
     * @param int $projectId
     * @return ProjectData
     * @throws TeamworkApiException
     */
    public function getProjectById(int $projectId): ProjectData
    {
        $response = $this->get("projects/api/v3/projects/{$projectId}.json");
        $this->handleError($response);

        return ProjectData::from($response->project);
    }

    /**
     * @param int $taskId
     * @return object
     * @throws TeamworkApiException
     */
    public function getTaskById(int $taskId): object
    {
        $response = $this->get(endpoint: "/projects/api/v3/tasks/{$taskId}");
        $this->handleError($response, "/projects/api/v3/tasks/{$taskId}");

        return $response;
    }

    /**
     * @param int $taskId
     * @return array
     * @throws TeamworkApiException
     */
    public function getTaskCustomFieldValue(int $taskId): array
    {
        $response = $this->get(endpoint: "/projects/api/v3/tasks/{$taskId}/customfields");
        $this->handleError($response, "/projects/api/v3/tasks/{$taskId}/customfields");

        return $response->customfieldTasks;
    }
}
