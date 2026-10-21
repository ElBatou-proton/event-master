<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // 1. Categories
        $categories = [
            [
                'id'          => 1,
                'name'        => 'Catering & Beverages',
                'slug'        => 'catering-beverages',
                'icon'        => 'bi-cup-hot',
                'description' => 'Full-service catering, buffets, cocktail bars, and wedding cakes.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'name'        => 'Venues & Locations',
                'slug'        => 'venues-locations',
                'icon'        => 'bi-buildings',
                'description' => 'Ballrooms, estates, conference centers, and outdoor gardens.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 3,
                'name'        => 'DJs & Live Music',
                'slug'        => 'djs-music',
                'icon'        => 'bi-music-note-beamed',
                'description' => 'Professional DJs, live acoustic bands, MCs, and sound systems.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 4,
                'name'        => 'Photography & Video',
                'slug'        => 'photography-video',
                'icon'        => 'bi-camera-reels',
                'description' => 'Event photographers, drone videography, and photo booths.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 5,
                'name'        => 'Decor & Floristry',
                'slug'        => 'decor-floristry',
                'icon'        => 'bi-flower1',
                'description' => 'Floral centerpieces, themes, stage backdrops, and table settings.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 6,
                'name'        => 'Lighting & AV Production',
                'slug'        => 'lighting-av-production',
                'icon'        => 'bi-speaker',
                'description' => 'Stage lighting, LED screens, projectors, and audio engineering.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 7,
                'name'        => 'Security & Staffing',
                'slug'        => 'security-staffing',
                'icon'        => 'bi-shield-check',
                'description' => 'Event security guards, valet parking, and hospitality hosts.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($categories as $cat) {
            $db->table('categories')->ignore(true)->insert($cat);
        }

        // 2. Providers
        $providers = [
            [
                'id'             => 1,
                'category_id'    => 1,
                'name'           => 'Gourmet Haven Catering',
                'contact_person' => 'Chef Marcus Rivera',
                'email'          => 'marcus@gourmethaven.com',
                'phone'          => '+1 (555) 234-5678',
                'website'        => 'https://gourmethaven.com',
                'address'        => '742 Evergreen Terrace, Suite 100',
                'city'           => 'Springfield',
                'price_range'    => '$$$',
                'rating'         => 5,
                'status'         => 'preferred',
                'notes'          => 'Top-tier farm-to-table menus. Can handle gluten-free & vegan options effortlessly.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 2,
                'category_id'    => 2,
                'name'           => 'The Grand Glasshouse Gardens',
                'contact_person' => 'Victoria Bennett',
                'email'          => 'events@grandglasshouse.com',
                'phone'          => '+1 (555) 876-5432',
                'website'        => 'https://grandglasshouse.com',
                'address'        => '1200 Highland Parkway',
                'city'           => 'Springfield',
                'price_range'    => '$$$$',
                'rating'         => 5,
                'status'         => 'preferred',
                'notes'          => 'Stunning glass greenhouse venue with seating up to 350 guests. Integrated heating & AC.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 3,
                'category_id'    => 3,
                'name'           => 'DJ Pulse & Sound Masters',
                'contact_person' => 'Alex "DJ Pulse" Morales',
                'email'          => 'booking@djpulsemusic.com',
                'phone'          => '+1 (555) 345-6789',
                'website'        => 'https://djpulsemusic.com',
                'address'        => '88 Sunset Blvd',
                'city'           => 'Springfield',
                'price_range'    => '$$',
                'rating'         => 5,
                'status'         => 'preferred',
                'notes'          => 'Club, wedding, and corporate specialist. Includes wireless mics, subwoofer, and lighting.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 4,
                'category_id'    => 4,
                'name'           => 'Lumina Film & Photography',
                'contact_person' => 'Sophia Chen',
                'email'          => 'hello@luminastudios.com',
                'phone'          => '+1 (555) 987-6543',
                'website'        => 'https://luminastudios.com',
                'address'        => '450 Art District Way',
                'city'           => 'Springfield',
                'price_range'    => '$$$',
                'rating'         => 5,
                'status'         => 'active',
                'notes'          => 'Award-winning photojournalistic style. Includes drone 4K footage and same-day teaser edit.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 5,
                'category_id'    => 5,
                'name'           => 'Bloom & Blossom Floral Designers',
                'contact_person' => 'Hannah Ward',
                'email'          => 'hannah@bloombllosom.com',
                'phone'          => '+1 (555) 432-1098',
                'website'        => 'https://bloombllosom.com',
                'address'        => '321 Meadow Lane',
                'city'           => 'Springfield',
                'price_range'    => '$$',
                'rating'         => 4,
                'status'         => 'active',
                'notes'          => 'Custom arches, table garlands, bridal bouquets, and sustainable floristry.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 6,
                'category_id'    => 6,
                'name'           => 'Sparkle AV & Stage FX',
                'contact_person' => 'David Miller',
                'email'          => 'contact@sparkle-av.com',
                'phone'          => '+1 (555) 654-3210',
                'website'        => 'https://sparkle-av.com',
                'address'        => '900 Industrial Park Dr',
                'city'           => 'Springfield',
                'price_range'    => '$$$',
                'rating'         => 4,
                'status'         => 'active',
                'notes'          => 'Cold spark machines, intelligent moving head lights, wireless uplighting, and fog.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 7,
                'category_id'    => 2,
                'name'           => 'Metropolitan Skyline Ballroom',
                'contact_person' => 'Christopher Ross',
                'email'          => 'ross@metroballroom.com',
                'phone'          => '+1 (555) 765-4321',
                'website'        => 'https://metroballroom.com',
                'address'        => '500 Central Avenue, 28th Floor',
                'city'           => 'Springfield',
                'price_range'    => '$$$$',
                'rating'         => 5,
                'status'         => 'active',
                'notes'          => 'Panoramic city view, capacity 500, state of the art sound proofing and commercial kitchen.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 8,
                'category_id'    => 1,
                'name'           => 'Artisan Cocktail & Bar Co.',
                'contact_person' => 'Lucas Sterling',
                'email'          => 'cheers@artisancocktails.com',
                'phone'          => '+1 (555) 543-2109',
                'website'        => 'https://artisancocktails.com',
                'address'        => '180 Brewery Lane',
                'city'           => 'Springfield',
                'price_range'    => '$$',
                'rating'         => 5,
                'status'         => 'active',
                'notes'          => 'Bespoke mobile cocktail stations, flair bartenders, curated signature drinks.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        foreach ($providers as $prov) {
            $db->table('providers')->ignore(true)->insert($prov);
        }

        // 3. Events
        $futureDate1 = date('Y-m-d', strtotime('+15 days'));
        $futureDate2 = date('Y-m-d', strtotime('+35 days'));
        $futureDate3 = date('Y-m-d', strtotime('+60 days'));
        $pastDate1   = date('Y-m-d', strtotime('-10 days'));

        $events = [
            [
                'id'             => 1,
                'title'          => "Sarah & Michael's Garden Wedding",
                'event_type'     => 'wedding',
                'client_name'    => 'Sarah Jenkins',
                'client_email'   => 'sarah.j@example.com',
                'client_phone'   => '+1 (555) 111-2233',
                'event_date'     => $futureDate1,
                'start_time'     => '16:00:00',
                'end_time'       => '23:30:00',
                'location_venue' => 'The Grand Glasshouse Gardens',
                'guest_count'    => 180,
                'budget'         => 28500.00,
                'status'         => 'confirmed',
                'description'    => 'Full wedding ceremony followed by cocktail hour and dinner reception in the botanical greenhouse.',
                'notes'          => 'Bride requested pastel florals and champagne greeting for all arriving guests.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 2,
                'title'          => 'TechNova Annual Innovation Summit 2026',
                'event_type'     => 'corporate',
                'client_name'    => 'Robert Vance (TechNova VP)',
                'client_email'   => 'r.vance@technova.io',
                'client_phone'   => '+1 (555) 222-3344',
                'event_date'     => $futureDate2,
                'start_time'     => '09:00:00',
                'end_time'       => '18:00:00',
                'location_venue' => 'Metropolitan Skyline Ballroom',
                'guest_count'    => 350,
                'budget'         => 45000.00,
                'status'         => 'planning',
                'description'    => 'Keynote presentations, breakout panels, lunch catering, and evening networking mixer.',
                'notes'          => 'Requires dual projector screens, livestreaming setup, and badges printing station.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 3,
                'title'          => "Elena's 30th Birthday Euphoria Gala",
                'event_type'     => 'birthday',
                'client_name'    => 'Elena Gomez',
                'client_email'   => 'elena.g@example.com',
                'client_phone'   => '+1 (555) 333-4455',
                'event_date'     => $futureDate3,
                'start_time'     => '20:00:00',
                'end_time'       => '02:00:00',
                'location_venue' => 'Private Villa & Pool Springfield',
                'guest_count'    => 80,
                'budget'         => 12000.00,
                'status'         => 'planning',
                'description'    => 'Neon glow theme with open craft cocktail bar, DJ set, and grazing tables.',
                'notes'          => 'Requires cold spark entrance and photo booth station.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 4,
                'title'          => 'St. Jude Charity Gala Dinner',
                'event_type'     => 'party',
                'client_name'    => 'Katherine Holmes',
                'client_email'   => 'kholmes@stjude-charity.org',
                'client_phone'   => '+1 (555) 444-5566',
                'event_date'     => $pastDate1,
                'start_time'     => '18:30:00',
                'end_time'       => '23:00:00',
                'location_venue' => 'Metropolitan Skyline Ballroom',
                'guest_count'    => 220,
                'budget'         => 32000.00,
                'status'         => 'completed',
                'description'    => 'Silent auction, 3-course seated dinner, live band performance.',
                'notes'          => 'Raised over $120,000 for the community hospital.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        foreach ($events as $evt) {
            $db->table('events')->ignore(true)->insert($evt);
        }

        // 4. Event Providers
        $eventProviders = [
            // Wedding
            [
                'id'                  => 1,
                'event_id'            => 1,
                'provider_id'         => 2, // Venue
                'service_description' => 'Full venue hire (Greenhouse + outdoor ceremony lawn)',
                'agreed_cost'         => 8500.00,
                'deposit_paid'        => 8500.00,
                'payment_status'      => 'paid',
                'contract_status'     => 'signed',
                'notes'               => 'Security deposit and setup schedule agreed.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 2,
                'event_id'            => 1,
                'provider_id'         => 1, // Catering
                'service_description' => '3-course plated dinner for 180 guests + canapes',
                'agreed_cost'         => 11200.00,
                'deposit_paid'        => 5600.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => '50% balance due 7 days prior to wedding date.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 3,
                'event_id'            => 1,
                'provider_id'         => 3, // DJ
                'service_description' => 'Ceremony audio + 6 hour reception DJ set',
                'agreed_cost'         => 1600.00,
                'deposit_paid'        => 500.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'Playlist finalized with couple.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 4,
                'event_id'            => 1,
                'provider_id'         => 4, // Photo
                'service_description' => '10-hour full photo & video coverage + drone',
                'agreed_cost'         => 3400.00,
                'deposit_paid'        => 1700.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'Shot list confirmed.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 5,
                'event_id'            => 1,
                'provider_id'         => 5, // Florist
                'service_description' => 'Ceremony arch, 18 centerpiece tables, bridal party flowers',
                'agreed_cost'         => 2800.00,
                'deposit_paid'        => 1400.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'White roses and eucalyptus accents.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],

            // TechNova Corporate
            [
                'id'                  => 6,
                'event_id'            => 2,
                'provider_id'         => 7, // Metropolitan Ballroom
                'service_description' => 'Ballroom A+B rental with presentation stage',
                'agreed_cost'         => 15000.00,
                'deposit_paid'        => 7500.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'AV tech rehearsal scheduled day before.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 7,
                'event_id'            => 2,
                'provider_id'         => 6, // Sparkle AV
                'service_description' => 'Dual 4K LED walls, multi-mic podium, livestreaming rack',
                'agreed_cost'         => 9500.00,
                'deposit_paid'        => 0.00,
                'payment_status'      => 'pending',
                'contract_status'     => 'sent',
                'notes'               => 'Quote sent awaiting procurement PO approval.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 8,
                'event_id'            => 2,
                'provider_id'         => 1, // Gourmet Haven
                'service_description' => 'Morning coffee service, hot buffet lunch, cocktail hour snacks',
                'agreed_cost'         => 14000.00,
                'deposit_paid'        => 5000.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'Headcount confirmation needed 10 days out.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],

            // Elena Birthday
            [
                'id'                  => 9,
                'event_id'            => 3,
                'provider_id'         => 3, // DJ Pulse
                'service_description' => 'Club DJ setup with custom euphoria remix playlist',
                'agreed_cost'         => 1200.00,
                'deposit_paid'        => 400.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'Outdoor pool area setup.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            [
                'id'                  => 10,
                'event_id'            => 3,
                'provider_id'         => 8, // Artisan Cocktail
                'service_description' => 'Light-up neon bar station, 3 custom cocktails, 2 mixologists',
                'agreed_cost'         => 2600.00,
                'deposit_paid'        => 1000.00,
                'payment_status'      => 'partial',
                'contract_status'     => 'signed',
                'notes'               => 'Alcohol permits and insurance provided.',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ];

        foreach ($eventProviders as $ep) {
            $db->table('event_providers')->ignore(true)->insert($ep);
        }

        // 5. Event Tasks
        $tasks = [
            [
                'id'          => 1,
                'event_id'    => 1,
                'provider_id' => 1,
                'title'       => 'Finalize dietary restrictions and meal headcount with Gourmet Haven',
                'due_date'    => date('Y-m-d', strtotime('+7 days')),
                'priority'    => 'high',
                'status'      => 'in_progress',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'event_id'    => 1,
                'provider_id' => 3,
                'title'       => 'Confirm entrance and first dance song cues with DJ Pulse',
                'due_date'    => date('Y-m-d', strtotime('+10 days')),
                'priority'    => 'medium',
                'status'      => 'completed',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 3,
                'event_id'    => 1,
                'provider_id' => 5,
                'title'       => 'Review floral sample mockups with Bloom & Blossom',
                'due_date'    => date('Y-m-d', strtotime('+5 days')),
                'priority'    => 'medium',
                'status'      => 'completed',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 4,
                'event_id'    => 1,
                'provider_id' => null,
                'title'       => 'Send finalized seating chart to printing',
                'due_date'    => date('Y-m-d', strtotime('+8 days')),
                'priority'    => 'high',
                'status'      => 'todo',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 5,
                'event_id'    => 2,
                'provider_id' => 6,
                'title'       => 'Complete AV connectivity check and wireless mic frequency test',
                'due_date'    => date('Y-m-d', strtotime('+30 days')),
                'priority'    => 'high',
                'status'      => 'todo',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 6,
                'event_id'    => 3,
                'provider_id' => 8,
                'title'       => 'Select the 3 signature birthday cocktails for the menu card',
                'due_date'    => date('Y-m-d', strtotime('+20 days')),
                'priority'    => 'low',
                'status'      => 'todo',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($tasks as $task) {
            $db->table('event_tasks')->ignore(true)->insert($task);
        }
    }
}
