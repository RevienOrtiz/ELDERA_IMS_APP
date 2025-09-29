<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Senior;
use Illuminate\Support\Facades\DB;

class ResetAppAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds to reset all seniors' has_app_account to false.
     *
     * @return void
     */
    public function run()
    {
        // Update all seniors to have has_app_account = false
        Senior::query()->update(['has_app_account' => false]);
        
        $this->command->info('All seniors\' app accounts have been reset to false (pink buttons).');
    }
}