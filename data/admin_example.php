<?php
/* Copy to data/admin.php on the server and set a long password. stats.php
   asks for it. data/ is never served and admin.php is ignored by git.

   To avoid keeping the password in plain text, use 'password_hash' instead,
   with a value from PHP's password_hash('your-password', PASSWORD_DEFAULT). */
return [
    'password' => 'change-me-to-something-long',
    // 'password_hash' => '$2y$10$...',
];
