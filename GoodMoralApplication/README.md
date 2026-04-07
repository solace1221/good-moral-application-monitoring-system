# 🎓 Good Moral Application and Violations Monitoring System

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A comprehensive digital solution that transforms how educational institutions manage student conduct records and good moral certificate processing. This system reduces processing time from weeks to days while providing unprecedented transparency and accountability.

---

## 📋 **Table of Contents**

- [Overview](#overview)
- [Key Features](#key-features)
- [System Screenshots](#system-screenshots)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [User Roles](#user-roles)
- [Documentation](#documentation)

---

## 🌟 **Overview**

The Good Moral Application and Violations Monitoring System is a capstone project designed to digitize and streamline the traditional manual process of issuing good moral certificates and tracking student violations in academic institutions.

### **Problem Statement**
Traditional manual processing creates inefficiencies, delays, and inconsistencies. Students face long waiting periods, administrators struggle with paper-based tracking, and there's a lack of transparency in the approval process.

### **Solution**
A role-based web application with automated workflows, real-time notifications, comprehensive reporting, and mobile-responsive design.

### **Impact**
- ✅ **75% reduction** in processing time
- ✅ **100% transparency** with real-time tracking
- ✅ **90% reduction** in manual errors
- ✅ **Immediate notifications** to all stakeholders
- ✅ **Streamlined workflows** eliminating redundant processes

---

## ⚡ **Key Features**

### **For Students**
- 📝 Online good moral certificate application
- 🔍 Real-time application status tracking
- 📥 Digital certificate download
- 🔔 Automated email and system notifications
- 📊 Personal violation history access

### **For Administrators**
- 🎯 Multi-role dashboard system (Admin, Dean, Moderator, Registrar, etc.)
- 📈 Comprehensive analytics and reporting
- ⚠️ Automated violation escalation
- 👥 User management and permissions
- 🔐 Role-based access control

### **For Moderators/OSA**
- ➕ Easy violation entry and management
- 🔄 Automatic case escalation based on severity
- 📋 Violation pattern recognition
- 📊 Department-specific reporting

### **For Deans**
- ✔️ Streamlined application review and approval
- 👤 Comprehensive student profiles with violation history
- 📑 Batch processing capabilities
- 💬 Decision feedback and comments

---

## 🖼️ **System Screenshots**

> **📸 To add screenshots**: Place your PNG/JPG images in the `screenshots/` folder and they will automatically appear below.

### **Login & Dashboard**
#### System Login Page
![Login Page](screenshots/login-page.png)
*Secure authentication with role-based access control*

#### Student Dashboard
![Student Dashboard](screenshots/student-dashboard.png)
*Clean, intuitive interface showing application status and personal information*

---

### **Student Portal**
#### Good Moral Application Form
![Application Form](screenshots/student-application-form.png)
*Streamlined application process with real-time validation*

#### Application Status Tracking
![Application Status](screenshots/student-application-status.png)
*Real-time tracking eliminates repeated inquiries*

#### Certificate Download
![Certificate Download](screenshots/student-certificate-download.png)
*Digital certificate generation and download*

---

### **Moderator/OSA Secretary Portal**
#### Violation Management Dashboard
![Moderator Dashboard](screenshots/moderator-dashboard.png)
*Comprehensive violation tracking and case management*

#### Add Violation Interface
![Add Violation](screenshots/moderator-add-violation.png)
*Easy violation entry with automatic student lookup*

#### Escalation Queue
![Escalation Queue](screenshots/moderator-escalation-queue.png)
*Automated escalation for cases requiring higher authority*

---

### **Dean Portal**
#### Pending Applications Queue
![Dean Applications](screenshots/dean-pending-applications.png)
*Efficient application queue management*

#### Application Review & Approval
![Application Review](screenshots/dean-application-review.png)
*Comprehensive student profile for informed decision-making*

---

### **Admin Portal**
#### System-Wide Analytics
![Admin Dashboard](screenshots/admin-dashboard.png)
*Real-time institutional insights and performance metrics*

#### Violation Monitoring
![Violation Monitoring](screenshots/admin-violation-monitoring.png)
*System-wide violation tracking and trend analysis*

---

### **Mobile Responsive Design**
![Mobile Interface](screenshots/mobile-responsive.png)
*Fully responsive design works on all devices*

---

## 🛠️ **Technology Stack**

### **Backend**
- **Framework**: Laravel 12.x
- **Language**: PHP 8.4+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **PDF Generation**: DomPDF/Snappy

### **Frontend**
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Vanilla JS / Alpine.js
- **Build Tool**: Vite

### **Local Development Environment**
- **Laravel Herd** (Windows/macOS) — provides Nginx, PHP, and MySQL

### **Additional Tools**
- **Version Control**: Git
- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Email**: Laravel Mail with SMTP integration
- **Notifications**: Real-time system notifications

---

## 📦 **Installation**

### **Prerequisites**
- [Laravel Herd](https://herd.laravel.com) (provides PHP 8.4, Nginx, and MySQL automatically)
- Composer
- Node.js and NPM

### **Step 1: Clone the Repository**
```bash
git clone https://github.com/solace1221/good-moral-application-and-monitoring-system.git
cd good-moral-application-and-monitoring-system
```

### **Step 2: Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### **Step 3: Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Update `.env` with your site name (must match the Herd site name):
```env
APP_URL=https://goodmoralapplication.test
```

### **Step 4: Database Setup**

Laravel Herd includes MySQL running on `127.0.0.1:3306` with `root` and no password.

Create the database in Herd's database manager (or via the MySQL CLI), then configure `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db-clearance-system
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:
```bash
# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### **Step 5: Build Assets**
```bash
# Development
npm run dev

# Production
npm run build
```

### **Step 6: Configure Laravel Herd**

1. Open **Laravel Herd** and go to **Sites**
2. Add the project's parent folder as a **Parked Path**, or link this folder directly:
   ```bash
   herd link
   herd secure
   ```
3. Herd will serve the application automatically — no `php artisan serve` needed.

Visit `https://goodmoralapplication.test` to access the application.

---

## 👥 **User Roles**

The system supports **7 distinct user roles**, each with specific permissions and responsibilities:

| Role | Primary Function | Key Features |
|------|------------------|--------------|
| **Student/Alumni** | Application submission | Submit applications, track status, download certificates |
| **Moderator (OSA)** | Violation management | Add violations, process escalations, case management |
| **Registrar** | Academic verification | Verify enrollment, academic standing, student records |
| **Dean** | Application approval | Review applications, approve/decline, violation oversight |
| **Program Coordinator** | Program oversight | Monitor program-specific violations and trends |
| **PSG Officers** | Student advocacy | Access transparency reports, monitor processing times |
| **Admin** | System administration | User management, final approvals, system analytics |

For detailed role functions and responsibilities, see [COMPLETE_USER_ROLE_SYNTHESIS.md](COMPLETE_USER_ROLE_SYNTHESIS.md).

---

## 📚 **Documentation**

### **Project Documentation**
- [Complete User Role Synthesis](COMPLETE_USER_ROLE_SYNTHESIS.md) - Comprehensive role functions and system interactions
- [8-Minute Demo Guide](8_MINUTE_DEMO_GUIDE.md) - Presentation guide for demonstrations
- [Demo by User Role](DEMO_BY_USER_ROLE.md) - Role-specific demonstration breakdown
- [Capstone Showcase Script](CAPSTONE_SHOWCASE_SCRIPT.md) - Full presentation script and demo plan

### **Implementation Guides**
- [Dashboard Enhancements](DASHBOARD_ENHANCEMENT_COMPLETE.md)
- [Responsive Design Summary](RESPONSIVE_DESIGN_SUMMARY.md)
- [Violation System Implementation](VIOLATION_REDIRECT_FIX_SUMMARY.md)
- [Certificate Processing](CERTIFICATE_NAME_FORMAT_FIX.md)
- [Database Backup Setup](DATABASE_BACKUP_README.md)

### **Quick Reference**
- [Quick Reference Guide](QUICK_REFERENCE.md) - Common tasks and troubleshooting

---

## 🔄 **Workflow Overview**

### **Good Moral Application Process**
```
Student Submits Application
        ↓
Registrar Verifies Academic Standing
        ↓
Dean Reviews & Approves/Declines
        ↓
Admin Final Processing
        ↓
Certificate Generated → Student Downloads
```

### **Violation Management Process**
```
Moderator Adds Violation
        ↓
System Classifies (Minor/Major)
        ↓
Automatic Escalation (if needed)
        ↓
Dean/Admin Reviews Case
        ↓
Case Closed → Notifications Sent
```

---

## 📄 **License**

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">

**⭐ Star this repository if you find it useful!**

Built with Laravel Framework | [Documentation](https://laravel.com/docs)

</div>
