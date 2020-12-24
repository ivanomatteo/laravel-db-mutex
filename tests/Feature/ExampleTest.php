<?php

namespace IvanoMatteo\LaravelDbMutex\Tests\Feature;

use IvanoMatteo\LaravelDbMutex\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
