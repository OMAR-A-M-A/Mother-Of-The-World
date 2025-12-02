# Mother-Of-The-World

> A dynamic web application serving as a comprehensive tourism guide for Egypt, featuring a categorized directory of historical, beach, and sea attractions, powered by a custom Admin Dashboard.

## 📖 Table of Contents
- [About the Project](#-about-the-project)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Database Schema](#-database-schema)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
- [Screenshots](#-screenshots)

---

## 🧐 About the Project
**Egypt Tourism Guide** is a Full-Stack web application designed to digitize tourism data in Egypt. It allows tourists to explore destinations based on **Governorates** or **Categories** (Historical, Beaches, Sea). 

The system includes a secure **Admin Panel** that allows administrators to manage content (CRUD operations) dynamically without touching the code.

---

## 🚀 Features

### 🟢 Front-End (User Side)
* **Home Page:** Interactive hero section, featured governorates, and latest additions.
* **Explore by Governorate:** Browse places specific to cities like Cairo, Alexandria, Luxor, etc.
* **Smart Filtering:** Filter places within a governorate by type (Historical, Beach, Sea).
* **Category Views:** Dedicated pages to browse specific interests (e.g., "All Beaches in Egypt").
* **Place Details:** Comprehensive view with image gallery, pricing, opening hours, and Google Maps integration.

### 🔴 Back-End (Admin Panel)
* **Secure Authentication:** Admin login system.
* **Dashboard:** Real-time statistics (Total Places, Governorates, Categories).
* **Manage Governorates:** Add, edit, or delete governorates.
* **Manage Places (CMS):** * Add new places with details (Price, Description, Location).
    * Upload images dynamically.
    * Assign places to categories and governorates.
* **Database Management:** Full CRUD operations reflecting instantly on the front end.

---

## 🛠 Tech Stack

* **Front-End:** HTML5, CSS3, Bootstrap 5, JavaScript (ES6).
* **Back-End:** PHP (Native).
* **Database:** MySQL (Relational Database).
* **Server:** Apache (via XAMPP/WAMP).

---

## 🗄 Database Schema
The system is built on a relational database consisting of 5 main tables:
1.  `admins` (Auth)
2.  `governorates` (Locations)
3.  `categories` (Types: Historical, Beach, Sea)
4.  `places` (Main Data)
5.  `place_images` (Gallery)

---

## 📂 Project Structure

```text
/egypt-tourism-guide
├── /admin                  # Admin Panel files
│   ├── dashboard.php
│   ├── manage_places.php
│   └── ...
├── /assets                 # Static assets
│   ├── /css
│   ├── /js
│   └── /uploads            # User uploaded images
├── /includes               # Reusable PHP components
│   ├── db_connect.php
│   ├── header.php
│   └── footer.php
├── index.php               # Home Page
├── governorates.php        # All Governorates
├── single_governorate.php  # Specific Governorate view
├── place_details.php       # Single Place view
└── database.sql            # SQL file for import
