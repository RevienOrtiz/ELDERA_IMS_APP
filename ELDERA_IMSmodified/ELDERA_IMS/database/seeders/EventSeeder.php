<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Senior Citizen Monthly Meeting',
                'description' => 'Regular monthly meeting for all senior citizens to discuss community matters and upcoming activities.',
                'event_type' => 'general',
                'event_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'location' => 'LCSCF Office',
                'organizer' => 'Lingayen Senior Citizens Federation',
                'contact_person' => 'Maria Santos',
                'contact_number' => '09123456789',
                'status' => 'upcoming',
                'max_participants' => 50,
                'current_participants' => 0,
                'requirements' => 'Valid OSCA ID',
                'created_by' => 1,
            ],
            [
                'title' => 'Health Check-up Program',
                'description' => 'Free health check-up including blood pressure, blood sugar, and general health assessment.',
                'event_type' => 'health',
                'event_date' => Carbon::now()->addDays(10),
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'location' => 'Municipal Health Center',
                'organizer' => 'Municipal Health Office',
                'contact_person' => 'Dr. Juan Dela Cruz',
                'contact_number' => '09123456790',
                'status' => 'upcoming',
                'max_participants' => 100,
                'current_participants' => 0,
                'requirements' => 'Fasting for 8 hours, bring previous medical records',
                'created_by' => 1,
            ],
            [
                'title' => 'Senior Citizen ID Claiming',
                'description' => 'Distribution of newly printed Senior Citizen ID cards.',
                'event_type' => 'id_claiming',
                'event_date' => Carbon::now()->addDays(20),
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'location' => 'OSCA Office',
                'organizer' => 'Office of Senior Citizens Affairs',
                'contact_person' => 'Pedro Martinez',
                'contact_number' => '09123456792',
                'status' => 'upcoming',
                'max_participants' => 75,
                'current_participants' => 0,
                'requirements' => 'Application receipt and valid ID',
                'created_by' => 1,
            ],
            [
                'title' => 'Nutrition Program',
                'description' => 'Distribution of nutritional supplements and health education session.',
                'event_type' => 'health',
                'event_date' => Carbon::now()->addDays(25),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'location' => 'Barangay Health Station',
                'organizer' => 'Municipal Nutrition Office',
                'contact_person' => 'Carmen Lopez',
                'contact_number' => '09123456793',
                'status' => 'upcoming',
                'max_participants' => 30,
                'current_participants' => 0,
                'requirements' => 'Valid OSCA ID and medical clearance',
                'created_by' => 1,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}