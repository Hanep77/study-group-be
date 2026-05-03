# Quick Start Guide - Task Management Backend

## Panduan Cepat Setup & Implementasi

---

## ⚡ QUICK START (15 Menit)

### Step 1: Clone & Setup Project

```bash
# Create project
composer create-project laravel/laravel task-management-api
cd task-management-api

# Install dependencies
composer require laravel/sanctum laravel/cors

# Generate key
php artisan key:generate
```

### Step 2: Configure Database

```bash
# Create database
mysql -u root -p
CREATE DATABASE task_management;
EXIT;

# Update .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

### Step 4: Start Server

```bash
php artisan serve
```

✅ **API ready at:** `http://localhost:8000/api`

---

## 📋 COMPLETE IMPLEMENTATION CHECKLIST

### Phase 1: Project Setup (Day 1)

- [ ] Create Laravel project
- [ ] Install packages (Sanctum, CORS)
- [ ] Configure database
- [ ] Create database
- [ ] Run initial migrations

### Phase 2: Database & Models (Day 1)

- [ ] Create migrations:
  - [ ] Groups table
  - [ ] GroupMembers table
  - [ ] Tasks table
  - [ ] TaskOverviews table
  - [ ] Checklists table
  
- [ ] Create models with relationships:
  - [ ] User model
  - [ ] Group model
  - [ ] GroupMember model
  - [ ] Task model
  - [ ] TaskOverview model
  - [ ] Checklist model

### Phase 3: Authentication (Day 1-2)

- [ ] Create AuthController
  - [ ] register() method
  - [ ] login() method
  - [ ] me() method
  - [ ] logout() method
  
- [ ] Configure Sanctum
- [ ] Create auth routes

### Phase 4: Group Management (Day 2)

- [ ] Create GroupController with methods:
  - [ ] index() - list groups
  - [ ] store() - create group
  - [ ] show() - get group details
  - [ ] update() - update group
  - [ ] destroy() - delete group
  - [ ] join() - join group
  - [ ] leave() - leave group
  
- [ ] Create routes for groups
- [ ] Test all endpoints with Postman

### Phase 5: Task Management (Day 2-3)

- [ ] Create TaskController with methods:
  - [ ] index() - list tasks
  - [ ] store() - create task
  - [ ] show() - get task details
  - [ ] update() - update task
  - [ ] destroy() - delete task
  - [ ] updateStatus() - change status
  
- [ ] Create routes for tasks
- [ ] Add filtering (status, priority, assigned_to)

### Phase 6: Checklist Management (Day 3)

- [ ] Create ChecklistController with methods:
  - [ ] store() - add item
  - [ ] toggle() - toggle completion
  - [ ] destroy() - delete item
  
- [ ] Create routes for checklists

### Phase 7: Testing & Validation (Day 3-4)

- [ ] Request validation for all endpoints
- [ ] Error handling
- [ ] Global exception handler
- [ ] Test with Postman/Insomnia
- [ ] Test from Flutter app

### Phase 8: Optimization (Day 4)

- [ ] Add proper response formatting
- [ ] Add pagination
- [ ] Add resource caching
- [ ] Database query optimization
- [ ] Error logging

---

## 🔧 FILE STRUCTURE

```
task-management-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── GroupController.php
│   │   │       ├── TaskController.php
│   │   │       └── ChecklistController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Group.php
│   │   ├── GroupMember.php
│   │   ├── Task.php
│   │   ├── TaskOverview.php
│   │   └── Checklist.php
│   └── Exceptions/
│       └── Handler.php
├── database/
│   └── migrations/
│       ├── 2024_01_01_000000_create_users_table.php
│       ├── 2024_01_01_000001_create_groups_table.php
│       ├── 2024_01_01_000002_create_group_members_table.php
│       ├── 2024_01_01_000003_create_task_overviews_table.php
│       ├── 2024_01_01_000004_create_tasks_table.php
│       └── 2024_01_01_000005_create_checklists_table.php
├── routes/
│   └── api.php
├── config/
│   ├── cors.php
│   └── sanctum.php
└── .env
```

---

## 🎯 TESTING ENDPOINTS

### 1. Register User

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Expected Response (201):**
```json
{
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123def456..."
  }
}
```

### 2. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Save token for next requests!**

### 3. Get Current User

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 4. Create Group

```bash
curl -X POST http://localhost:8000/api/groups \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Project Development",
    "description": "Team untuk project",
    "deadline": "2024-12-31"
  }'
```

### 5. Get All Groups

```bash
curl -X GET http://localhost:8000/api/groups \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 6. Create Task

```bash
curl -X POST http://localhost:8000/api/groups/1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Implement login",
    "description": "Create auth endpoint",
    "priority": "high",
    "assigned_to": 2,
    "due_date": "2024-02-15"
  }'
```

### 7. Get Tasks

```bash
curl -X GET "http://localhost:8000/api/groups/1/tasks?status=todo&priority=high" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 8. Update Task Status

```bash
curl -X PATCH http://localhost:8000/api/tasks/1/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status": "in_progress"}'
```

### 9. Add Checklist

```bash
curl -X POST http://localhost:8000/api/tasks/1/checklists \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"item": "Create login endpoint"}'
```

### 10. Toggle Checklist

```bash
curl -X PATCH http://localhost:8000/api/checklists/1/toggle \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📦 POSTMAN COLLECTION

Gunakan environment variables di Postman:

```json
{
  "base_url": "http://localhost:8000/api",
  "token": "YOUR_TOKEN_HERE"
}
```

Kemudian gunakan dalam requests:
```
Authorization: Bearer {{token}}
URL: {{base_url}}/groups
```

---

## 🔍 DEBUGGING TIPS

### 1. Enable Debug Mode

Edit `.env`:
```env
APP_DEBUG=true
```

### 2. Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

### 3. Test Database Connection

```bash
php artisan tinker
> DB::connection()->getPdo()
> exit
```

### 4. Check Routes

```bash
php artisan route:list
```

### 5. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## ⚠️ COMMON ISSUES & SOLUTIONS

### Issue 1: CORS Error
**Solution:** Update `config/cors.php`:
```php
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### Issue 2: 401 Unauthorized
**Make sure:**
- Token is included in header: `Authorization: Bearer {token}`
- Token is not expired
- Database connection is working

### Issue 3: 422 Validation Error
**Check:**
- Request body matches validation rules
- All required fields are present
- Email/unique values don't already exist

### Issue 4: 404 Not Found
**Check:**
- Resource ID exists in database
- Route is correctly defined in `routes/api.php`
- Spelling of endpoint is correct

### Issue 5: Database Connection Error
**Solution:**
```bash
# Check .env database settings
# Ensure MySQL is running
mysql -u root -p
SHOW DATABASES;
```

---

## 🚀 DEPLOYMENT CHECKLIST

Before production:

- [ ] Set `APP_DEBUG=false`
- [ ] Generate secure `APP_KEY`
- [ ] Use environment variables for sensitive data
- [ ] Setup HTTPS/SSL certificate
- [ ] Configure proper CORS origins
- [ ] Setup database backups
- [ ] Configure email service
- [ ] Setup error logging service
- [ ] Run `php artisan optimize`
- [ ] Run `php artisan config:cache`
- [ ] Test all endpoints thoroughly

### Deploy to Heroku

```bash
# Login
heroku login

# Create app
heroku create your-app-name

# Add MySQL addon
heroku addons:create cleardb:ignite -a your-app-name

# Deploy
git push heroku main

# Run migrations
heroku run php artisan migrate -a your-app-name
```

---

## 📚 USEFUL COMMANDS

```bash
# Create controller
php artisan make:controller Api/ControllerName

# Create model with migration
php artisan make:model ModelName -m

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migrations
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Clear everything
php artisan optimize:clear

# Run tinker (interactive shell)
php artisan tinker

# Check route list
php artisan route:list

# Generate API documentation
# (if you use Laravel Scribe)
php artisan scribe:generate
```

---

## 🎓 LEARNING RESOURCES

- **Laravel Documentation:** https://laravel.com/docs
- **Sanctum:** https://laravel.com/docs/sanctum
- **Eloquent ORM:** https://laravel.com/docs/eloquent
- **API Resources:** https://laravel.com/docs/eloquent-resources
- **Testing:** https://laravel.com/docs/testing
- **Deployment:** https://laravel.com/docs/deployment

---

## 📞 SUPPORT

Jika ada pertanyaan:
1. Check Laravel documentation
2. Search Stack Overflow
3. Check GitHub issues
4. Ask in Laravel Discord community

---

**Status:** Ready for Production ✅
**Last Updated:** January 2024
**Tested with:** PHP 8.1+, Laravel 10.x, MySQL 8.0+
