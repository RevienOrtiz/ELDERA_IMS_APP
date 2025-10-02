<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ELDERA IMS Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options specific to the ELDERA IMS
    | (Elderly Information Management System).
    |
    */

    'app' => [
        'name' => 'ELDERA IMS',
        'version' => '1.0.0',
        'description' => 'Elderly Information Management System',
    ],

    'seniors' => [
        'min_age' => 60,
        'osca_id_prefix' => 'OSCA',
        'auto_generate_osca_id' => true,
        'default_status' => 'active',
        'pension_eligibility_age' => 60,
        'pension_eligibility_income' => 5000, // PHP
    ],

    'applications' => [
        'statuses' => [
            'pending' => 'Pending',
            'received' => 'Received',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],
        'types' => [
            'senior_id' => 'Senior ID Application',
            'pension' => 'Pension Application',
            'benefits' => 'Benefits Application',
        ],
        'default_processing_days' => [
            'senior_id' => 30,
            'pension' => 15,
            'benefits' => 20,
        ],
    ],

    'events' => [
        'types' => [
            'general' => 'General Meeting',
            'pension' => 'Pension Distribution',
            'health' => 'Health Check-up',
            'id_claiming' => 'ID Claiming',
        ],
        'statuses' => [
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ],
    ],

    'benefits' => [
        'types' => [
            'medical' => 'Medical Assistance',
            'burial' => 'Burial Assistance',
            'financial' => 'Financial Assistance',
            'others' => 'Other Benefits',
        ],
        'milestone_ages' => [80, 85, 90, 95, 100],
        'amounts' => [
            'medical' => 5000,
            'burial' => 10000,
            'financial' => 3000,
            'others' => 2000,
        ],
    ],

    'file_uploads' => [
        'max_size' => 2048, // KB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'application/pdf',
        ],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
        'storage_disk' => 'public',
        'generate_thumbnails' => true,
        'thumbnail_size' => 300,
    ],

    'notifications' => [
        'types' => [
            'application_update' => 'Application Update',
            'event_reminder' => 'Event Reminder',
            'system_alert' => 'System Alert',
            'pension_reminder' => 'Pension Reminder',
        ],
    ],

    'dashboard' => [
        'charts' => [
            'age_groups' => [
                '60-65' => [60, 65],
                '66-70' => [66, 70],
                '71-75' => [71, 75],
                '76-80' => [76, 80],
                '81-85' => [81, 85],
                '86-90' => [86, 90],
                '90+' => [90, null],
            ],
        ],
        'statistics' => [
            'cache_duration' => 300, // 5 minutes
        ],
    ],

    'api' => [
        'rate_limit' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'pagination' => [
            'default_per_page' => 20,
            'max_per_page' => 100,
        ],
    ],

    'reports' => [
        'formats' => ['pdf', 'excel', 'csv'],
        'cache_duration' => 3600, // 1 hour
    ],

    'backup' => [
        'enabled' => true,
        'schedule' => 'daily',
        'retention_days' => 30,
    ],
];

























