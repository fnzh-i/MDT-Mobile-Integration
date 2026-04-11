# Mobile-Data-Terminal (Mobile Integration)
This platform optimizes civilian data management by bridging a powerful Laravel API with a flexible mobile framework. The result is a high-speed interface that empowers field officers to access vital information instantly, ensuring efficient performance of their duties.

🔗 Repository: https://github.com/fnzh-i/MDT-Mobile-Integration

## 🚀 Tech Stack
**Backend:** Laravel 11 (PHP 8.2+)

**Environment:** Docker & Docker Compose (MariaDB, PHP-FPM, Vite)

**API:** RESTful API with Laravel Sanctum Authentication

**Database:** MariaDB (MySQL Compatible)

**Mobile Handling:** Retrofit (Android/Kotlin) with real-time data synchronization

---

## 🐳 Docker Setup (Quick Start)
The project is fully containerized. To get started, ensure you have [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed.

1. **Clone and Enter:**
   ```bash
   git clone https://github.com/fnzh-i/MDT-Mobile-Integration
   cd MDT-Mobile-Integration
   ```

2. **Initialize Environment:**
   ```bash
   cp .env.example .env
   ```

3. **Build and Run:**
   ```bash
   docker-compose up -d --build
   ```

4. **Run Migrations & Seeders:**
   ```bash
   docker-compose exec backend php artisan migrate --seed
   ```
   *Your API is now live at `http://localhost:8080`*

---

## 🧩 Project Status
> 🚧 **In Development: We are currently optimizing the API handshake protocols and refining the mobile UI for low-latency data fetching.**
            and additional functionalities will be introduced in future updates.
---

## 🗺️ Roadmap

🖥️ **Backend & API (Laravel)**

- [x] Core Architecture: Built on Laravel for scalable CSR routing and raw SQL queries.

- [x] RESTful API Integration: Standardized JSON endpoints for civilian profiles, vehicle records, and incident reports.

- [x] Secure Authentication: Implemented Role-Based Access Control (RBAC) and API token management.

- [x] Data Integrity: Integrated logging, error handling, and request validation to ensure high-quality data input.

📱 **Mobile & Frontend Handling**

- [x] Mobile Data Fetching: Optimized API consumers using asynchronous fetching to handle real-time civilian data requests.

- [x] Responsive UI: Hybrid-ready interface designed for both desktop terminals and mobile field devices.

- [ ] State Management: Added loading states and local caching to ensure the app remains functional during intermittent connectivity.

- [ ] Push Notifications: (Planned) Real-time alerts for high-priority civilian lookups.

🔌 **API Usage**
To fetch civilian data within the mobile application, the system utilizes a GET request to the Laravel backend:

---

## 🔌 API Connectivity
When connecting from a mobile device to the Dockerized backend:
- **Android Emulator:** Use `http://[YOUR_MAC_IP]:8080/api/`
- **Physical Device:** Use `http://[YOUR_MAC_IP]:8080/api/`

---

### 👨‍💻 Authors

Author: **@fnzh-i**

Co-Authors: **@ravendr17** & **@JR-Choa**

Role: Web & Software Developers


📅 Started: February 2026

💬 "Romanticize the process — make the grind exciting, not exhausting"