<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
//    public function testExample()
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }
    use RefreshDatabase;
    use ActingJWTUser;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testStoreTopic()
    {
//        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];
//
//        $token = auth('api')->fromUser($this->user);
//        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
//            ->json('POST', '/api/v1/topics', $data);
//
//        $assertData = [
//            'category_id' => 1,
//            'user_id' => $this->user->id,
//            'title' => 'test title',
//            'body' => clean('test body', 'user_topic_body'),
//        ];
//
//        $response->assertStatus(201)
//            ->assertJsonFragment($assertData);

        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', '/api/v1/topics', $data);
        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];

        $response->assertStatus(201)
            ->assertJsonFragment($assertData);
    }
    public function testUpdateTopic()
    {
        $topic = $this->makeTopic();

        $editData = ['category_id' => 2, 'body' => 'edit body2', 'title' => 'edit title2'];

        $response = $this->JWTActingAs($this->user)
            ->json('PATCH', '/api/v1/topics/'.$topic->id, $editData);

        $assertData= [
            'category_id' => 2,
            'user_id' => $this->user->id,
            'title' => 'edit title2',
            'body' => clean('edit body2', 'user_topic_body'),
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    protected function makeTopic()
    {
        return factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => 1,
        ]);
    }
}
