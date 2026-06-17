<?php

return [
    'privacy' => [
        'title' => 'Privacy Policy',
        'last_updated' => 'Last updated: 31-05-2023',
        'intro' => 'Thank you for visiting this website. Your privacy matters: this policy explains how personal information is collected, used, and protected while you browse the site.',
        'sections' => [
            [
                'title' => '1. Information collected',
                'body' => [
                    'During your visit we may collect information you provide voluntarily, such as name, email, or phone number, when you submit forms or create an account.',
                    'We may also collect navigation data such as IP address, browser type, visited pages, and access date and time.',
                ],
            ],
            [
                'title' => '2. Use of information',
                'body' => [
                    'We use information to provide requested services, answer questions, improve the site experience, and protect systems and users.',
                    'We may use data for service communications, technical updates, and administrative messages.',
                ],
            ],
            [
                'title' => '3. Disclosure of information',
                'body' => [
                    'We do not sell or transfer personal information to third parties without consent, except for legal obligations or technical needs related to providing the service.',
                    'Providers who help operate the site must process data only for the requested activities.',
                ],
            ],
            [
                'title' => '4. Information security',
                'body' => [
                    'We adopt reasonable measures to protect personal data. No transmission or storage system can be considered completely secure.',
                ],
            ],
            [
                'title' => '5. External links',
                'body' => [
                    'The site may contain links to third-party websites. Please review their policies, because we are not responsible for their privacy practices.',
                ],
            ],
            [
                'title' => '6. Privacy Policy changes',
                'body' => [
                    'We reserve the right to update this policy. Changes are effective when published on this page.',
                ],
            ],
            [
                'title' => '7. Contact',
                'body' => [
                    'For questions about this Privacy Policy or personal data processing, use the contact details available on the site.',
                ],
            ],
        ],
    ],

    'cookie' => [
        'title' => 'Cookie usage',
        'intro' => [
            'This site uses technical cookies to keep the services working correctly.',
            'Some features may require technical cookies to be enabled.',
            'If cookies are completely blocked, some site functions may not be available.',
        ],
        'what_are_title' => 'What cookies are',
        'what_are_text' => 'Cookies are small files stored on your device while browsing. They can keep preferences, sessions, and information useful for site operation.',
        'technical_title' => 'Technical cookies',
        'technical_text' => 'The site uses technical cookies required for security and session management.',
        'technical_cookies' => [
            'XSRF-TOKEN' => 'Protects requests from Cross-Site Request Forgery (CSRF) attacks.',
            'laravel_session' => 'Manages the current user session.',
            'remember_web_{hash}' => 'Remembers login credentials when the user chooses the remember-login option.',
        ],
        'profiling_title' => 'Profiling cookies',
        'profiling_text' => 'This site does not use profiling cookies.',
        'management_title' => 'How to manage cookies',
        'management_text' => 'You can manage, disable, or delete cookies from your browser settings. Disabling technical cookies may limit some site features.',
        'rights_title' => 'User rights',
        'rights' => [
            'access' => 'Access to processed personal data.',
            'rectification' => 'Correction or completion of inaccurate or incomplete data.',
            'erasure' => 'Deletion of data when the relevant conditions apply.',
            'restriction' => 'Restriction of processing in specific circumstances.',
            'objection' => 'Objection to processing for legitimate reasons.',
            'portability' => 'Portability of provided data, where technically possible.',
            'withdrawal' => 'Withdrawal of consent where consent was given.',
        ],
        'contact_text' => 'To exercise your rights or request more information, use the contact details available on the site.',
    ],
];
