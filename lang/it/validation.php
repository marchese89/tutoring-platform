<?php

return [

    'accepted' => 'Il campo :attribute deve essere accettato.',
    'accepted_if' => 'Il campo :attribute deve essere accettato quando :other è :value.',
    'active_url' => 'Il campo :attribute deve essere un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva a :date.',
    'after_or_equal' => 'Il campo :attribute deve essere una data successiva o uguale a :date.',

    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, trattini e underscore.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',

    'array' => 'Il campo :attribute deve essere un array.',
    'ascii' => 'Il campo :attribute può contenere solo caratteri alfanumerici e simboli ASCII.',

    'before' => 'Il campo :attribute deve essere una data precedente a :date.',
    'before_or_equal' => 'Il campo :attribute deve essere una data precedente o uguale a :date.',

    'between' => [
        'array' => 'Il campo :attribute deve avere tra :min e :max elementi.',
        'file' => 'Il campo :attribute deve essere tra :min e :max KB.',
        'numeric' => 'Il campo :attribute deve essere compreso tra :min e :max.',
        'string' => 'Il campo :attribute deve contenere tra :min e :max caratteri.',
    ],

    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'can' => 'Il campo :attribute contiene un valore non autorizzato.',
    'confirmed' => 'La conferma del campo :attribute non corrisponde.',
    'current_password' => 'La password non è corretta.',

    'date' => 'Il campo :attribute deve essere una data valida.',
    'date_equals' => 'Il campo :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il campo :attribute non corrisponde al formato :format.',

    'decimal' => 'Il campo :attribute deve avere :decimal cifre decimali.',
    'declined' => 'Il campo :attribute deve essere rifiutato.',
    'declined_if' => 'Il campo :attribute deve essere rifiutato quando :other è :value.',

    'different' => 'Il campo :attribute e :other devono essere diversi.',

    'digits' => 'Il campo :attribute deve contenere :digits cifre.',
    'digits_between' => 'Il campo :attribute deve contenere tra :min e :max cifre.',

    'dimensions' => 'Il campo :attribute ha dimensioni immagine non valide.',
    'distinct' => 'Il campo :attribute contiene un valore duplicato.',

    'doesnt_end_with' => 'Il campo :attribute non può terminare con: :values.',
    'doesnt_start_with' => 'Il campo :attribute non può iniziare con: :values.',

    'email' => 'Inserire un indirizzo email valido.',
    'ends_with' => 'Il campo :attribute deve terminare con: :values.',

    'enum' => 'Il valore selezionato per :attribute non è valido.',
    'exists' => 'Il valore selezionato per :attribute non è valido.',

    'file' => 'Il campo :attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve contenere un valore.',

    'gt' => [
        'array' => 'Il campo :attribute deve contenere più di :value elementi.',
        'file' => 'Il campo :attribute deve essere maggiore di :value KB.',
        'numeric' => 'Il campo :attribute deve essere maggiore di :value.',
        'string' => 'Il campo :attribute deve essere più lungo di :value caratteri.',
    ],

    'gte' => [
        'array' => 'Il campo :attribute deve contenere almeno :value elementi.',
        'file' => 'Il campo :attribute deve essere maggiore o uguale a :value KB.',
        'numeric' => 'Il campo :attribute deve essere maggiore o uguale a :value.',
        'string' => 'Il campo :attribute deve essere almeno :value caratteri.',
    ],

    'image' => 'Il campo :attribute deve essere un\'immagine.',
    'in' => 'Il valore selezionato per :attribute non è valido.',
    'integer' => 'Il campo :attribute deve essere un numero intero.',

    'ip' => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4' => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',

    'json' => 'Il campo :attribute deve essere una stringa JSON valida.',

    'lowercase' => 'Il campo :attribute deve essere in minuscolo.',

    'lt' => [
        'array' => 'Il campo :attribute deve contenere meno di :value elementi.',
        'file' => 'Il campo :attribute deve essere minore di :value KB.',
        'numeric' => 'Il campo :attribute deve essere minore di :value.',
        'string' => 'Il campo :attribute deve essere più corto di :value caratteri.',
    ],

    'lte' => [
        'array' => 'Il campo :attribute non deve contenere più di :value elementi.',
        'file' => 'Il campo :attribute deve essere minore o uguale a :value KB.',
        'numeric' => 'Il campo :attribute deve essere minore o uguale a :value.',
        'string' => 'Il campo :attribute deve essere al massimo :value caratteri.',
    ],

    'max' => [
        'array' => 'Il campo :attribute non può avere più di :max elementi.',
        'file' => 'Il campo :attribute non può superare :max KB.',
        'numeric' => 'Il campo :attribute non può essere maggiore di :max.',
        'string' => 'Il campo :attribute non può superare :max caratteri.',
    ],

    'min' => [
        'array' => 'Il campo :attribute deve avere almeno :min elementi.',
        'file' => 'Il campo :attribute deve essere almeno :min KB.',
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'string' => 'Il campo :attribute deve contenere almeno :min caratteri.',
    ],

    'numeric' => 'Il campo :attribute deve essere un numero.',

    'regex' => 'Il formato del campo :attribute non è valido.',

    'required' => 'Il campo :attribute è obbligatorio.',

    'same' => 'Il campo :attribute deve corrispondere a :other.',

    'size' => [
        'array' => 'Il campo :attribute deve contenere :size elementi.',
        'file' => 'Il campo :attribute deve essere di :size KB.',
        'numeric' => 'Il campo :attribute deve essere :size.',
        'string' => 'Il campo :attribute deve essere lungo :size caratteri.',
    ],

    'string' => 'Il campo :attribute deve essere una stringa.',

    'timezone' => 'Il campo :attribute deve essere un fuso orario valido.',

    'unique' => ':attribute è già in uso.',
    'uploaded' => 'Il caricamento del campo :attribute non è riuscito.',

    'url' => 'Il campo :attribute deve essere un URL valido.',

    'uuid' => 'Il campo :attribute deve essere un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Attributi personalizzati
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'first_name' => 'nome',
        'last_name' => 'cognome',

        'email' => 'email',
        'email_confirmation' => 'conferma email',

        'password' => 'password',
        'password_confirmation' => 'conferma password',

        'tax_code' => 'codice fiscale',

        'address' => 'indirizzo',
        'house_number' => 'numero civico',
        'city' => 'città',
        'province' => 'provincia',
        'postal_code' => 'CAP',
        'inputNome' => 'nome',
        'inputCognome' => 'cognome',
        'inputIndirizzo' => 'indirizzo',
        'inputNumeroCivico' => 'numero civico',
        'inputCitta' => 'città',
        'inputProvincia' => 'provincia',
        'inputCAP' => 'CAP',
        'inputCF' => 'codice fiscale',
        'descrizione' => 'descrizione',
        'prezzo' => 'prezzo',
        'qta' => 'quantità',
        'note' => 'note',
    ],

];
