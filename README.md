# BudgetBuddy- (SpendScribe)

## Deployment Instructions

1.  **Environment Variables:** Rename `.env.example` to `.env` and update your database credentials:
    ```
    DB_HOST=localhost
    DB_NAME=your_database_name
    DB_USER=your_database_user
    DB_PASS=your_database_password
    ```
2.  **Production Mode:** In `index.php`, ensure `define('APP_ENV', 'production');` is set to disable error displays.
3.  **Base URL:** The application automatically detects the `BASE_URL`, but ensure your `.htaccess` is supported by your hosting (Apache).
4.  **Database Migration:** Import the SQL schema from `docs/budgetbuddy.sql` into your production database.

## Default Admin Account

- Email: temp.admin@budgetbuddy.com
- Password: TempAdmin!123

Update these credentials after the first login via the Admin Profile settings.

