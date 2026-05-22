<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Bin;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\SanitizationTask;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────────
        $admin = User::updateOrCreate([
            'email' => 'admin@swms.com',
        ], [
            'name'              => 'Admin User',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
            'points'            => 0,
            'is_active'         => true,
        ]);

        $staff1 = User::updateOrCreate([
            'email' => 'staff@swms.com',
        ], [
            'name'              => 'Ravi Kumar',
            'password'          => Hash::make('password'),
            'role'              => 'staff',
            'phone'             => '9876543210',
            'area'              => 'North Campus',
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        $staff2 = User::updateOrCreate([
            'email' => 'sunita@swms.com',
        ], [
            'name'              => 'Sunita Devi',
            'password'          => Hash::make('password'),
            'role'              => 'staff',
            'phone'             => '9876543211',
            'area'              => 'South Campus',
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        $staff3 = User::updateOrCreate([
            'email' => 'mohan@swms.com',
        ], [
            'name'              => 'Mohan Singh',
            'password'          => Hash::make('password'),
            'role'              => 'staff',
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        $user1 = User::updateOrCreate([
            'email' => 'user@swms.com',
        ], [
            'name'              => 'Arjun Kumar',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'email_verified_at' => now(),
            'points'            => 240,
            'is_active'         => true,
        ]);

        $user2 = User::updateOrCreate([
            'email' => 'priya@swms.com',
        ], [
            'name'              => 'Priya Kaur',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'email_verified_at' => now(),
            'points'            => 340,
            'is_active'         => true,
        ]);

        $user3 = User::updateOrCreate([
            'email' => 'rahul@swms.com',
        ], [
            'name'              => 'Rahul Mehta',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'email_verified_at' => now(),
            'points'            => 280,
            'is_active'         => true,
        ]);

        $user4 = User::updateOrCreate([
            'email' => 'neha@swms.com',
        ], [
            'name'              => 'Neha Verma',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'email_verified_at' => now(),
            'points'            => 190,
            'is_active'         => true,
        ]);

        // ── Waste Categories ──────────────────────────────────────────────────
        $categories = [
            [
                'name'                   => 'Dry Waste',
                'slug'                   => 'dry-waste',
                'color'                  => '#2da563',
                'icon'                   => '🗂️',
                'bin_color'              => 'Green',
                'description'            => 'Non-biodegradable dry waste like paper, cardboard, glass, and metal.',
                'disposal_instructions'  => "• Place in the GREEN bin\n• Keep dry and clean\n• Flatten cardboard boxes\n• Rinse glass containers before disposal",
                'recycling_tips'         => "• Paper and cardboard are 100% recyclable\n• Glass can be recycled indefinitely\n• Metal cans should be cleaned before recycling",
                'is_hazardous'           => false,
            ],
            [
                'name'                   => 'Wet Waste',
                'slug'                   => 'wet-waste',
                'color'                  => '#c47d0a',
                'icon'                   => '🥬',
                'bin_color'              => 'Brown',
                'description'            => 'Biodegradable organic waste such as food scraps, vegetable peels, and garden waste.',
                'disposal_instructions'  => "• Place in the BROWN/DARK GREEN bin\n• Dispose within 24 hours to avoid odour\n• Do NOT mix with dry waste\n• Drain excess liquid before disposal",
                'recycling_tips'         => "• Can be composted at home\n• Rich source of organic fertilizer\n• Vermicomposting is an excellent option",
                'is_hazardous'           => false,
            ],
            [
                'name'                   => 'Plastic Waste',
                'slug'                   => 'plastic',
                'color'                  => '#1a5fb4',
                'icon'                   => '🧴',
                'bin_color'              => 'Blue',
                'description'            => 'All types of plastic including bottles, bags, containers, and wrappers.',
                'disposal_instructions'  => "• Place in the BLUE bin\n• Rinse bottles and containers before disposing\n• Remove caps and crush bottles to save space\n• Single-use plastics go to plastic bin",
                'recycling_tips'         => "• PET bottles (type 1) are highly recyclable\n• Avoid mixing food-soiled plastic with clean plastic\n• Carry reusable bags to reduce plastic waste",
                'is_hazardous'           => false,
            ],
            [
                'name'                   => 'E-Waste',
                'slug'                   => 'ewaste',
                'color'                  => '#7f77dd',
                'icon'                   => '💻',
                'bin_color'              => 'Grey',
                'description'            => 'Electronic waste including old phones, laptops, batteries, chargers, and wires.',
                'disposal_instructions'  => "• Place in designated GREY e-waste bin\n• Never throw in regular bins\n• Campus e-waste collection: every 1st Monday, Admin Block\n• Delete personal data before handing over devices",
                'recycling_tips'         => "• Batteries contain toxic metals — never burn or break\n• Many manufacturers offer take-back programs\n• Donate working electronics to schools or NGOs",
                'is_hazardous'           => true,
            ],
            [
                'name'                   => 'Hazardous Waste',
                'slug'                   => 'hazardous',
                'color'                  => '#c0392b',
                'icon'                   => '☣️',
                'bin_color'              => 'Red',
                'description'            => 'Chemicals, paints, acids, solvents, fertilizers, and other toxic materials.',
                'disposal_instructions'  => "• Use RED hazardous waste bin ONLY\n• Contact admin for special disposal arrangements\n• NEVER pour chemicals down drains\n• Label containers clearly before handing over\n• Store in cool, dry place until collection",
                'recycling_tips'         => "• Some chemicals can be neutralized before disposal\n• Contact local municipal authority for hazardous waste days\n• Use less harmful substitutes wherever possible",
                'is_hazardous'           => true,
            ],
            [
                'name'                   => 'Medical Waste',
                'slug'                   => 'medical',
                'color'                  => '#0f7173',
                'icon'                   => '🏥',
                'bin_color'              => 'Yellow',
                'description'            => 'Biomedical waste including syringes, needles, bandages, medicines, and PPE.',
                'disposal_instructions'  => "• Use YELLOW medical/biomedical waste bin\n• Never mix with household waste\n• Sharps (needles/syringes) go in puncture-proof containers\n• Expired medicines should go to pharmacy take-back programs",
                'recycling_tips'         => "• Sterilized medical waste can sometimes be landfilled\n• Contact campus medical centre for proper disposal\n• PPE (gloves, masks) should be double-bagged",
                'is_hazardous'           => true,
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = WasteCategory::create($cat);
        }

        // ── Smart Bins ────────────────────────────────────────────────────────
        $bins = [
            ['name' => 'Block A — Gate 3',   'area' => 'North Campus', 'fill_level' => 92, 'status' => 'overflow', 'bin_type' => 'general',  'latitude' => 30.9010, 'longitude' => 75.8573],
            ['name' => 'Main Canteen',        'area' => 'Central',      'fill_level' => 60, 'status' => 'medium',   'bin_type' => 'wet',       'latitude' => 30.9015, 'longitude' => 75.8580],
            ['name' => 'Hostel Block B',      'area' => 'Hostel Area',  'fill_level' => 25, 'status' => 'low',      'bin_type' => 'general',  'latitude' => 30.9020, 'longitude' => 75.8560],
            ['name' => 'Library Block',       'area' => 'Academic',     'fill_level' => 98, 'status' => 'overflow', 'bin_type' => 'dry',       'latitude' => 30.9005, 'longitude' => 75.8590],
            ['name' => 'Sports Ground',       'area' => 'Sports',       'fill_level' => 35, 'status' => 'low',      'bin_type' => 'plastic',  'latitude' => 30.9025, 'longitude' => 75.8550],
            ['name' => 'Admin Block',         'area' => 'Admin',        'fill_level' => 72, 'status' => 'medium',   'bin_type' => 'general',  'latitude' => 30.9008, 'longitude' => 75.8575],
            ['name' => 'Lab Block',           'area' => 'Academic',     'fill_level' => 88, 'status' => 'full',     'bin_type' => 'hazardous','latitude' => 30.9012, 'longitude' => 75.8585],
            ['name' => 'Parking Zone A',      'area' => 'Parking',      'fill_level' => 10, 'status' => 'low',      'bin_type' => 'general',  'latitude' => 30.9000, 'longitude' => 75.8570],
        ];
        foreach ($bins as $bin) {
            Bin::create($bin);
        }

        // ── Complaints ────────────────────────────────────────────────────────
        $complaintData = [
            ['user_id' => $user1->id, 'waste_category_id' => $categoryModels['dry-waste']->id,  'title' => 'Overflowing bin near Gate 3',       'area' => 'North Campus', 'status' => 'pending',     'priority' => 'urgent',  'assigned_to' => null],
            ['user_id' => $user2->id, 'waste_category_id' => $categoryModels['plastic']->id,     'title' => 'Plastic waste dumped on road',       'area' => 'Hostel Road',  'status' => 'in_progress', 'priority' => 'high',    'assigned_to' => $staff1->id],
            ['user_id' => $user3->id, 'waste_category_id' => $categoryModels['ewaste']->id,      'title' => 'E-waste pile in parking lot',        'area' => 'Parking Zone', 'status' => 'resolved',    'priority' => 'normal',  'assigned_to' => $staff2->id],
            ['user_id' => $user1->id, 'waste_category_id' => $categoryModels['wet-waste']->id,   'title' => 'Wet garbage beside canteen',         'area' => 'Canteen',      'status' => 'resolved',    'priority' => 'normal',  'assigned_to' => $staff1->id],
            ['user_id' => $user4->id, 'waste_category_id' => $categoryModels['hazardous']->id,   'title' => 'Chemical waste in lab corridor',     'area' => 'Lab Block',    'status' => 'closed',      'priority' => 'urgent',  'assigned_to' => $staff2->id],
            ['user_id' => $user2->id, 'waste_category_id' => $categoryModels['medical']->id,     'title' => 'Used masks near medical centre',     'area' => 'Medical',      'status' => 'resolved',    'priority' => 'high',    'assigned_to' => $staff3->id],
            ['user_id' => $user3->id, 'waste_category_id' => $categoryModels['dry-waste']->id,   'title' => 'Paper waste scattered near library', 'area' => 'Library',      'status' => 'in_progress', 'priority' => 'normal',  'assigned_to' => $staff3->id],
            ['user_id' => $user4->id, 'waste_category_id' => $categoryModels['plastic']->id,     'title' => 'Plastic bottles dumped in garden',   'area' => 'Garden',       'status' => 'pending',     'priority' => 'normal',  'assigned_to' => null],
        ];

        foreach ($complaintData as $i => $data) {
            Complaint::create(array_merge($data, [
                'description'       => 'This is a sample complaint submitted for testing purposes. The waste needs to be cleared as soon as possible.',
                'specific_location' => 'Near the main entrance',
                'building'          => 'Block ' . chr(65 + $i),
                'created_at'        => now()->subDays(rand(0, 14)),
            ]));
        }

        // ── Sanitization Tasks ────────────────────────────────────────────────
        $taskTypes = ['Floor sanitization', 'Bin cleaning', 'Drain clearing', 'Area disinfection', 'Pathway sweeping'];
        $areas     = ['Main Canteen', 'Block A', 'Hostel Area', 'Library', 'Sports Ground', 'Admin Block'];

        foreach ($areas as $i => $area) {
            SanitizationTask::create([
                'assigned_to'       => [$staff1->id, $staff2->id, $staff3->id][$i % 3],
                'created_by'        => $admin->id,
                'area'              => $area,
                'task_type'         => $taskTypes[$i % count($taskTypes)],
                'description'       => "Regular {$taskTypes[$i % count($taskTypes)]} for {$area}",
                'scheduled_date'    => now()->toDateString(),
                'scheduled_time'    => ['07:00', '09:00', '11:00', '13:00', '15:00', '17:00'][$i],
                'status'            => ['completed', 'in_progress', 'pending', 'completed', 'pending', 'completed'][$i],
                'completion_percent'=> [100, 60, 0, 100, 0, 100][$i],
                'completed_at'      => in_array($i, [0, 3, 5]) ? now() : null,
            ]);
        }

        // ── Notifications ─────────────────────────────────────────────────────
        Notification::create(['user_id' => $admin->id,  'title' => 'Bin overflow alert',      'message' => 'Library Block bin is 98% full — collect immediately.',     'type' => 'bin',      'is_read' => false]);
        Notification::create(['user_id' => $admin->id,  'title' => 'New complaint filed',     'message' => '#C-0001 — Overflowing bin near Gate 3',                    'type' => 'complaint','is_read' => false]);
        Notification::create(['user_id' => $staff1->id, 'title' => 'Task assigned to you',   'message' => 'Bin cleaning at Block A on ' . now()->format('d M Y'),      'type' => 'task',     'is_read' => false]);
        Notification::create(['user_id' => $user1->id,  'title' => 'Complaint status update', 'message' => '#C-0002 is now In Progress — assigned to Ravi Kumar.',      'type' => 'complaint','is_read' => false]);
        Notification::create(['user_id' => $user1->id,  'title' => 'Points earned!',          'message' => '+20 pts for complaint #C-0004 being resolved. Keep it up!', 'type' => 'points',   'is_read' => true]);
        Notification::create(['user_id' => $user2->id,  'title' => 'Complaint resolved',      'message' => '#C-0006 — Used masks near medical centre has been cleared.', 'type' => 'complaint','is_read' => false]);

        $this->command->info('✅  Database seeded successfully!');
        $this->command->info('');
        $this->command->info('  Admin  → admin@swms.com  / password');
        $this->command->info('  Staff  → staff@swms.com  / password');
        $this->command->info('  User   → user@swms.com   / password');
    }
}
