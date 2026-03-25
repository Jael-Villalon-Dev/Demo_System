## Gmail Password Reset Setup

### 1. Install Composer (if not already done)
Download from https://getcomposer.org/download/

### 2. Install PHPMailer
```bash
cd c:\xampp\htdocs\Demo_System
composer require phpmailer/phpmailer
```

This creates a `vendor/` folder and `composer.json` file.

### 3. Get Gmail App Password
- Visit: https://myaccount.google.com/apppasswords
- Select "Mail" and "Windows Computer"
- Copy the 16-character app password

### 4. Update config/mail.php
Open `c:\xampp\htdocs\Demo_System\config\mail.php` and replace:
```
MAIL_USERNAME => your-email@gmail.com
MAIL_PASSWORD => your-app-password-16-chars
MAIL_FROM_EMAIL => your-email@gmail.com
```

### 5. Test the flow
1. Register a user or login
2. Click "Forgot password?"
3. Enter your registered email
4. Check Gmail inbox for reset link
5. Click link and change password in DB

### File Structure
```
config/
  ├── mail.php (Gmail credentials)
  └── ...
inc/
  ├── mail.php (sendPasswordResetEmail function)
  └── ...
auth/
  ├── forgot_request.php (uses Gmail now)
  ├── reset.php
  └── reset_process.php
```

### Troubleshooting
- If "Could not send email", check:
  - Gmail app password is correct
  - 2FA is enabled on Gmail
  - vendor/autoload.php exists
  - No firewall blocking SMTP:587
- Check PHP error logs for details
