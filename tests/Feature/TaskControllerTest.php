<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing of index
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('api/tasks');

        $response->assertStatus(200);
    }

    /**
     * Testing of create
     *
     * @return void
     */
    public function testCreateTask()
    {
        $jsonArr = array();
        $jsonArr['parent_id'] = null;
        $jsonArr['user_id'] = "1";
        $jsonArr['title'] = "task 1";
        $jsonArr['points'] = '1';
        $jsonArr['is_done'] = '0';
        $response =  $this->withHeaders([
            'Content-type' => 'application/json'
        ])->json('POST', '/api/task', $jsonArr);

        $response->assertStatus(201);
    }

    /**
     * Testing of update
     *
     * @return void
     */
    public function testUpdateTask()
    {
        $jsonArr = array();
        $jsonArr['parent_id'] = null;
        $jsonArr['user_id'] = "1";
        $jsonArr['title'] = "task 2";
        $jsonArr['points'] = '1';
        $jsonArr['is_done'] = '0';
        $response =  $this->withHeaders([
            'Content-type' => 'application/json'
        ])->json('POST', '/api/task', $jsonArr);

        $response->assertStatus(201);
    }
}
