<?php
namespace Tests\Feature\Traits;

trait WithApiTesting
{
    protected function getJsonStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes',
                    'relationships'
                ]
            ],
            'meta' => [
                'total',
                'page',
                'per_page'
            ]
        ];
    }

    protected function assertJsonApiResponse($response, int $statusCode = 200): void
    {
        $response->assertStatus($statusCode)
            ->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    protected function assertJsonApiCollection($response, int $count = null): void
    {
        $this->assertJsonApiResponse($response);
        
        if ($count !== null) {
            $response->assertJsonCount($count, 'data');
        }
        
        $response->assertJsonStructure($this->getJsonStructure());
    }

    protected function assertJsonApiResource($response, array $expectedData = []): void
    {
        $this->assertJsonApiResponse($response);
        
        $response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes'
            ]
        ]);

        if (!empty($expectedData)) {
            $response->assertJsonFragment($expectedData);
        }
    }
}

