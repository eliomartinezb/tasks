<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()->createMany([
            [
                "name" => "Project 1",
            ],
            [
                "name" => "Project 2",
            ],
            [
                "name" => "Project 3",
            ]
        ]);
    }
}
