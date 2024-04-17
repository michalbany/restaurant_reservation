<?php

use App\Models\Reservation;
use App\Models\User;

test('can create reservation with factory', function () {
    $reservation = Reservation::factory()->create();
    expect($reservation)->toBeInstanceOf(Reservation::class);
});

test('can we load my-reservations as a logged-in user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/reservations');

    $response->assertStatus(200);
});

test('reservations can be listed for a logged-in user', function () {
    $user = User::factory()->create();
    $reservations = Reservation::factory()->count(3)->create();

    $response = $this->actingAs($user)->get('/reservations');

    $response->assertSee($reservations[0]->guest_number);
    $response->assertSee($reservations[1]->guest_number);
    $response->assertSee($reservations[2]->guest_number);
});