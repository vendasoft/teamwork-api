<?php

namespace Services;

use Illuminate\Support\Facades\Http;

class BaseHttpService
{
    public function __construct(
        protected string $siteUrl,
        protected string $username,
        protected string $password,
    ) {}

    protected function get(string $endpoint, $query = [], $headers = []): ?object
    {
        return $this->makeRequest('GET', endpoint: $endpoint, headers: $headers, query: $query);
    }

    protected function patch(string $endpoint, $data = [], $headers = []): ?object
    {
        return $this->makeRequest('PATCH', $endpoint, $data, $headers);
    }

    protected function post(string $endpoint, $data = [], $headers = []): ?object
    {
        return $this->makeRequest('POST', $endpoint, $data, $headers);
    }

    protected function delete(string $endpoint, $headers = []): ?object
    {
        return $this->makeRequest('DELETE', $endpoint, null, $headers);
    }

    protected function put($endpoint, $data = [], $headers = []): ?object
    {
        return $this->makeRequest('PUT', $endpoint, $data, $headers);
    }

    private function makeRequest($method, $endpoint, $data = [], $headers = [], $query = []): ?object
    {

        $token = base64_encode("{$this->username}:{$this->password}");
        $client = Http::baseUrl($this->siteUrl)
            ->withHeaders(['Authorization' => "Basic $token"]);

        if (! empty($query)) {
            $client->withQueryParameters($query);
        }
        $response = $client->$method($endpoint, $data);

        return json_decode($response->body());
    }
}