<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\BenefitsApplication;
use App\Models\Senior;
use App\Models\User;
use Carbon\Carbon;

class BenefitsApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing seniors and a user
        $seniors = Senior::inRandomOrder()->take(20)->get();
        $user = User::first();

        if ($seniors->isEmpty() || !$user) {
            $this->command->error('No seniors or users found. Please run the comprehensive senior seeder first.');
            return;
        }

        $milestoneAges = [80, 85, 90, 95, 100];
        $statuses = ['pending', 'approved', 'rejected', 'received'];

        foreach ($seniors as $index => $senior) {
            // Create main application
            $application = Application::create([
                'senior_id' => $senior->id,
                'application_type' => 'benefits',
                'submitted_by' => $user->id,
                'status' => $statuses[array_rand($statuses)],
                'submitted_at' => Carbon::now()->subDays(rand(1, 30)),
                'estimated_completion_date' => Carbon::now()->addDays(rand(10, 30)),
            ]);

            // Create benefits application details
            BenefitsApplication::create([
                'application_id' => $application->id,
                'milestone_age' => $milestoneAges[array_rand($milestoneAges)],
            ]);
        }

        $this->command->info('Successfully created 20 benefits applications with milestone ages!');
    }
}