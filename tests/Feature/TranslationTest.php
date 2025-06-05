<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Translation;
use App\Models\TranslationValue;
use App\Models\Tag;
use App\Models\User;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'api_token' => hash('sha256', 'testtoken'),
        ]);

        $this->headers = ['Authorization' => 'Bearer testtoken'];
    }

    public function test_can_create_a_translation(): void
    {
        $payload = [
            'key' => 'greeting.hello',
            'group' => 'greeting',
            'values' => [
                ['locale' => 'en', 'value' => 'Hello'],
                ['locale' => 'de', 'value' => 'Hallo'],
            ],
            'tags' => ['common', 'homepage']
        ];

        $response = $this->postJson('/api/translations', $payload, $this->headers);

        $response->assertStatus(201)->assertJsonFragment(['key' => 'greeting.hello']);
    }

    public function test_can_update_a_translation(): void
    {
        $translation = Translation::factory()->create();
        $translation->values()->createMany([
            ['locale' => 'en', 'value' => 'Old Value'],
        ]);

        $payload = [
            'key' => 'greeting.updated',
            'values' => [
                ['locale' => 'en', 'value' => 'Updated Value'],
            ],
            'tags' => ['updated'],
        ];

        $response = $this->putJson("/api/translations/{$translation->id}", $payload, $this->headers);

        $response->assertStatus(200)->assertJsonFragment(['key' => 'greeting.updated']);
    }

    public function test_can_list_translations_with_filters(): void
    {
        $translation = Translation::factory()->create(['key' => 'filtered.key']);
        $translation->values()->create(['locale' => 'en', 'value' => 'Filtered Value']);
        $tag = Tag::factory()->create(['name' => 'filter']);
        $translation->tags()->attach($tag->id);

        $response = $this->getJson('/api/translations?key=filtered&content=Filtered&tag=filter', $this->headers);

        $response->assertStatus(200)->assertJsonFragment(['key' => 'filtered.key']);
    }

    public function test_can_export_translations_for_a_locale(): void
    {
        $translation = Translation::factory()->create(['key' => 'site.title']);
        $translation->values()->create(['locale' => 'en', 'value' => 'My Site']);

        $response = $this->getJson('/api/export/en', $this->headers);

        $response->assertStatus(200)->assertJsonFragment(['site.title' => 'My Site']);
    }
}
