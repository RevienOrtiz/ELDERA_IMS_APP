<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Health Checkup Camp',
                'what' => 'Free health checkup for senior citizens including blood pressure, sugar level, and general health assessment.',
                'when' => 'October 25, 2023 at 9:00 AM',
                'where' => 'Community Center, Main Hall',
                'category' => 'HEALTH',
                'department' => 'Health Department',
                'hasListen' => true,
                'postedDate' => 'Oct 15, 2023',
            ],
            [
                'title' => 'Pension Distribution',
                'what' => 'Monthly pension distribution for registered senior citizens. Please bring your ID card.',
                'when' => 'November 1, 2023 at 10:00 AM',
                'where' => 'Municipal Office, Room 101',
                'category' => 'PENSION',
                'department' => 'Finance Department',
                'hasListen' => true,
                'postedDate' => 'Oct 16, 2023',
            ],
            [
                'title' => 'Community Gathering',
                'what' => 'Monthly community gathering for senior citizens with games, music, and refreshments.',
                'when' => 'October 30, 2023 at 3:00 PM',
                'where' => 'Senior Citizens Park',
                'category' => 'GENERAL',
                'department' => 'Community Affairs',
                'hasListen' => true,
                'postedDate' => 'Oct 17, 2023',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}