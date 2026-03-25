## PHPMailer Manual Setup (No Composer)

### Folder created:
```
vendor/
  ├── autoload.php (simple loader)
  └── phpmailer/
      └── phpmailer/
          └── src/
              (PHPMailer files go here)
```

### Download PHPMailer files

1. Go to: https://github.com/PHPMailer/PHPMailer/releases/
2. Download latest release (e.g., `PHPMailer-6.8.1.zip`)
3. Extract the zip file
4. Copy these files from extracted folder to `vendor/phpmailer/phpmailer/src/`:
   - `PHPMailer.php`
   - `Exception.php`
   - `OAuth6.php` (optional, for OAuth)

### Final structure should look like:
```
vendor/
  ├── autoload.php
  └── phpmailer/
      └── phpmailer/
          └── src/
              ├── PHPMailer.php
              ├── Exception.php
              └── OAuth6.php (optional)
```

### Test it

1. Updated `inc/mail.php` already uses `vendor/autoload.php`
2. Updated `auth/forgot_request.php` already calls `sendPasswordResetEmail()`
3. Just fill in `config/mail.php` with your Gmail details
4. Test forgot password flow

### Gmail config (config/mail.php):
```
MAIL_USERNAME = your-email@gmail.com
MAIL_PASSWORD = your-16-char-app-password
MAIL_FROM_EMAIL = your-email@gmail.com
```

Done! No Composer needed.
