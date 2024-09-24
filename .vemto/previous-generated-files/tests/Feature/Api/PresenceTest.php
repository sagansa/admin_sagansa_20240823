<?php

use App\Models\User;
use App\Models\Presence;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['email' => 'admin@admin.com']);

    Sanctum::actingAs($user, [], 'web');
});

test('it gets presences list', function () {
    $presences = Presence::factory()
        ->count(5)
        ->create();

    $response = $this->get(route('api.presences.index'));

    $response->assertOk()->assertSee($presences[0]->image_in);
});

test('it stores the presence', function () {
    $data = Presence::factory()
        ->make()
        ->toArray();

    $response = $this->postJson(route('api.presences.store'), $data);

    unset($data['created_at']);
    unset($data['updated_at']);

    $this->assertDatabaseHas('presences', $data);

    $response->assertStatus(201)->assertJsonFragment($data);
});

test('it updates the presence', function () {
    $presence = Presence::factory()->create();

    $user = User::factory()->create();
    $user = User::factory()->create();

    $data = [
        'status' => fake()->word(),
        'image_in' => fake()->text(255),
        'start_date_time' => fake()->dateTime(),
        'latitude_in' => fake()->randomNumber(2),
        'longitude_in' => fake()->randomNumber(2),
        'image_out' => fake()->text(255),
        'end_date_time' => fake()->dateTime(),
        'latitude_out' => fake()->randomNumber(2),
        'longitude_out' => fake()->randomNumber(2),
        'created_at' => fake()->dateTime(),
        'updated_at' => fake()->dateTime(),
        'created_by_id' => $user->id,
        'approved_by_id' => $user->id,
    ];

    $response = $this->putJson(route('api.presences.update', $presence), $data);

    unset($data['created_at']);
    unset($data['updated_at']);

    $data['id'] = $presence->id;

    $this->assertDatabaseHas('presences', $data);

    $response->assertStatus(200)->assertJsonFragment($data);
});

test('it deletes the presence', function () {
    $presence = Presence::factory()->create();

    $response = $this->deleteJson(route('api.presences.destroy', $presence));

    $this->assertModelMissing($presence);

    $response->assertNoContent();
});
