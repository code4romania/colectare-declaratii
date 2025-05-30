<?php

declare(strict_types=1);

return[
    'fields' => [
        'full_name' => 'Nume complet',
        'institution' => 'Instituție',
        'position' => 'Funcție',
        'county' => 'Județ',
        'locality' => 'Localitate',
        'fill_date' => 'Data completării',
        'type' => 'Tip',
        'file' => 'Fișier',
        'ip_address' => 'Adresa IP',

    ],

    'hints' => [
        'full_name' => 'Introduceți numele complet al persoanei din declarație.',
        'file' => 'Încărcați fișierul PDF descǎrcat de pe <a href="https://integritate.eu/portal/" class="underline" target="_blank">ANI</a>.',
    ],

    'declaration_types' => [
        'assets' => 'Declarație de avere',
        'interests' => 'Declarație de interese',
    ],

    'submit' => 'Trimite',
    'refresh' => 'Incarcă o nouă declarație',

    'declaration' => [
        'singular' => 'Declarație',
        'plural' => 'Declarații',
    ],

];
