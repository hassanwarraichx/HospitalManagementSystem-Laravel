# 🏥 Hospital Management System (Laravel Based)

A robust hospital management system built using **Laravel**, **Bootstrap (Dark Theme)**, and **MySQL**, designed to streamline patient care, appointments, prescriptions, and real-time notifications for doctors, patients, and administrators.

---

## 🚀 Features

### 🔐 Authentication & Roles

* Role-based access for **Admin**, **Doctor**, and **Patient**
* Laravel Sanctum or built-in auth for secure login/logout

### 📅 Appointment Management

* Patients can book appointments with available doctors
* Admin can approve/reject appointments
* Doctors can view upcoming appointments

### 💊 Prescription & Medical History

* Doctors can add/view prescriptions
* Complete medical history exportable as **Excel** or **PDF**
* Prescription details are preserved per appointment

### 📂 Patient Documents

* Doctors can view uploaded patient documents (e.g., test reports)

### 📢 Notification System

* Real-time toast notifications across dashboards
* Email notification on appointment approval
* Notification history (read/unread)

### 📊 Doctor Dashboard

* Upcoming appointments
* Medical inventory low stock alerts
* Export options for prescriptions/medical history
* Notification panel (unread count and latest messages)

### 📥 Admin Dashboard

* Manage doctors, patients, medicines
* View appointment stats and handle approvals
* Receive real-time alerts on new bookings

---

## 🛠️ Tech Stack

* **Backend**: Laravel 10+
* **Frontend**: Blade, Bootstrap 5 (Dark Theme)
* **Database**: MySQL
* **Email**: Laravel Mail (Mailtrap/Gmail supported)
* **Excel/PDF Export**: Laravel Excel

---

## 🔧 Setup Instructions

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/hospital-management.git
cd hospital-management
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update DB credentials and mail configuration in `.env`:

```env
DB_DATABASE=hospital_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 5. Serve the Project

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000)

---

## 🧪 Default Credentials

```
Admin:    admin@example.com / password
Doctor:   doctor1@example.com / password
Patient:  patient1@example.com / password
```

> You can update or create more users via Laravel Tinker or Admin Panel.

---

## 📂 File Structure Highlights

* `app/Http/Controllers/` – All controller logic
* `resources/views/` – Blade templates for dashboards
* `app/Models/` – Doctor, PatientProfile, Medicine, Appointment
* `routes/web.php` – Web routes by role
* `app/Notifications/` – Real-time & email notification classes

---

## ✨ Todo / Upcoming Features

* Billing and Invoice Management
* Live chat between doctor-patient
* Role-permission management with Spatie
* Notification via Pusher (WebSocket)

---

## 🤝 Contributing

Pull requests are welcome. For major changes, open an issue first to discuss what you would like to change.

---

## 📄 License

[MIT License](LICENSE)

---

## 🙌 Credits

Developed by Muhammad Bin Imran with Laravel ❤️ for Hospital Workflow Automation.
