<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (!str_contains($user->email, "empty")) {
                Contact::factory(5)->create([
                    'user_id' => $user->id,
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'title' => fake()->jobTitle(),
                    'description' => fake()->paragraph(),
                    'address' => fake()->address(),
                    'email' => fake()->safeEmail(),
                    'profile_picture' => fake()->imageUrl(),
                    'banner_picture' => fake()->imageUrl(1920, 1080),
                ]);
            }
        });
    }
}
