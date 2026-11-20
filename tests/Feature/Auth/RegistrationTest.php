<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_number' => '7700000000',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'phone_number' => '7700000000',
    ]);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration rejects a phone number that is already in use', function () {
    User::factory()->create([
        'phone_number' => '7700000000',
    ]);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Another User',
        'email' => 'another@example.com',
        'phone_number' => '7700000000',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('phone_number');
});
