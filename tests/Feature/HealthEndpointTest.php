<?php

it('returns health payload', function () {
    $response = $this->getJson('/api/v1/health');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'app',
                'env',
                'database',
                'redis',
            ],
        ]);
});
