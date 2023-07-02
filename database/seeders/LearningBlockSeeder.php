<?php

namespace Database\Seeders;

use App\Models\LearningBlock;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class LearningBlockSeeder extends Seeder
{
    public function run()
    {
        $topics = Topic::all();

        foreach ($topics as $topic) {
            LearningBlock::factory()->count(5)->create([
                'topic_id' => $topic->id,
            ]);
        }
    }
}
