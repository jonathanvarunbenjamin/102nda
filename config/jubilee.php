<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bootstrap admin emails
    |--------------------------------------------------------------------------
    |
    | Google accounts listed here are automatically made approved admins the
    | first time they sign in. This seeds the initial set of 15 course admins
    | without needing server/database access. After that, admins are managed
    | inside the app. Set ADMIN_EMAILS in .env as a comma-separated list.
    |
    */

    'admin_emails' => array_values(array_filter(array_map(
        fn ($email) => strtolower(trim($email)),
        explode(',', (string) env('ADMIN_EMAILS', ''))
    ))),

];
