<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'partner_category_id' => PartnerCategory::factory(),
            'legal_name' => fake()->company(),
            'trade_name' => fake()->optional()->company(),
            'cnpj' => null,
            'phone' => fake()->optional()->phoneNumber(),
        ];
    }
}
