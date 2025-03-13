<?php

namespace Tests\Unit;

use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function testGettingAllProducts()
    {
        $response = $this->json('GET', '/api/v1/admin/products');
        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                [
                    'id',
                    'name',
                    'description',
                    'units',
                    'price',
                    'image',
                    'created_at',
                    'updated_at'
                ]
            ]
        );
    }
}
