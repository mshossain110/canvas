<?php

namespace Canvas\Tests;

use Ramsey\Uuid\Uuid;

class TagTest extends TestCase
{
    /** @test */
    public function allow_tags_to_share_the_same_slug_with_unique_users()
    {
        $user_1 = factory(\Illuminate\Foundation\Auth\User::class)->create();
        $tag_1 = $this->actingAs($user_1)->withoutMiddleware()->post('/canvas/api/tags/create', [
            'id' => Uuid::uuid4(),
            'name' => 'Empire Strikes Back',
            'slug' => 'empire-strikes-back',
        ]);

        $user_2 = factory(\Illuminate\Foundation\Auth\User::class)->create();
        $tag_2 = $this->actingAs($user_2)->withoutMiddleware()->post('/canvas/api/tags/create', [
            'id' => Uuid::uuid4(),
            'name' => 'Empire Strikes Back',
            'slug' => 'empire-strikes-back',
        ]);

        $this->assertDatabaseHas('canvas_tags', [
            'id' => $tag_1->decodeResponseJson()['id'],
            'slug' => $tag_1->decodeResponseJson()['slug'],
            'user_id' => $tag_1->decodeResponseJson()['user_id'],
        ]);

        $this->assertDatabaseHas('canvas_tags', [
            'id' => $tag_2->decodeResponseJson()['id'],
            'slug' => $tag_2->decodeResponseJson()['slug'],
            'user_id' => $tag_2->decodeResponseJson()['user_id'],
        ]);
    }
}
