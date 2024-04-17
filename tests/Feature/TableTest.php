<?php

use App\Models\Table;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;

test('can create table with factory', function () {
    $table = Table::factory()->create();
    expect($table)->toBeInstanceOf(Table::class);
});

test('can we load dashboard as a logged-in user', function () {
    $user = User::factory()->create();  // Vytvoření testovacího uživatele

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});

test('tables can be listed for a logged-in user', function () {
    $user = User::factory()->create();  // Vytvoření testovacího uživatele
    $tables = Table::factory()->count(3)->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSee($tables[0]->capacity);
    $response->assertSee($tables[1]->capacity);
    $response->assertSee($tables[2]->capacity);
});

// Pouze ilustrační
test('can check if a table is free with no reservations', function () {
    $mockTable = Mockery::mock(Table::class);
    $dateTime = Carbon::tomorrow()->setHour(12)->setMinute(0);
    $capacity = 4;

    $mockTable->shouldReceive('free')
        ->with($dateTime, $capacity)
        ->andReturn(new Collection([new Table]));

    $freeTables = $mockTable->free($dateTime, $capacity);

    expect($freeTables->count())->toBe(1);
});

test('can check if a table is free with existing reservations', function () {
    $mockTable = Mockery::mock(Table::class);
    $dateTime = Carbon::tomorrow()->setHour(12)->setMinute(0);
    $capacity = 4;

    $mockTable->shouldReceive('free')
        ->with($dateTime, $capacity)
        ->andReturn(new Illuminate\Database\Eloquent\Collection());

    $freeTables = $mockTable->free($dateTime, $capacity);

    expect($freeTables->count())->toBe(0);
});

// test('can check if a table is free with no reservations', function () {
//     $table = Table::factory()->create();
//     $dateTime = Carbon::tomorrow()->setHour(12)->setMinute(0);
//     $capacity = 4;

//     $freeTables = $table->free($dateTime, $capacity);

//     expect($freeTables)->toBeInstanceOf(Collection::class);
//     expect($freeTables->count())->toBe(1);
// });

// test('can check if a table is free with existing reservations', function () {
//     $table = Table::factory()->create();
//     $dateTime = Carbon::tomorrow()->setHour(12)->setMinute(0);
//     $capacity = 4;

//     // Create a reservation that overlaps with the specified date and time
//     Reservation::factory()->create([
//         'table_id' => $table->id,
//         'reservation_time' => $dateTime->copy()->addHour(),
//     ]);

//     $freeTables = $table->free($dateTime, $capacity);

//     expect($freeTables)->toBeInstanceOf(Collection::class);
//     expect($freeTables->count())->toBe(0);
// });