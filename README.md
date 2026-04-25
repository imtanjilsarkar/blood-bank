# 🩸 LifeFlow - Blood Bank Management System

<div align="center">

![Version](https://img.shields.io/badge/version-2.0.0-red?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)

**A Modern, Full-Stack Web Application for Blood Donation Management**

</div>

---

##  About The Project

**LifeFlow** is a comprehensive, production-ready blood bank management system designed to bridge the gap between blood donors, hospitals, and administrators. The platform enables real-time blood stock tracking, request management, donation scheduling, and analytics - all wrapped in a beautiful, modern glassmorphism interface.

###  Key Features

####  Admin Dashboard
- Complete system overview with interactive analytics charts
- Manage blood requests (approve/reject with one click)
- Real-time blood stock management with color-coded status
- Search and filter functionality for requests
- Blood stock update with AJAX (no page reload)

####  Hospital Dashboard
- Request blood for patients with form validation
- Track request status in real-time with progress timeline
- View complete request history with detailed status
- Real-time stock availability check before requesting

####  Donor Dashboard
- Schedule blood donations with preferred date selection
- Track donation history with detailed records
- Check eligibility status with 90-day rule enforcement
- View impact statistics (lives saved calculation)

####  Public Website
- Modern, responsive landing page with animations
- Real-time blood stock display from database
- Professional About Us & Contact pages
- User registration and authentication system
- Google Maps integration on contact page

---

##  Quick Setup (5 Minutes)

### Step 1: Copy to XAMPP
```bash
# Copy the entire BLOOD-BANK folder to:
C:\xampp\htdocs\BLOOD-BANK
Step 2: Start Servers
Open XAMPP Control Panel

Click Start for Apache

Click Start for MySQL

Step 3: Create Database
Open browser and go to: http://localhost/phpmyadmin

Click "New" to create database

Database name: blood_bank

Click "Create"

Step 4: Import Database
Click on blood_bank database

Click "Import" tab

Click "Choose File" and select database_schema.sql

Click "Go" at the bottom
```

#### Project Structure

```bash

BLOOD-BANK/
│
├── 📄 index.php                 # Homepage
├── 📄 about.php                 # About Us page
├── 📄 contact.php               # Contact page with Google Maps
├── 📄 login.php                 # Login with demo buttons
├── 📄 register.php              # Registration page
├── 📄 logout.php                # Logout script
├── 📄 database_schema.sql       # Complete database schema
├── 📄 README.md                 # Documentation
│
├── 📁 database/
│   └── 📄 connection.php        # Database connection settings
│
└── 📁 dashboard/
    ├── 📄 admin.php              # Admin dashboard
    ├── 📄 admin_requests.php     # Manage requests
    ├── 📄 blood_stock.php        # View stock
    ├── 📄 update_stock.php       # Update inventory
    ├── 📄 hospital.php           # Hospital dashboard
    ├── 📄 request_blood.php      # Request blood
    ├── 📄 my_requests.php        # View my requests
    ├── 📄 donor.php              # Donor dashboard
    ├── 📄 donation_history.php   # Donation records
    └── 📄 request_donation.php   # Schedule donation
```
#### License

MIT License

Copyright (c) 2026 LifeFlow Blood Bank System

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions...

Full license text in repository.

