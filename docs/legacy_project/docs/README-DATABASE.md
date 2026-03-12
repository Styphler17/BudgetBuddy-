# Database Setup Guide

This guide will help you set up the MySQL database for the BudgetBuddy application.

## Prerequisites

- MySQL Server installed and running
- MySQL Workbench (recommended) or command line access to MySQL

## Step 1: Create the Database

### For XAMPP Users:
1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
3. Create a new database named `budgetbuddy`
4. Select the `budgetbuddy` database from the left sidebar
5. Click on the "SQL" tab
6. Copy and paste the contents of `database-schema-admin.sql`
7. Click "Go" to execute the script

### For MySQL Workbench Users:
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Open a new query tab
4. Copy and paste the contents of `database-schema-admin.sql`
5. Execute the script

### Command Line Alternative:
```bash
mysql -u root -p < database-schema-admin.sql
```

## Step 2: Configure Environment Variables

1. Copy `.env.example` to `.env`
2. Update the database configuration in `.env`:

```env
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=budgetbuddy
```

For XAMPP users, the default configuration is usually:
- Host: localhost
- User: root
- Password: (empty)
- Database: budgetbuddy

## Step 3: Install Dependencies

The required MySQL dependencies are already installed. If you need to reinstall:

```bash
npm install mysql2 @types/mysql2
```

## Step 4: Start the Application

The database will be automatically initialized when you start the application:

```bash
npm run dev
```

## Database Schema Overview

### Tables

- **admins**: System administrators with different roles
- **users**: User accounts and profiles
- **categories**: Budget categories with spending limits
- **transactions**: Income and expense records
- **budgets**: Budget settings for different periods
- **goals**: Financial goals and targets
- **accounts**: Bank accounts and balances
- **user_settings**: User preferences and settings
- **admin_logs**: Audit trail of admin actions
- **system_settings**: Global system configuration

### Key Features

- Foreign key relationships ensure data integrity
- Automatic timestamps for created/updated records
- Unique constraints prevent duplicate data
- Indexes for optimal query performance
- Support for multiple currencies
- Admin role-based access control
- Comprehensive audit logging
- System-wide configuration management

## Admin Features

### Role-Based Access Control

- **Super Admin**: Full system access, can manage other admins
- **Admin**: Can manage users and view system statistics
- **Moderator**: Limited admin capabilities, can view logs

### Admin Dashboard

Access the admin dashboard at `/admin` to:
- View system statistics and metrics
- Manage user accounts (activate/deactivate, edit details)
- Create and manage admin accounts
- View audit logs of all admin actions
- Configure system-wide settings

### Creating Your First Admin

After setting up the database, create an admin user:

```sql
INSERT INTO admins (email, name, password_hash, role)
VALUES ('admin@budgetbuddy.com', 'Super Admin', '$2b$10$your_bcrypt_hash_here', 'super_admin');
```

**Note**: In a production environment, always hash passwords properly using bcrypt.

## Troubleshooting

### Connection Issues

- Ensure MySQL server is running (XAMPP: check Apache and MySQL modules)
- Verify database credentials in `.env`
- Check that the database `budgetbuddy` exists
- Try connecting with phpMyAdmin or MySQL Workbench first

### Permission Errors

- For XAMPP, the root user should have all permissions by default
- If using a different MySQL setup, ensure your user has CREATE, SELECT, INSERT, UPDATE, DELETE permissions

### Migration Issues

If you need to update the schema:
1. Backup your data
2. Modify the `database-schema-admin.sql` file
3. Run the updated script
4. Test thoroughly

### Admin Access Issues

- Ensure you're accessing `/admin` route
- Check that admin accounts exist in the database
- Verify admin account is active (`is_active = 1`)

## Security Notes

- Never commit `.env` files with real credentials
- Use strong passwords for database users
- Consider using connection pooling for production
- Implement proper input validation and sanitization
