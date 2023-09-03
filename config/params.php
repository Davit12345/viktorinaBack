<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'password_pattern' => '/(.*)?(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@,;.:$!^#%*?<>~`\'=&+\/"()\-_[])[{a-zA-Z\d$@,;.:$!^#%*?<>~`\'=&+\/"()\-_[].{0,}/',
    'email_pattern' => '/^([a-zA-Z0-9.!#$%&’*+\=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-z]{2,})$/',
    'bsDependencyEnabled' => false,
    'bsVersion' => '4.x',
    'upload_path' => 'images/uploads',
    'jwt' => [
        'issuer' => 'control_panel',  //name of your project (for information only)
        'audience' => 'control_panel',  //description of the audience, eg. the website using the authentication (for info only)
        'id' => 'Aessjatekw3control_panel7w*7a!2qwwnqw6%^',  //a unique identifier for the JWT, typically a random string
        'expire' => 300000000,  //the short-lived JWT token is here set to expire after 5 min.
    ],
];
