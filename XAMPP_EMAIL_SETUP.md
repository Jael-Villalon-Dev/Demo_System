## XAMPP Email Configuration (Password Reset)

### Current Setup
- Using PHP's built-in `mail()` function
- Fallback: Shows reset link in UI if email fails (for testing)
- No PHPMailer files needed anymore

### For Local Testing
1. Forgot password → enter email
2. If mail fails, reset link shows in green message
3. Copy/paste link and test password reset
4. DB will update password correctly

### For Production (Gmail SMTP)

Update `php.ini` in `C:\xampp\php\`:

```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = your-email@gmail.com
sendmail_path = "C:\xampp\php\sendmail.exe -t -i"
```

**Note:** Gmail requires app password, not regular password. Get one at:
https://myaccount.google.com/apppasswords

Also update `config/mail.php`:
```php
define('MAIL_FROM_EMAIL', 'your-email@gmail.com');
define('MAIL_FROM_NAME', 'CoinBase Support');
```

### Fallback Display
If mail() fails on your system, you'll see:
```
Reset link (test mode - copy/paste): http://localhost/Demo_System/auth/reset.php?token=...
```

This allows testing without mail server setup.

### To Enable Real Email (Recommended)
1. Install Composer globally
2. Run: `composer require phpmailer/phpmailer`
3. Replace `inc/mail.php` with PHPMailer version (I'll provide it)
4. Configure SMTP credentials

For now, use the fallback link to test!
