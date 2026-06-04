<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('shows the registration screen', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
    $response->assertStatus(200);
    // $response->assertSee('Crear Cuenta');

    $response->assertSeeInOrder([
        'Crear Cuenta',
        'Registrarme'
    ]);
});

it('registers a new user as unverified and dispatches the registered event', function () {
    Event::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Qu!r@z1538#',
        'password_confirmation' => 'Qu!r@z1538#',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'test@example.com')->first();

    expect($user->email)->not()->toBeNull();
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);
});

it('should validate required fields when the request body is empty', function () {
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password'
    ]);

    $response->assertSessionHasErrors([
        'name' => 'El campo nombre es obligatorio.',
        'email' => 'El campo email es obligatorio.',
        'password' => 'El campo contraseña es obligatorio.',
    ]);
});

it('prevents duplicate emails addresses', function () {
    User::factory()->create([
        'email' => 'test@example.com'
    ]);

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Qu!r@z1538#',
        'password_confirmation' => 'Qu!r@z1538#',
    ]);

    $response->assertRedirect();

    $response->assertSessionHasErrors([
        'email' => 'El email se encuentra en uso.',
    ]);
});

it('sends the verification email notification after registration', function () {
    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Qu!r@z1538#',
        'password_confirmation' => 'Qu!r@z1538#',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('verfies the user email from a signed verfication link', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect(route('dashboard'));

    expect($user->hasVerifiedEmail())->toBeTrue();
});

it('does not allow an unverified user to access the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));
});

it('allow a verified user to access the dashboard', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});
