<?php

// Icons for the sidebar
return [
    'iconMap' => [
        'date_range' => '/public/resources/icons/date_range.png',
        'assignment' => '/public/resources/icons/assignment.png',
        'assignment_ind' => '/public/resources/icons/assignment_ind.png',
        'logout' => '/public/resources/icons/logout.png',
        'chevron_right' => '/public/resources/icons/chevron_right.png',
        'tv' => '/public/resources/icons/tv.png',
        'ad' => '/public/resources/icons/ad.png',
        'grp' => '/public/resources/icons/grp.png',
    ],

    // Public routes are routes that are accessible to everyone, even if they are not logged in
    'publicRoutes' => [
        '/login',
        '/register',
        // iskljuciti u produkciji
        '/logout'

    ],

    // Protected routes are routes that are only accessible to logged in users
    'routes' => [
        '/dashboard' => [
            'label' => 'Dashboard', // Label for the sidebar
            'icon' => 'date_range', // Icon for the sidebar
            'roles' => [\Delight\Auth\Role::CONSUMER], // Consumers can access this route
            'view' => __DIR__ . '/../Views/Protected/Dashboard.php', // Path to the view file
        ],

        '/developers' => [
            'label' => 'Developers',
            'icon' => 'assignment',
            'roles' => [\Delight\Auth\Role::DEVELOPER], // Developers can access this route
            'view' => __DIR__ . '/../Views/Protected/Developers.php',
        ],

        '/administrators' => [
            'label' => 'Administrators',
            'icon' => 'assignment_ind',
            'roles' => [\Delight\Auth\Role::ADMIN], // Only administrators can access this route
            'view' => __DIR__ . '/../Views/Protected/Administrators.php',
        ],

        '/administrators/upravljanje' => [
            'label' => 'Upravljanje Uredjajima',
            'icon' => 'assignment_ind',
            'roles' => [\Delight\Auth\Role::ADMIN], // Only administrators can access this route
            'view' => __DIR__ . '/../Views/Protected/Upravljanje.uredjajima.php',
            'hidden' => true, // Mark as hidden for sidebar, here we hide the link from the sidebar
        ],

        '/administrators/registracija' => [
            'label' => 'Registracija Uredjaja',
            'icon' => 'assignment_ind',
            'roles' => [\Delight\Auth\Role::ADMIN],
            'view' => __DIR__ . '/../Views/Protected/Registracija.uredjaja.php',
            'hidden' => true, // Mark as hidden for sidebar, here we hide the link from the sidebar
        ],

        '/administrators/konfiguracija' => [
            'label' => 'Konfiguracija Korisnika',
            'icon' => 'assignment_ind',
            'roles' => [\Delight\Auth\Role::ADMIN],
            'view' => __DIR__ . '/../Views/Protected/Konfiguracija.korisnika.php',
            'hidden' => true, // Mark as hidden for sidebar, here we hide the link from the sidebar
        ],

        '/korisnici' => [
            'label' => 'Korisnici',
            'icon' => 'grp',
            'roles' => [\Delight\Auth\Role::CONSUMER], // Only editors can access this route
            'view' => __DIR__ . '/../Views/Protected/Korisnici.php',
            'sub' => [ // Dodajemo podkategorije
                '/korisnici/reklame' => [
                    'label' => 'Pregled po reklami',
                    'icon' => 'ad',
                    'view' => __DIR__ . '/../Views/Protected/Pregled.po.reklamama.php',
                ],
                '/korisnici/displeji' => [
                    'label' => 'Pregled po displejima',
                    'icon' => 'tv',
                    'view' => __DIR__ . '/../Views/Protected/Pregled.po.displejima.php',
                ],
            ],
        ],


        '/managers' => [
            'label' => 'Managers',
            'icon' => 'assignment_ind',
            'roles' => [Delight\Auth\Role::ADMIN], // Only administrators can access this route
            'view' => __DIR__ . '/../Views/Protected/Managers.php',
        ],

        '/nova-reklama/upload' => [
            'label' => 'Upload',
            'icon' => 'assignment_ind',
            'roles' => [\Delight\Auth\Role::ADMIN], // Only administrators can access this route
            'hidden' => true, // Mark as hidden for sidebar, here we hide the link from the sidebar
            'view' => __DIR__ . '/../Views/Protected/Upload.php',
        ],
    ],
];
