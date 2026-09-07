<?php

declare(strict_types=1);

return [
    'seo' => [
        'title' => 'Joy Foundation Nepal — Share The Joy of Giving',
        'description' => 'For nearly 30 years, JOY has been working to create positive change through health, education, community development, and humanitarian assistance.',
    ],

    'nav' => [
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'About Us', 'href' => '#'],
        ['label' => 'Programs', 'href' => '#'],
        ['label' => 'Contact Us', 'href' => '#'],
    ],

    'social' => [
        ['label' => 'Facebook', 'icon' => 'icon-facebook.svg', 'url' => '#'],
        ['label' => 'Instagram', 'icon' => 'icon-instagram.svg', 'url' => '#'],
        ['label' => 'X', 'icon' => 'icon-x.svg', 'url' => '#'],
        ['label' => 'LinkedIn', 'icon' => 'icon-linkedin.svg', 'url' => '#'],
        ['label' => 'YouTube', 'icon' => 'icon-youtube.svg', 'url' => '#'],
    ],

    'hero' => [
        'background' => 'images/site/hero-bg.png',
        'heading_prefix' => 'Share The ',
        'heading_highlight' => 'Joy',
        'heading_suffix' => ' of Giving',
    ],

    'intro' => [
        'text' => 'For nearly 30 years, JOY has been working to create positive change through health, education, community development, and humanitarian assistance.',
    ],

    'story' => [
        'photo' => 'images/site/story-photo.png',
        'photo_polaroid' => 'images/site/story-photo-polaroid.png',
        'established_year' => '1996',
        'eyebrow' => 'Our Story',
        'heading_prefix' => 'Nearly thirty years of ',
        'heading_highlight' => 'showing up',
        'paragraphs' => [
            'It started with a simple belief: that ordinary people, when they come together, can do extraordinary things.',
            'In 1996, a small group of compassionate individuals decided that the gaps in healthcare, education, and community support across Nepal were too wide to ignore. So they got to work.',
            "Since then, JOY Foundation Nepal has grown into something we're genuinely proud of — not because of the size of our organization, but because of the depth of our impact. Through hundreds of healthcare camps, years of education support, and emergency relief when communities needed it most, we've had the privilege of being there for people during the moments that mattered.",
            "We didn't do it alone. Every volunteer who gave their weekend, every donor who believed in our mission, every partner who stood beside us — you're part of this story too.",
        ],
        'cta_label' => 'Learn More About Us',
    ],

    'mission_section' => [
        'eyebrow' => 'Mission & Vision',
        'heading_prefix' => 'Why we ',
        'heading_highlight' => 'do',
        'heading_suffix' => ' what we do.',
        'intro' => 'One long-standing promise — to keep showing up for the people and places that have been overlooked for too long.',

        'mission' => [
            'label' => 'Mission',
            'segments' => [
                ['text' => 'To stand beside underserved communities across', 'highlight' => false],
                ['break' => true],
                ['text' => 'Nepal — bringing ', 'highlight' => false],
                ['text' => 'healthcare, humanitarian support,', 'highlight' => true],
                ['break' => true],
                ['text' => 'education,', 'highlight' => true],
                ['text' => ' and ', 'highlight' => false],
                ['text' => 'opportunity', 'highlight' => true],
                ['text' => " to those who've had too little", 'highlight' => false],
                ['break' => true],
                ['text' => 'of all four.', 'highlight' => false],
            ],
        ],

        'vision' => [
            'label' => 'Vision',
            'segments' => [
                ['text' => "A Nepal where no one is left behind because of where they were born, what they can afford, or what's standing in their way.", 'highlight' => false],
            ],
        ],

        'guides' => [
            'label' => 'What Guides Us',
            'segments' => [
                ['text' => 'We hold ourselves to ', 'highlight' => false],
                ['text' => 'compassion', 'highlight' => true],
                ['text' => ', ', 'highlight' => false],
                ['text' => 'transparency', 'highlight' => true],
                ['text' => ',', 'highlight' => false],
                ['break' => true],
                ['text' => 'accountability', 'highlight' => true],
                ['text' => ', ', 'highlight' => false],
                ['text' => 'integrity', 'highlight' => true],
                ['text' => ', and ', 'highlight' => false],
                ['text' => 'community', 'highlight' => true],
                ['text' => '. Not as', 'highlight' => false],
                ['break' => true],
                ['text' => 'words on a wall, but as the way we show up —', 'highlight' => false],
                ['break' => true],
                ['text' => "when no one's watching, and especially when it's", 'highlight' => false],
                ['break' => true],
                ['text' => 'hard.', 'highlight' => false],
            ],
        ],
    ],

    'impact' => [
        'eyebrow' => 'Impact In Numbers',
        'heading' => [
            'line1' => 'Nearly Three Decades',
            'line2_prefix' => 'of ',
            'line2_highlight' => 'Showing Up.',
        ],
        'note' => ['Every number here is a person.', "We don't forget that."],

        'headline_stat' => [
            'ghost' => '62K',
            'value' => '62,542',
            'caption' => ['Eye care treatments delivered', 'since 1996'],
        ],

        'stats' => [
            ['value' => '6,293', 'caption' => ['Free cataract', 'surgeries'], 'color' => 'navy', 'weight' => 'semibold'],
            ['value' => '66', 'caption' => ['Health camps', 'conducted'], 'color' => 'primary', 'weight' => 'light'],
            ['value' => '100', 'caption' => ['Oxygen concentrators', 'supported'], 'color' => 'primary', 'weight' => 'light'],
            ['value' => '30+', 'caption' => ['Years of', 'community service'], 'color' => 'navy', 'weight' => 'semibold'],
        ],

        'dark_stats' => [
            ['value' => '12 + 10', 'weight' => 'light', 'caption' => 'Student scholarships', 'sub_caption' => '12 quarterly · 10 yearly'],
            ['value' => '30+', 'weight' => 'semibold', 'caption' => 'Organisations supported', 'sub_caption' => 'Across Nepal'],
            ['value' => '29', 'weight' => 'light', 'caption' => 'Districts reached', 'sub_caption' => 'Across Nepal'],
            ['value' => 'Since 1996', 'weight' => 'semibold', 'caption' => 'Walking alongside communities', 'sub_caption' => 'Nearly three decades'],
        ],
    ],

    'programs' => [
        'eyebrow' => 'Our Programs',
        'heading_prefix' => 'Where your ',
        'heading_highlight' => 'support',
        'heading_suffix' => ' goes.',
        'subtext' => 'Nearly 30 years across healthcare, education, relief, and community.',

        'items' => [
            [
                'photo' => 'program-photo-healthcare.png',
                'icon' => 'program-icon-healthcare.svg',
                'category' => 'Healthcare',
                'title' => 'Free Medical & Health Camps',
                'description' => 'Many families in Nepal go months — sometimes years — without seeing a doctor. We bring free medical, dental, and health services directly to underserved communities.',
            ],
            [
                'photo' => 'program-photo-eyecare.png',
                'icon' => 'program-icon-eyecare.svg',
                'category' => 'Eye Care',
                'title' => 'Eye Care Initiatives',
                'description' => 'Most vision loss is preventable if caught in time. Through screening camps and follow-up care, we help people keep their sight and their independence.',
            ],
            [
                'photo' => 'program-photo-education.png',
                'icon' => 'program-icon-education.svg',
                'category' => 'Education',
                'title' => 'Education Support',
                'description' => 'Every child deserves a fair shot at learning. We provide materials, learning support, and encouragement to children who need a little more.',
            ],
            [
                'photo' => 'program-photo-relief.png',
                'icon' => 'program-icon-relief.svg',
                'category' => 'Relief',
                'title' => 'Disaster Relief & Humanitarian',
                'description' => 'When earthquakes, floods, or emergencies strike, we mobilize quickly — delivering relief supplies and staying present through the long recovery.',
            ],
            [
                'photo' => 'program-photo-community.png',
                'icon' => 'program-icon-community.svg',
                'category' => 'Community',
                'title' => 'Community Development',
                'description' => 'Lasting change grows from within. We work alongside communities — not above them — supporting initiatives they can own and sustain.',
            ],
        ],
    ],

    'long_term_community' => [
        'eyebrow' => 'Long-Term Community Support',
        'heading' => [
            'line1' => 'Long-term initiatives.',
            'line2_prefix' => '',
            'line2_highlight' => 'Sustained',
            'line2_suffix' => ' impact.',
        ],
        'intro' => 'For years, JOY Foundation Nepal has remained committed to supporting vulnerable communities through continuous partnerships, healthcare access, and welfare programs that create long-term change.',

        'initiatives' => [
            [
                'photo' => 'initiative-photo-1.jpg',
                'badge' => 'Since 2010',
                'category' => 'Child Welfare',
                'title' => ['Child Welfare &', 'Residential Support'],
                'location' => 'Community Child Rescue Centre (CCRC) · Nakhipot, Lalitpur',
                'description' => 'Since 2010, JOY Foundation Nepal has provided ongoing quarterly financial support to help cover essential living costs for children in residential care.',
                'tags' => ['Housing', 'Living expenses', 'Child care & protection', 'Safe residential support'],
                'impact' => 'Supporting continuity of care and stable living conditions.',
                'partners' => [],
            ],
            [
                'photo' => 'initiative-photo-2.jpg',
                'badge' => 'Since 2018',
                'category' => 'Community Inclusion',
                'title' => ['Accessibility &', 'Inclusive Communities'],
                'location' => 'National Association of the Deaf and Hard of Hearing (NADH) · Ratopul, Kathmandu',
                'description' => 'Since 2018, continuous quarterly support for interpreter services — improving accessibility and communication for deaf and hard-of-hearing communities across Nepal.',
                'tags' => ['Interpreter support', 'Communication access', 'Inclusion initiatives', 'Community empowerment'],
                'impact' => 'Helping improve accessibility for deaf and hard-of-hearing communities.',
                'partners' => [],
            ],
            [
                'photo' => 'initiative-photo-3.jpg',
                'badge' => 'Since 2024',
                'category' => 'Healthcare',
                'title' => ['Community Eye', 'Health Access'],
                'location' => 'Tilganga Community Eye Centre · Tarkeshwor, Kathmandu',
                'description' => 'Implemented in collaboration with Mountain People, supporting a community eye center through long-term infrastructure. Land and building provided for 10 years to enable affordable, sustainable eye care.',
                'tags' => ['Community eye care', 'Free eye camps', 'Surgical outreach', 'Accessible treatment'],
                'impact' => 'Affordable eye care delivery for underserved communities.',
                'partners' => ['Tilganga Eye Institute', 'The Fred Hollows Foundation'],
            ],
            [
                'photo' => 'initiative-photo-4.jpg',
                'badge' => 'Current Initiative',
                'category' => 'Nutrition & Child Health',
                'title' => ['Nutrition Support for', 'Vulnerable Children'],
                'location' => '61 children across residential homes · Kathmandu Valley',
                'description' => 'Supporting 61 children through nutrition programs, oral health awareness, and ongoing health monitoring — sustained through active partnerships with Rotary International and Taiwan Oral Care Association.',
                'tags' => ['Nutrition support', 'Oral health awareness', 'Health monitoring', 'Child welfare'],
                'impact' => 'Improved health outcomes for 61 vulnerable children.',
                'partners' => ['Taiwan Oral Care Association', 'Rotary International District 3470'],
            ],
        ],
    ],

    'achievements' => [
        'eyebrow' => 'Relief, Recovery & Resilience',
        'heading_prefix' => 'Our ',
        'heading_highlight' => 'Major',
        'heading_line2' => 'Achievements.',
        'subtext' => 'Supporting communities through crisis and standing beside them beyond recovery.',

        'items' => [
            [
                'photo' => 'achievement-photo-eyecamp.jpg',
                'category' => 'Child Welfare',
                'date' => 'Since 2010',
                'title' => 'Free Eye Camp',
                'location' => 'Community Child Rescue Centre (CCRC) · Nakhipot, Lalitpur',
                'stat_value' => '62,542',
                'stat_label' => 'Eye Care Treatments',
                'stat_theme' => 'primary',
                'description' => 'Since 2010, JOY Foundation Nepal has provided ongoing quarterly financial support to cover essential living costs for children in residential care.',
                'tags' => ['Housing', 'Living expenses', 'Child care & protection', 'Safe residential support'],
                'impact' => 'Supporting continuity of care and stable living conditions.',
                'partners' => [],
            ],
            [
                'photo' => 'achievement-photo-yngupta.jpg',
                'category' => 'Community Inclusion',
                'date' => 'Since 2018',
                'title' => 'YN Gupta Village',
                'location' => 'National Association of the Deaf and Hard of Hearing (NADH) · Ratopul, Kathmandu',
                'stat_value' => '200+',
                'stat_label' => 'Families Supported',
                'stat_theme' => 'navy',
                'description' => 'Since 2018, continuous quarterly support for interpreter services — improving accessibility and communication for deaf and hard-of-hearing communities across Nepal.',
                'tags' => ['Interpreter support', 'Communication access', 'Inclusion initiatives', 'Community empowerment'],
                'impact' => 'Helping improve accessibility for deaf and hard-of-hearing communities.',
                'partners' => [],
            ],
            [
                'photo' => 'achievement-photo-earthquake.jpg',
                'category' => 'Healthcare',
                'date' => '2015',
                'title' => 'Earthquake Relief',
                'location' => 'Tilganga Community Eye Centre · Tarkeshwor, Kathmandu',
                'stat_value' => '29',
                'stat_label' => 'Districts Reached',
                'stat_theme' => 'primary',
                'description' => 'Implemented in collaboration with Mountain People, supporting a community eye center through long-term infrastructure. Land and building provided for 10 years to enable affordable, sustainable eye care.',
                'tags' => ['Community eye care', 'Free eye camps', 'Surgical outreach', 'Accessible treatment'],
                'impact' => 'Affordable eye care delivery for underserved communities.',
                'partners' => ['Tilganga Eye Institute', 'The Fred Hollows Foundation'],
            ],
            [
                'photo' => 'achievement-photo-covid.jpg',
                'category' => 'Nutrition & Child Health',
                'date' => '2021',
                'title' => 'Covid-19 Support',
                'location' => '61 children across residential homes · Kathmandu Valley',
                'stat_value' => '100',
                'stat_label' => 'Oxygen Concentrators',
                'stat_theme' => 'navy',
                'description' => 'Supporting 61 children through nutrition programs, oral health awareness, and ongoing health monitoring — sustained through active partnerships with Rotary International and Taiwan Oral Care Association.',
                'tags' => ['Nutrition support', 'Oral health awareness', 'Health monitoring', 'Child welfare'],
                'impact' => 'Improved health outcomes for 61 vulnerable children.',
                'partners' => ['Taiwan Oral Care Association', 'Rotary International District 3470'],
            ],
        ],
    ],

    'partners' => [
        'eyebrow' => 'Our Partners',
        'heading_prefix' => "We don't do it ",
        'heading_highlight' => 'alone',
        'note' => ['Every volunteer, donor, and partner who', 'stood beside us is part of this story too.'],

        'items' => [
            ['logo' => 'partner-nafa.png', 'name' => 'NAFA'],
            ['logo' => 'partner-gaia.png', 'name' => 'Gaia'],
            ['logo' => 'partner-lingjiou.png', 'name' => 'Ling Jiou Mountain Buddhist Society'],
            ['logo' => 'partner-ekekpaila.png', 'name' => 'Ek Ek Paila'],
            ['logo' => 'partner-taiwanoralcare.png', 'name' => 'Taiwan Oral Care Association'],
            ['logo' => 'partner-taiwanhealthcorps.png', 'name' => 'Taiwan Health Corps'],
            ['logo' => 'partner-taipeimedical.png', 'name' => 'Taipei Medical University'],
            ['logo' => 'partner-natldefense.png', 'name' => 'National Defense Medical Center'],
            ['logo' => 'partner-councilchs.png', 'name' => 'Council of CHS'],
            ['logo' => 'partner-tilganga.png', 'name' => 'Tilganga Institute'],
        ],
    ],

    'stories' => [
        'eyebrow' => 'Stories Of Hope',
        'heading_prefix' => 'The people ',
        'heading_highlight' => 'behind',
        'heading_suffix' => ' the numbers.',
        'subtext' => 'Stats tell you scale. Stories tell you truth.',

        'items' => [
            [
                'photo' => 'story-photo-1.jpg',
                'category' => 'Beneficiary · Eye Care',
                'headline' => "She could finally see her grandchildren's faces.",
                'paragraphs' => [
                    "She hadn't seen clearly in years. Cataracts had been stealing her world slowly, quietly — the way these things do in places where no doctor ever comes.",
                    'One camp. One morning. Everything changed. She said she cried when she first saw them clearly. Not from pain — from relief.',
                ],
                'location' => 'Dolakha District',
            ],
            [
                'photo' => 'story-photo-2.jpg',
                'category' => 'Volunteer · Healthcare',
                'headline' => "He came for a weekend. He's been back every season since.",
                'paragraphs' => [
                    "He drove four hours to help at a health camp three years ago. He wasn't a doctor — just someone who wanted to do something useful. He's been back every season since, and brought twelve others with him.",
                ],
                'location' => 'Kathmandu Valley',
            ],
            [
                'photo' => 'story-photo-3.jpg',
                'category' => 'Community · Education',
                'headline' => 'She wants to be a doctor. We think she will be.',
                'paragraphs' => [
                    "Her family couldn't afford school supplies. The programme stepped in quietly — no ceremony, no fuss. She's in grade eight now, and when asked what she wants to be, she didn't hesitate.",
                ],
                'location' => 'Chitwan',
            ],
        ],

        'closing' => [
            ['text' => 'Shared with ', 'highlight' => false],
            ['text' => 'gratitude', 'highlight' => true],
            ['text' => ', with ', 'highlight' => false],
            ['text' => 'permission', 'highlight' => true],
            ['text' => ', and with deep ', 'highlight' => false],
            ['text' => 'respect', 'highlight' => true],
            ['text' => ' for the people who lived them.', 'highlight' => false],
        ],
    ],

    'events' => [
        'eyebrow' => 'Events & Activities',
        'heading_prefix' => 'Come ',
        'heading_highlight' => 'Join',
        'heading_suffix' => ' Us.',
        'subtext' => 'From healthcare camps and volunteer drives to community gatherings — our events are where the mission becomes movement. Everyone is welcome.',

        'concluded_label' => 'Recently Concluded',
        'featured' => [
            'photos' => ['event-featured-1.jpg', 'event-featured-2.jpg'],
            'title' => 'Free Healthcare & Eye Camp — Manang',
            'date' => 'May 15–16, 2026',
            'description' => 'Over two days, our team and volunteers came together to serve more than 600 community members with free healthcare and eye care services.',
            'stats' => ['600+ served', '80+ volunteers', '50+ services'],
        ],

        'gallery' => [
            'event-strip-1.jpg',
            'event-strip-2.jpg',
            'event-strip-3.jpg',
            'event-strip-4.jpg',
            'event-strip-5.jpg',
        ],

        'upcoming_label' => 'Upcoming Events',
        'upcoming' => [
            ['month' => 'Aug', 'day' => '16', 'category' => 'Health Camp', 'title' => 'Community Health Camp', 'location' => 'Kathmandu'],
            ['month' => 'Sep', 'day' => '20', 'category' => 'Volunteer', 'title' => 'Volunteer Outreach Program', 'location' => 'Pokhara'],
            ['month' => 'Oct', 'day' => '18', 'category' => 'Meeting', 'title' => 'Annual General Meeting', 'location' => 'Kathmandu'],
            ['month' => 'Nov', 'day' => '15', 'category' => 'Education', 'title' => 'Education Support Initiative', 'location' => 'Chitwan'],
        ],

        'cta_label' => 'View All Events',
    ],
];
