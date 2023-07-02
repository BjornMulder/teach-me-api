<?php

namespace Database\Factories;

use App\Models\LearningBlock;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningBlockFactory extends Factory
{
    protected $model = LearningBlock::class;

    public function definition()
    {
        return [
            'topic_id' => Topic::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'order' => $this->faker->randomDigitNotNull,
        ];
    }
}
