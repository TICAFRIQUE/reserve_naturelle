<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'tel' => '07' . fake()->numerify('########'),
            'role' => fake()->randomElement([
                'user',
                'user',
                'user',
                'admin',
                'fournisseur',
            ]),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => 'admin',
        ]);
    }

    public function client(): static
    {
        return $this->state(fn () => [
            'role' => 'user',
        ]);
    }

    public function fournisseur(): static
    {
        return $this->state(fn () => [
            'role' => 'fournisseur',
        ]);
    }
}