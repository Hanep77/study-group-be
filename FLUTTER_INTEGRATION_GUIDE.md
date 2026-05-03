# Task Management API - Reference & Flutter Integration

## Dokumentasi API Reference + Flutter Integration

---

## 📊 API ENDPOINTS SUMMARY

### Authentication Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | ❌ | Daftar user baru |
| POST | `/api/login` | ❌ | Login dan dapatkan token |
| GET | `/api/me` | ✅ | Get profil user saat ini |
| POST | `/api/logout` | ✅ | Logout dan hapus token |

### Group Management Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/groups` | ✅ | Get semua grup user |
| POST | `/api/groups` | ✅ | Buat grup baru |
| GET | `/api/groups/{id}` | ✅ | Get detail grup |
| PUT | `/api/groups/{id}` | ✅ | Update grup (admin only) |
| DELETE | `/api/groups/{id}` | ✅ | Hapus grup (creator only) |
| POST | `/api/groups/join` | ✅ | Join grup dengan kode |
| POST | `/api/groups/{id}/leave` | ✅ | Keluar dari grup |

### Task Management Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/groups/{groupId}/tasks` | ✅ | Get semua task di grup |
| POST | `/api/groups/{groupId}/tasks` | ✅ | Buat task baru |
| GET | `/api/tasks/{id}` | ✅ | Get detail task |
| PUT | `/api/tasks/{id}` | ✅ | Update task |
| DELETE | `/api/tasks/{id}` | ✅ | Hapus task |
| PATCH | `/api/tasks/{id}/status` | ✅ | Update status task |

### Checklist Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/tasks/{taskId}/checklists` | ✅ | Tambah checklist item |
| PATCH | `/api/checklists/{id}/toggle` | ✅ | Toggle checklist |
| DELETE | `/api/checklists/{id}` | ✅ | Hapus checklist item |

---

## 🔑 Authentication & Headers

### Register Request

```json
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response Success (201):**
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

### Login Request

```json
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response Success (200):**
```json
{
  "message": "Login successful",
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

### API Request Headers (Authenticated)

```
Authorization: Bearer 1|abc123def456...
Content-Type: application/json
```

---

## 👥 GROUP MANAGEMENT DETAILS

### Create Group

```json
POST /api/groups
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Project Development",
  "description": "Team untuk mengerjakan project X",
  "deadline": "2024-12-31"
}
```

**Response (201):**
```json
{
  "message": "Group created successfully",
  "data": {
    "id": 1,
    "creator_id": 1,
    "name": "Project Development",
    "description": "Team untuk mengerjakan project X",
    "deadline": "2024-12-31",
    "status": "active",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "creator": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "members": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "pivot": {
          "role": "admin",
          "joined_at": "2024-01-15T10:30:00Z"
        }
      }
    ]
  }
}
```

### Get All Groups (Paginated)

```
GET /api/groups
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Project Development",
      "description": "Team development",
      "deadline": "2024-12-31",
      "status": "active",
      "created_at": "2024-01-15T10:30:00Z",
      "members_count": 3,
      "creator": {
        "id": 1,
        "name": "John Doe"
      }
    },
    {
      "id": 2,
      "name": "Marketing Campaign",
      "description": "Campaign marketing Q1",
      "deadline": "2024-03-31",
      "status": "active",
      "created_at": "2024-01-10T14:25:00Z",
      "members_count": 5,
      "creator": {
        "id": 2,
        "name": "Jane Smith"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 2
  }
}
```

### Update Group

```json
PUT /api/groups/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Project Development v2",
  "description": "Updated description",
  "deadline": "2024-12-31",
  "status": "active"
}
```

### Join Group

```json
POST /api/groups/join
Authorization: Bearer {token}
Content-Type: application/json

{
  "code": "1"
}
```

### Leave Group

```
POST /api/groups/{id}/leave
Authorization: Bearer {token}
```

---

## 📝 TASK MANAGEMENT DETAILS

### Create Task

```json
POST /api/groups/{groupId}/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Implement login feature",
  "description": "Create authentication with JWT",
  "priority": "high",
  "assigned_to": 2,
  "due_date": "2024-02-15"
}
```

**Response (201):**
```json
{
  "message": "Task created successfully",
  "data": {
    "id": 1,
    "group_id": 1,
    "created_by": 1,
    "assigned_to": 2,
    "title": "Implement login feature",
    "description": "Create authentication with JWT",
    "priority": "high",
    "status": "todo",
    "due_date": "2024-02-15",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "creator": {
      "id": 1,
      "name": "John Doe"
    },
    "assignee": {
      "id": 2,
      "name": "Jane Smith"
    },
    "checklists": []
  }
}
```

### Get Tasks in Group

```
GET /api/groups/{groupId}/tasks?status=todo&priority=high&assigned_to=2
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): `todo`, `in_progress`, `done`
- `priority` (optional): `low`, `medium`, `high`
- `assigned_to` (optional): User ID

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Implement login feature",
      "description": "Create authentication with JWT",
      "priority": "high",
      "status": "in_progress",
      "due_date": "2024-02-15",
      "assigned_to": 2,
      "created_by": 1,
      "checklists": [
        {
          "id": 1,
          "item": "Create login endpoint",
          "completed": true
        },
        {
          "id": 2,
          "item": "Add JWT validation",
          "completed": false
        }
      ],
      "creator": {
        "id": 1,
        "name": "John Doe"
      },
      "assignee": {
        "id": 2,
        "name": "Jane Smith"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 20,
    "total": 1
  }
}
```

### Update Task

```json
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Implement login feature (updated)",
  "description": "Create authentication with JWT and OAuth",
  "priority": "high",
  "status": "in_progress",
  "assigned_to": 3,
  "due_date": "2024-02-20"
}
```

### Update Task Status

```json
PATCH /api/tasks/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "in_progress"
}
```

**Response (200):**
```json
{
  "message": "Task status updated",
  "data": {
    "id": 1,
    "title": "Implement login feature",
    "status": "in_progress",
    "updated_at": "2024-01-15T11:45:00Z"
  }
}
```

---

## ✅ CHECKLIST MANAGEMENT

### Add Checklist Item

```json
POST /api/tasks/{taskId}/checklists
Authorization: Bearer {token}
Content-Type: application/json

{
  "item": "Create login endpoint"
}
```

**Response (201):**
```json
{
  "message": "Checklist item added",
  "data": {
    "id": 1,
    "task_id": 1,
    "item": "Create login endpoint",
    "completed": false,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### Toggle Checklist Item

```json
PATCH /api/checklists/{id}/toggle
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Checklist toggled",
  "data": {
    "id": 1,
    "task_id": 1,
    "item": "Create login endpoint",
    "completed": true,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### Delete Checklist Item

```
DELETE /api/checklists/{id}
Authorization: Bearer {token}
```

---

## 📱 FLUTTER INTEGRATION EXAMPLE

### 1. Install HTTP Package

```yaml
# pubspec.yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.0
  provider: ^6.0.0
```

### 2. Create API Service

```dart
// lib/services/api_service.dart

import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  Map<String, String> _buildHeaders({bool needsAuth = true}) {
    return {
      'Content-Type': 'application/json',
      if (needsAuth) 'Authorization': 'Bearer ${_token}',
    };
  }

  // AUTH ENDPOINTS
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: _buildHeaders(needsAuth: false),
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      }),
    );

    if (response.statusCode == 201) {
      final data = jsonDecode(response.body);
      await saveToken(data['data']['token']);
      return data['data'];
    } else {
      throw Exception('Registration failed: ${response.body}');
    }
  }

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: _buildHeaders(needsAuth: false),
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      await saveToken(data['data']['token']);
      return data['data'];
    } else {
      throw Exception('Login failed: ${response.body}');
    }
  }

  Future<void> logout() async {
    await http.post(
      Uri.parse('$baseUrl/logout'),
      headers: _buildHeaders(),
    );
    await clearToken();
  }

  // GROUP ENDPOINTS
  Future<List<dynamic>> getGroups({int page = 1}) async {
    final response = await http.get(
      Uri.parse('$baseUrl/groups?page=$page'),
      headers: _buildHeaders(),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    } else {
      throw Exception('Failed to fetch groups');
    }
  }

  Future<Map<String, dynamic>> createGroup({
    required String name,
    required String description,
    required String deadline,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/groups'),
      headers: _buildHeaders(),
      body: jsonEncode({
        'name': name,
        'description': description,
        'deadline': deadline,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to create group');
    }
  }

  Future<Map<String, dynamic>> getGroupDetails(int groupId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/groups/$groupId'),
      headers: _buildHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to fetch group details');
    }
  }

  // TASK ENDPOINTS
  Future<List<dynamic>> getTasks(
    int groupId, {
    String? status,
    String? priority,
    int? assignedTo,
    int page = 1,
  }) async {
    String url = '$baseUrl/groups/$groupId/tasks?page=$page';
    
    if (status != null) url += '&status=$status';
    if (priority != null) url += '&priority=$priority';
    if (assignedTo != null) url += '&assigned_to=$assignedTo';

    final response = await http.get(
      Uri.parse(url),
      headers: _buildHeaders(),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    } else {
      throw Exception('Failed to fetch tasks');
    }
  }

  Future<Map<String, dynamic>> createTask({
    required int groupId,
    required String title,
    required String description,
    required String priority,
    int? assignedTo,
    String? dueDate,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/groups/$groupId/tasks'),
      headers: _buildHeaders(),
      body: jsonEncode({
        'title': title,
        'description': description,
        'priority': priority,
        'assigned_to': assignedTo,
        'due_date': dueDate,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to create task');
    }
  }

  Future<Map<String, dynamic>> updateTaskStatus({
    required int taskId,
    required String status,
  }) async {
    final response = await http.patch(
      Uri.parse('$baseUrl/tasks/$taskId/status'),
      headers: _buildHeaders(),
      body: jsonEncode({'status': status}),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to update task status');
    }
  }

  // CHECKLIST ENDPOINTS
  Future<Map<String, dynamic>> addChecklist({
    required int taskId,
    required String item,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/tasks/$taskId/checklists'),
      headers: _buildHeaders(),
      body: jsonEncode({'item': item}),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to add checklist');
    }
  }

  Future<Map<String, dynamic>> toggleChecklist(int checklistId) async {
    final response = await http.patch(
      Uri.parse('$baseUrl/checklists/$checklistId/toggle'),
      headers: _buildHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body)['data'];
    } else {
      throw Exception('Failed to toggle checklist');
    }
  }
}
```

### 3. Create Auth Provider

```dart
// lib/providers/auth_provider.dart

import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  bool _isLoggedIn = false;
  Map<String, dynamic>? _user;

  bool get isLoggedIn => _isLoggedIn;
  Map<String, dynamic>? get user => _user;

  Future<void> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final data = await _apiService.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
      );
      _user = data['user'];
      _isLoggedIn = true;
      notifyListeners();
    } catch (e) {
      throw Exception('Registration failed: $e');
    }
  }

  Future<void> login({
    required String email,
    required String password,
  }) async {
    try {
      final data = await _apiService.login(
        email: email,
        password: password,
      );
      _user = data['user'];
      _isLoggedIn = true;
      notifyListeners();
    } catch (e) {
      throw Exception('Login failed: $e');
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.logout();
      _user = null;
      _isLoggedIn = false;
      notifyListeners();
    } catch (e) {
      throw Exception('Logout failed: $e');
    }
  }
}
```

### 4. Usage in Flutter Widget

```dart
// lib/screens/login_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(
              controller: _emailController,
              decoration: InputDecoration(labelText: 'Email'),
            ),
            SizedBox(height: 16),
            TextField(
              controller: _passwordController,
              decoration: InputDecoration(labelText: 'Password'),
              obscureText: true,
            ),
            SizedBox(height: 24),
            ElevatedButton(
              onPressed: _isLoading ? null : _handleLogin,
              child: Text(_isLoading ? 'Loading...' : 'Login'),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _handleLogin() async {
    setState(() => _isLoading = true);
    try {
      await context.read<AuthProvider>().login(
            email: _emailController.text,
            password: _passwordController.text,
          );
      if (mounted) {
        Navigator.of(context).pushReplacementNamed('/groups');
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e.toString())),
        );
      }
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}
```

---

## 🐛 Error Codes & Messages

| Code | Message | Meaning |
|------|---------|---------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Missing or invalid token |
| 403 | Forbidden | Access denied |
| 404 | Not Found | Resource not found |
| 422 | Validation Error | Input validation failed |
| 500 | Server Error | Internal server error |

---

## 🔗 Contoh Flowchart API Calls

```
Flutter App
    ↓
[Login Screen] → POST /api/login → Get Token → Save to SharedPreferences
    ↓
[Groups Screen] → GET /api/groups → Display List
    ↓
[Group Detail] → GET /api/groups/{id} → Show Members & Tasks
    ↓
[Task List] → GET /api/groups/{id}/tasks → Display Tasks
    ↓
[Task Detail] → Update Status / Add Checklist → PATCH /api/tasks/{id}/status
    ↓
[Checklist] → POST /api/tasks/{id}/checklists → Toggle/Delete
```

---

**API Version:** 1.0
**Last Updated:** January 2024
**Compatible with:** Flutter 3.0+, Dart 3.0+
