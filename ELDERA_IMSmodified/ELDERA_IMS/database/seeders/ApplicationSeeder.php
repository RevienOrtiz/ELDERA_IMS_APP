<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\PensionApplication;
use App\Models\BenefitsApplication;
use App\Models\SeniorIdApplication;
use App\Models\Senior;
use App\Models\User;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing seniors and users
        $seniors = Senior::take(10)->get();
        $user = User::first() ?? User::factory()->create();

        // Create 10 Pension Applications
        for ($i = 0; $i < 10; $i++) {
            $senior = $seniors->random();
            
            $application = Application::create([
                'senior_id' => $senior->id,
                'application_type' => 'pension',
                'status' => $this->getRandomStatus(),
                'submitted_by' => $user->id,
                'submitted_at' => Carbon::now()->subDays(rand(1, 30)),
                'estimated_completion_date' => Carbon::now()->addDays(15),
            ]);

            PensionApplication::create([
                'application_id' => $application->id,
                'rrn' => 'RRN' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'monthly_income' => rand(1000, 8000),
                'has_pension' => rand(0, 1),
                'pension_source' => rand(0, 1) ? ['SSS', 'GSIS', 'Private', 'Other'][rand(0, 3)] : null,
                'pension_amount' => rand(0, 1) ? rand(1000, 5000) : null,
            ]);
        }

        // Create 10 Benefits Applications
        for ($i = 0; $i < 10; $i++) {
            $senior = $seniors->random();
            
            $application = Application::create([
                'senior_id' => $senior->id,
                'application_type' => 'benefits',
                'status' => $this->getRandomStatus(),
                'submitted_by' => $user->id,
                'submitted_at' => Carbon::now()->subDays(rand(1, 30)),
                'estimated_completion_date' => Carbon::now()->addDays(30),
            ]);

            BenefitsApplication::create([
                'application_id' => $application->id,
                'benefit_type' => ['medical', 'burial', 'financial', 'others'][rand(0, 3)],
                'reason' => $this->getRandomReason(),
                'milestone_age' => [80, 85, 90, 95, 100][rand(0, 4)],
            ]);
        }

        // Create 10 Senior ID Applications
        for ($i = 0; $i < 10; $i++) {
            $senior = $seniors->random();
            
            $application = Application::create([
                'senior_id' => $senior->id,
                'application_type' => 'senior_id',
                'status' => $this->getRandomStatus(),
                'submitted_by' => $user->id,
                'submitted_at' => Carbon::now()->subDays(rand(1, 30)),
                'estimated_completion_date' => Carbon::now()->addDays(30),
            ]);

            SeniorIdApplication::create([
                'application_id' => $application->id,
                'full_name' => $senior->first_name . ' ' . $senior->last_name,
                'address' => $senior->address ?? 'Sample Address, Lingayen, Pangasinan',
                'gender' => $senior->gender ?? ['Male', 'Female'][rand(0, 1)],
                'date_of_birth' => $senior->date_of_birth ?? Carbon::now()->subYears(rand(60, 90)),
                'birth_place' => 'Lingayen, Pangasinan',
                'occupation' => ['Retired', 'Farmer', 'Vendor', 'Housewife', 'Driver'][rand(0, 4)],
                'civil_status' => ['Single', 'Married', 'Widowed', 'Divorced'][rand(0, 3)],
                'annual_income' => rand(20000, 150000),
                'pension_source' => ['SSS', 'GSIS', 'Private', 'None'][rand(0, 3)],
                'ctc_number' => 'CTC' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'place_of_issuance' => 'Lingayen, Pangasinan',
                'contact_number' => '09' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
            ]);
        }
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'received', 'approved', 'rejected'];
        $weights = [35, 25, 25, 15]; // More pending and received
        
        $random = rand(1, 100);
        $cumulative = 0;
        
        for ($i = 0; $i < count($statuses); $i++) {
            $cumulative += $weights[$i];
            if ($random <= $cumulative) {
                return $statuses[$i];
            }
        }
        
        return 'pending';
    }

    private function getRandomReason(): string
    {
        $reasons = [
            'Medical emergency requiring immediate financial assistance',
            'Burial assistance needed for deceased family member',
            'Financial support for daily living expenses',
            'Medical treatment for chronic illness',
            'Emergency financial aid for home repairs',
            'Support for medication and healthcare needs',
            'Assistance with transportation costs for medical appointments',
            'Financial help for family emergency',
            'Support for basic necessities and food',
            'Medical equipment and supplies needed'
        ];
        
        return $reasons[array_rand($reasons)];
    }
}
