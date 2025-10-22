# KnowledgeCity Backend

A multi-regional educational platform backend built with Laravel 12, providing secure RESTful APIs for user authentication, course management, enrollments, payments, and video streaming.

## 🚀 Features

- **Authentication**: Laravel Sanctum token-based authentication
- **Course Management**: Complete CRUD operations for courses and lessons
- **User Enrollments**: Track student progress and course completion
- **Payment Processing**: Mock payment system for course enrollments
- **Video Streaming**: Video upload, processing, and streaming capabilities
- **Multi-Regional**: Designed for UAE and KSA regional deployment
- **Scalable Architecture**: Ready for microservices integration

## 📁 Project Structure

```
knowledgecity/
├── backend/                    # Laravel 12 API
│   ├── app/
│   │   ├── Http/Controllers/API/  # API Controllers
│   │   ├── Models/               # Eloquent Models
│   │   ├── Jobs/                 # Background Jobs
│   │   └── Providers/            # Service Providers
│   ├── database/
│   │   ├── migrations/          # Database Migrations
│   │   └── seeders/            # Database Seeders
│   ├── routes/
│   │   ├── api.php             # API Routes
│   │   └── web.php             # Web Routes
│   └── tests/                  # Test Suite
└── frontend/                   # React SPA (separate)
```

## 📋 Requirements

- **PHP** >= 8.2
- **Composer** (latest)
- **MySQL** >= 8.0
- **Laravel** 12
- **Node.js** & NPM (for frontend)

## 🛠️ Installation

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/knowledgecity.git
cd knowledgecity/backend
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
```

Update your `.env` file:
```env
APP_NAME=KnowledgeCity
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=knowledgecity
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost
SESSION_DOMAIN=localhost
```

### 4. Generate Key & Migrate
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Start Development Server
```bash
php artisan serve
```

API will be available at: **http://localhost:8000/api**

## 🔐 Authentication

The system uses **Laravel Sanctum** for token-based authentication.

### Public Routes
- `POST /api/register` - Register new user
- `POST /api/login` - Login and receive access token

### Protected Routes (require Bearer Token)
- `POST /api/logout` - Logout user
- `GET /api/me` - Get current user
- `GET /api/courses` - List courses
- `GET /api/courses/{id}` - Course details
- `GET /api/enrollments` - User enrollments
- `POST /api/enroll/{courseId}` - Enroll in course
- `PUT /api/enrollment/{id}/progress` - Update progress
- `GET /api/payments` - User payments
- `POST /api/pay/{enrollmentId}` - Make payment
- `GET /api/course/{courseId}/videos` - Course videos
- `GET /api/video/{videoId}/stream` - Stream video
- `GET /api/video/{videoId}/status` - Video processing status

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts (students, instructors, admins)
- **courses** - Course information and metadata
- **course_lessons** - Individual lessons within courses
- **enrollments** - Student course enrollments and progress
- **payments** - Payment transactions and history
- **videos** - Video files and processing status

### Seeder Data
The `DatabaseSeeder` includes:
- `UsersTableSeeder` - Sample users by region
- `CoursesTableSeeder` - Sample courses
- `CourseLessonsTableSeeder` - Course lesson structure
- `EnrollmentsTableSeeder` - Student enrollments
- `PaymentsTableSeeder` - Payment history

**Reset with fresh data:**
```bash
php artisan migrate:fresh --seed
```

## 🧪 API Testing

### Using Postman/Insomnia
1. Register a new user: `POST /api/register`
2. Login: `POST /api/login` (save the token)
3. Use token in Authorization header: `Bearer {your-token}`
4. Test protected endpoints

### Example Request
```bash
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

## 🏗️ Architecture

### Current Implementation
- **Monolithic Laravel API** - Core business logic
- **MySQL Database** - Primary data storage
- **Laravel Sanctum** - Authentication system
- **Queue System** - Background job processing

### Future Enhancements
- **Video Microservice** - AWS S3 + CloudFront integration
- **Analytics Service** - ClickHouse for user analytics
- **Multi-Region Deployment**:
  - UAE users → UAE region storage
  - KSA users → KSA region storage
- **Microservices Architecture** - Service mesh implementation

## 🚀 Deployment

### Development
```bash
php artisan serve
```

### Production (Docker)
```bash
# Build and run with Docker
docker-compose up -d
```

### AWS Deployment
- **ECS Fargate** for container hosting
- **RDS MySQL** for database
- **S3** for file storage
- **CloudFront** for CDN
- **Route 53** for DNS


## 🔒 Security

### Implemented
- **Laravel Sanctum** authentication
- **CSRF Protection** for web routes
- **SQL Injection** prevention via Eloquent
- **XSS Protection** via Laravel's built-in features


## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -am 'Add new feature'`
4. Push to branch: `git push origin feature/new-feature`
5. Submit a Pull Request

## 📄 License

This project is for educational and assessment purposes under the KnowledgeCity test assignment.

## 📞 Support

For questions or support, please contact the development team.

---

**Built with ❤️ using Laravel 12**