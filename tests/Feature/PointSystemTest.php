<?php

use App\Models\Point;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('awards 50 points only once per day after first check-out', function () {
    $admin = User::factory()->create(['role' => 'administrator']);
    $member = User::factory()->create(['role' => 'user']);

    Sanctum::actingAs($admin);

    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk()->assertJsonPath('record.action', 'check_in');

    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk()->assertJsonPath('record.action', 'check_out');

    expect(Point::where('user_id', $member->id)->value('point'))->toBe(50);

    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk()->assertJsonPath('record.action', 'check_in');

    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk()->assertJsonPath('record.action', 'check_out');

    expect(Point::where('user_id', $member->id)->value('point'))->toBe(50);
});


it('limits /api/points to the authenticated user for user and trainer roles', function () {
    $member = User::factory()->create(['role' => 'user']);
    $trainer = User::factory()->create(['role' => 'trainer']);

    Point::create(['user_id' => $member->id, 'point' => 80]);
    Point::create(['user_id' => $trainer->id, 'point' => 140]);

    Sanctum::actingAs($member);

    $this->getJson('/api/points')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.user_id', $member->id)
        ->assertJsonPath('data.0.point', 80);

    Sanctum::actingAs($trainer);

    $this->getJson('/api/points')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.user_id', $trainer->id)
        ->assertJsonPath('data.0.point', 140);
});

it('supports administrator CRUD for points endpoint', function () {
    $admin = User::factory()->create(['role' => 'administrator']);
    $member = User::factory()->create(['role' => 'user']);

    Sanctum::actingAs($admin);

    $createResponse = $this->postJson('/api/points', [
        'user_id' => $member->id,
        'point' => 125,
    ])->assertCreated()->assertJsonPath('data.point', 125);

    $pointId = $createResponse->json('data.id');

    $this->getJson('/api/points')
        ->assertOk()
        ->assertJsonPath('data.0.id', $pointId);

    $this->getJson('/api/points/' . $pointId)
        ->assertOk()
        ->assertJsonPath('data.user_id', $member->id);

    $this->patchJson('/api/points/' . $pointId, [
        'point' => 175,
    ])->assertOk()->assertJsonPath('data.point', 175);

    $this->deleteJson('/api/points/' . $pointId)
        ->assertOk();

    $this->assertDatabaseMissing('points', ['id' => $pointId]);
});

it('allows admin manual adjustments without daily reward restrictions', function () {
    $admin = User::factory()->create(['role' => 'administrator']);
    $member = User::factory()->create(['role' => 'user']);

    Sanctum::actingAs($admin);

    // First completed cycle awards daily reward.
    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk();

    $this->postJson('/api/attendance/scan', [
        'user_id' => $member->id,
        'qr_type' => 'user',
    ])->assertOk();

    // Admin can still adjust points freely on the same day.
    $this->postJson('/api/points/adjust', [
        'user_id' => $member->id,
        'amount' => -25,
        'reason' => 'Reward redemption',
    ])->assertOk()->assertJsonPath('data.point', 25);

    $this->postJson('/api/points/adjust', [
        'user_id' => $member->id,
        'amount' => 5000,
        'reason' => 'Manual correction',
    ])->assertOk()->assertJsonPath('data.point', 5025);
});
