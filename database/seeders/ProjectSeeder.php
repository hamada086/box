<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $clients = Client::all();
        $team = TeamMember::where('department', '!=', 'management')->get();

        $clients->each(function ($client) use ($team) {
            $projects = Project::factory()
                ->count(rand(1, 3))
                ->create(['client_id' => $client->id]);

            $projects->each(function ($project) use ($team) {
                $project->teamMembers()->attach(
                    $team->random(rand(1, 3))->pluck('id'),
                    ['role' => 'contributor', 'hours_worked' => rand(5, 40)]
                );
            });
        });
    }
}