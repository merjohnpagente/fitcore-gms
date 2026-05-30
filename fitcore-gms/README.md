# FitCore GMS — Gym Member Management System
> A complete CRUD API in PHP + MySQL with a connected frontend.  
> Built for: **Finals Exam — Institute of Computer Studies**

---

## Tech Stack
- **Backend:** PHP (vanilla, no framework)
- **Database:** MySQL via XAMPP
- **Frontend:** HTML + CSS + Vanilla JavaScript (Fetch API)

---

## Project Structure
```
fitcore-gms/
├── api/
│   ├── config/
│   │   └── database.php       ← DB connection + helper functions
│   ├── endpoints/
│   │   ├── create.php         ← POST   /api/members
│   │   ├── read.php           ← GET    /api/members  or  /api/members/{id}
│   │   ├── update.php         ← PUT    /api/members/{id}
│   │   └── delete.php         ← DELETE /api/members/{id}
│   ├── index.php              ← Router / entry point
│   └── .htaccess              ← Clean URL rewriting
├── frontend/
│   └── index.html             ← Full frontend UI
├── database.sql               ← DB schema + sample data
└── README.md
```

---

## Setup Instructions

### 1. Start XAMPP
Make sure **Apache** and **MySQL** are running.

### 2. Place the project
Copy the entire `fitcore-gms` folder into your XAMPP `htdocs` directory:
```
C:/xampp/htdocs/fitcore-gms/
```

### 3. Create the database
- Open **phpMyAdmin** → `http://localhost/phpmyadmin`
- Click **Import** → select `database.sql` → click **Go**

### 4. Open the frontend
Visit in your browser:
```
http://localhost/fitcore-gms/frontend/index.html
```

---

## API Endpoints

Base URL: `http://localhost/fitcore-gms/api/members`

| Method | URL | Description |
|--------|-----|-------------|
| GET | `/api/members` | Get all members |
| GET | `/api/members?search=juan` | Search members |
| GET | `/api/members?plan=Premium&status=Active` | Filter members |
| GET | `/api/members/{id}` | Get single member |
| POST | `/api/members` | Create new member |
| PUT | `/api/members/{id}` | Update member |
| DELETE | `/api/members/{id}` | Delete member |

### Sample POST/PUT body (JSON)
```json
{
  "full_name":  "Merjohn Pagente",
  "email":      "Merjohn@email.com",
  "phone":      "09171234567",
  "plan":       "Premium",
  "status":     "Active",
  "start_date": "2025-01-01",
  "end_date":   "2025-12-31"
}
```

### Sample Response
```json
{
  "success": true,
  "message": "Member created successfully.",
  "data": { "id": 4 }
}
```

---

## Features
- Full **CRUD** operations for gym members
- Input **validation** and **sanitization** on all endpoints
- Duplicate **email** detection
- **Search** by name, email, or phone
- **Filter** by plan (Basic / Standard / Premium) and status (Active / Inactive / Suspended)
- Proper **HTTP status codes** (200, 201, 400, 404, 409, 500)
- **CORS** headers for frontend integration
- Clean frontend with **real-time search**, modals, toast notifications, and live stats

---

## Developer
**MERJOHN PAGENTE**  
Institute of Computer Studies — Tangub City Global College
"# fitcore-gms" 
