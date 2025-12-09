# User Management CRUD System with Soft Delete

## Overview
This is a complete CRUD (Create, Read, Update, Delete) system for user management with soft delete functionality. Users are not permanently removed from the database when deleted; instead, they are marked with a `deleted_at` timestamp.

## Database Schema

### Users Table Structure
The users table includes:
- `id` - Primary key (auto-increment)
- `name` - User's full name
- `username` - Username (can be same as name)
- `email` - Unique email address
- `password` - Hashed password
- `role` - User role (admin, teacher, or student)
- `created_at` - Timestamp when user was created
- `updated_at` - Timestamp when user was last updated
- `deleted_at` - Timestamp when user was soft deleted (NULL for active users)

### Migration
Run the migration to add the `deleted_at` column:
```bash
php spark migrate
```

## Files Structure

### 1. Migration File
**Location:** `app/Database/Migrations/2025-01-15-000001_AddDeletedAtToUsersTable.php`
- Adds `deleted_at` column to the users table
- Allows NULL values (NULL = active user, timestamp = deleted user)

### 2. Model
**Location:** `app/Models/UserModel.php`
- Enabled soft deletes (`useSoftDeletes = true`)
- Configured `deletedField = 'deleted_at'`
- Includes validation rules for all user fields
- Automatically excludes deleted users from queries unless explicitly requested

### 3. Controller
**Location:** `app/Controllers/Users.php`
- **index()** - Lists all active users (excludes deleted)
- **create()** - Shows form to create new user
- **store()** - Saves new user to database
- **edit($id)** - Shows form to edit existing user
- **update($id)** - Updates user information
- **delete($id)** - Soft deletes a user (sets deleted_at timestamp)
- **trash()** - Lists all deleted users
- **restore($id)** - Restores a deleted user (sets deleted_at to NULL)

### 4. Views
**Location:** `app/Views/users/`
- **index.php** - Active users list with Bootstrap table
- **create.php** - Form to create new user
- **edit.php** - Form to edit existing user
- **trash.php** - Deleted users list with restore functionality

### 5. Routes
**Location:** `app/Config/Routes.php`
All routes are prefixed with `/users`:
- `GET /users` - List active users
- `GET /users/create` - Show create form
- `POST /users/store` - Store new user
- `GET /users/edit/{id}` - Show edit form
- `POST /users/update/{id}` - Update user
- `GET /users/delete/{id}` - Soft delete user
- `GET /users/trash` - List deleted users
- `GET /users/restore/{id}` - Restore deleted user

## How It Works

### Soft Delete Mechanism
1. When `delete()` is called, CodeIgniter 4's soft delete feature automatically sets the `deleted_at` field to the current timestamp
2. The user record remains in the database but is excluded from normal queries
3. To view deleted users, use `withDeleted()` method and filter by `deleted_at IS NOT NULL`
4. To restore, set `deleted_at` back to NULL

### Security Features
- All routes require admin authentication
- Prevents self-deletion (admin cannot delete their own account)
- CSRF protection on all forms
- Input validation and sanitization
- Password hashing using PHP's `password_hash()`

### User Interface
- Bootstrap 5 styling for modern, responsive design
- Font Awesome icons for better UX
- Flash messages for success/error notifications
- Confirmation dialogs before delete/restore actions
- Color-coded role badges (Admin=red, Teacher=yellow, Student=blue)

## Usage Instructions

1. **Run Migration:**
   ```bash
   php spark migrate
   ```

2. **Access User Management:**
   - Login as admin
   - Navigate to `/users` to see active users
   - Click "Create New User" to add users
   - Click edit icon to modify users
   - Click delete icon to soft delete users
   - Navigate to `/users/trash` to see deleted users
   - Click "Restore" to reactivate deleted users

3. **Features:**
   - Create users with name, email, password, and role
   - Edit user details (password optional)
   - Soft delete users (they won't appear in active list)
   - View deleted users in trash
   - Restore deleted users back to active status

## Notes
- Deleted users are NOT permanently removed from the database
- Active users list automatically excludes deleted users
- Only admins can access user management features
- Password field is optional when editing (only updates if provided)

