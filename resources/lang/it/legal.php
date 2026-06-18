<?php

return [
    'privacy' => [
        'title' => 'Privacy Policy',
        'last_updated' => 'Ultimo aggiornamento: 31-05-2023',
        'intro' => 'Grazie per aver visitato il nostro sito web. La tua privacy e importante per noi: in questa informativa spieghiamo come raccogliamo, utilizziamo e proteggiamo le informazioni personali durante la navigazione.',
        'sections' => [
            [
                'title' => '1. Informazioni raccolte',
                'body' => [
                    'Durante la visita al sito possiamo raccogliere informazioni fornite volontariamente, come nome, email o telefono, quando compili moduli o crei un account.',
                    "Possiamo raccogliere anche informazioni di navigazione, come indirizzo IP, browser utilizzato, pagine visitate, data e ora dell'accesso.",
                ],
            ],
            [
                'title' => '2. Utilizzo delle informazioni',
                'body' => [
                    "Usiamo le informazioni per fornire i servizi richiesti, rispondere alle domande, migliorare l'esperienza sul sito e proteggere sistemi e utenti.",
                    'Possiamo usare i dati per comunicazioni relative ai servizi, aggiornamenti tecnici o messaggi amministrativi.',
                ],
            ],
            [
                'title' => '3. Divulgazione delle informazioni',
                'body' => [
                    'Non vendiamo ne trasferiamo informazioni personali a terzi senza consenso, salvo obblighi di legge o necessita tecniche legate alla fornitura del servizio.',
                    'I fornitori che collaborano alla gestione del sito sono tenuti a trattare i dati solo per le attivita richieste.',
                ],
            ],
            [
                'title' => '4. Sicurezza delle informazioni',
                'body' => [
                    'Adottiamo misure ragionevoli per proteggere i dati personali. Nessun sistema di trasmissione o conservazione puo tuttavia essere considerato completamente sicuro.',
                ],
            ],
            [
                'title' => '5. Collegamenti esterni',
                'body' => [
                    'Il sito puo contenere collegamenti a siti di terze parti. Ti invitiamo a consultare le rispettive informative, perche non siamo responsabili delle loro pratiche privacy.',
                ],
            ],
            [
                'title' => '6. Modifiche alla Privacy Policy',
                'body' => [
                    'Ci riserviamo il diritto di modificare questa informativa. Le modifiche saranno efficaci dalla pubblicazione su questa pagina.',
                ],
            ],
            [
                'title' => '7. Contatti',
                'body' => [
                    'Per domande sulla Privacy Policy o sul trattamento dei dati personali puoi usare i riferimenti presenti nella sezione contatti del sito.',
                ],
            ],
        ],
    ],

    'cookie' => [
        'title' => 'Utilizzo dei cookie',
        'intro' => [
            'Questo sito utilizza cookie tecnici per garantire il corretto funzionamento dei servizi.',
            "Per usare alcune funzionalita puo essere necessario consentire l'utilizzo dei cookie tecnici.",
            'Se i cookie vengono bloccati completamente, alcune funzioni del sito potrebbero non essere disponibili.',
        ],
        'what_are_title' => 'Cosa sono i cookie',
        'what_are_text' => 'I cookie sono piccoli file salvati sul dispositivo durante la navigazione. Possono conservare preferenze, sessioni e informazioni utili al funzionamento del sito.',
        'technical_title' => 'Cookie tecnici',
        'technical_text' => 'Sul sito sono utilizzati cookie tecnici necessari alla sicurezza e alla gestione della sessione.',
        'technical_cookies' => [
            'XSRF-TOKEN' => 'Protegge le richieste da attacchi Cross-Site Request Forgery (CSRF).',
            'laravel_session' => "Gestisce la sessione corrente dell'utente.",
            'remember_web_{hash}' => "Ricorda le credenziali di accesso quando l'utente sceglie la funzione di permanenza login.",
        ],
        'profiling_title' => 'Cookie di profilazione',
        'profiling_text' => 'Questo sito non utilizza cookie di profilazione.',
        'management_title' => 'Come gestire i cookie',
        'management_text' => 'Puoi gestire, disabilitare o cancellare i cookie dalle impostazioni del browser. La disattivazione dei cookie tecnici puo limitare alcune funzioni del sito.',
        'rights_title' => 'Diritti degli utenti',
        'rights' => [
            'access' => 'Accesso ai dati personali trattati.',
            'rectification' => 'Rettifica o integrazione dei dati inesatti o incompleti.',
            'erasure' => 'Cancellazione dei dati quando ne ricorrono i presupposti.',
            'restriction' => 'Limitazione del trattamento in determinate circostanze.',
            'objection' => 'Opposizione al trattamento per motivi legittimi.',
            'portability' => 'Portabilita dei dati forniti, quando tecnicamente possibile.',
            'withdrawal' => 'Revoca del consenso eventualmente prestato.',
        ],
        'contact_text' => 'Per esercitare i diritti o ottenere ulteriori informazioni, puoi usare i riferimenti presenti nella sezione contatti del sito.',
    ],
];
