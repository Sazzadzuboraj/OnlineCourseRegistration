# CourseHub - Modern Online Course Registration System

Professional PHP/MySQL course registration platform with dark mode, ratings, printable slips, and live animated UI.

## New Features (Latest Update)

- **Course Rating** — Students rate enrolled courses (1–5 stars + optional review). Average ratings shown on course catalog.
- **Dark Mode Toggle** — One-click light/dark theme, saved in browser (localStorage).
- **Print Enrollment Slip** — Professional printable slip with student & course details.
- **Live Animated Background** — Floating gradient orbs + interactive particle network.
- **Professional UI** — Glass cards, refined typography, smooth micro-animations.

## Default Credentials

| Role    | Username / Reg No   | Password      |
|---------|---------------------|---------------|
| Admin   | `admin`             | `Admin@123`   |
| Student | `2025-CSE-001`      | `Student@123` |

## Setup

1. Copy folder to XAMPP/WAMP `htdocs`
2. Start Apache + MySQL
3. Import `db/onlinecourse.sql` in phpMyAdmin
4. Open:
   - Student: `http://localhost/OnlineCourseRegistration/`
   - Admin: `http://localhost/OnlineCourseRegistration/admin/`

## Tech

- PHP 8+ / MySQL
- Bootstrap 5.3 + Font Awesome 6 + Inter font
- Custom CSS (CSS variables + dark mode)
- Canvas particle animation + CSS orbs
- password_hash / password_verify

## Student Pincode (demo)

After login as demo student, pincode is shown on **My Profile** page (needed for enrollment).
Demo student pincode: **482913**
