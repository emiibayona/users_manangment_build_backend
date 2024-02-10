<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $user_id;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'activity' => fake()->jobTitle(),
            'description' => fake()->paragraphs(),
            'address' => fake()->address(),
            'email' => fake()->safeEmail(),
            'profile_picture' => fake()->imageUrl(),
            'banner_picture' => fake()->imageUrl(1920, 1080),
        ];
    }
}
