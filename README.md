# EventMaster - Event & Vendor Planner App

A modern, full-featured Event Planning and Vendor Tracking web application built with **CodeIgniter 4 (v4.7.5)**, **PHP 8.2+**, **SQLite / MySQL**, and **Bootstrap 5.3**.

Designed specifically for event managers and coordinators to seamlessly organize events and manage service providers across multiple categories (**Catering**, **Venues**, **DJs & Live Music**, **Photography & Video**, **Lighting & AV**, **Floral & Decor**, and more).

---

## 🌟 Key Features

### 1. Events Management
- **Complete Event Lifecycle**: Manage events across multiple statuses (`draft`, `planning`, `confirmed`, `in_progress`, `completed`, `cancelled`).
- **Diverse Event Types**: Weddings, Corporate Summits, Birthday Galas, Conferences, Concerts, Anniversaries, and Private Parties.
- **Client & Venue Tracking**: Client contact info, expected guest headcount, schedule timeline (start/end times), and location.
- **Live Budget & Variance Tracking**: Real-time calculation of overall target budget vs. allocated provider costs, deposits paid, and remaining balances, complete with color-coded budget progress bars.

### 2. Service Providers Directory (Vendors)
- **Multi-Category Organization**: Categorized into Catering, Venues, DJs/Music, Photography, Stage/AV, Security, Floristry, etc.
- **Provider Profiles**: Contact persons, direct call/email links, website links, base location, ratings (1-5 stars), price tiers (`$`, `$$`, `$$$`, `$$$$`), and service specialties.
- **Vendor Booking History**: Every provider profile displays a complete history of events they have been hired for, agreed compensation, contract status, and payment progress.

### 3. Event-Provider Assignment & Financials
- **Assign multiple vendors** to any event with customized role descriptions.
- **Agreed Fees & Deposits**: Track agreed contract costs, upfront deposits paid, and payment status (`pending`, `partial`, `paid`).
- **Contract Tracking**: Keep track of contract phases (`draft`, `sent`, `signed`, `completed`).

### 4. Interactive Checklist & Tasks
- **Task Milestones**: Create event checklist items with due dates and priority levels (`high`, `medium`, `low`).
- **Provider Association**: Optionally associate checklist tasks directly with the hired provider (e.g. menu tasting with Catering, sound check with DJ).
- **One-Click AJAX Status Toggle**: Check off tasks directly from the dashboard or event details without full page reloads.

### 5. Interactive Dashboard
- Metric cards showing active upcoming events, total active budgets, committed vendor expenses, and pending invoices.
- Upcoming events timeline with countdown badges.
- Pending task quick checklist.
- Preferred vendor spotlights and category browser.

---

## 🚀 Quickstart Guide (Docker)

The application includes a pre-configured Docker environment with Apache, PHP 8.2, all required CodeIgniter extensions (`intl`, `mbstring`, `pdo_sqlite`, `pdo_mysql`, `gd`, `zip`), and Composer.

### 1. Start the Application
Run Docker Compose from the project directory:
```bash
docker compose up -d
```
The application will be live at:
👉 **[http://localhost:8080](http://localhost:8080)**

### 2. Database Migrations & Seeders
The app uses SQLite out of the box (stored in `writable/event_planner.db`), requiring **zero** external database setup.

To run migrations and populate rich sample data (sample weddings, corporate events, caterers, venues, DJs, AV crews):
```bash
# Run migrations
docker exec event_planner_app php spark migrate

# Seed sample events & providers
docker exec event_planner_app php spark db:seed DatabaseSeeder
```

---

## 🛠️ Project Architecture

```
event_planner/
├── app/
│   ├── Config/
│   │   ├── App.php             # Base URL & clean index routing
│   │   ├── Database.php        # SQLite3 / MySQL connection configs
│   │   └── Routes.php          # RESTful application route definitions
│   ├── Controllers/
│   │   ├── Dashboard.php       # Dashboard metrics & upcoming timeline
│   │   ├── Events.php          # Event CRUD, provider assignment, tasks
│   │   ├── Providers.php       # Vendor directory & booking history
│   │   └── Categories.php      # Service category management
│   ├── Database/
│   │   ├── Migrations/         # 5 database migrations
│   │   └── Seeds/
│   │       └── DatabaseSeeder.php # Rich sample data
│   ├── Models/
│   │   ├── CategoryModel.php   # Categories with vendor count
│   │   ├── ProviderModel.php   # Filtered providers with booking history
│   │   ├── EventModel.php      # Events with budget & financial stats
│   │   ├── EventProviderModel.php # Event-to-provider pivot tracking
│   │   └── EventTaskModel.php  # Event checklist milestones
│   └── Views/
│       ├── layouts/main.php    # Responsive Bootstrap 5.3 master layout
│       ├── dashboard/index.php # Main KPI dashboard
│       ├── events/             # List, show, form views
│       ├── providers/          # Directory, profile, form views
│       └── categories/         # Categories management view
├── docker-compose.yml          # Container configuration (port 8080)
├── Dockerfile                  # PHP 8.2-Apache with CI4 extensions
└── .env                        # Development environment settings
```

---

## 📋 Available Routes

| Method | URI | Description |
| :--- | :--- | :--- |
| `GET` | `/` | Dashboard with KPIs, upcoming events & tasks |
| `GET` | `/events` | Directory of events with search & filters |
| `GET` | `/events/new` | Create event form |
| `POST` | `/events` | Store new event |
| `GET` | `/events/{id}` | Event overview, budget breakdown, vendor table & tasks |
| `GET` | `/events/{id}/edit`| Edit event details |
| `POST`| `/events/{id}` | Update event |
| `POST`| `/events/{id}/delete` | Delete event |
| `POST`| `/events/{id}/providers` | Assign a vendor with fees & payment terms |
| `POST`| `/events/{id}/providers/{pid}` | Update assigned vendor fee/deposit/status |
| `POST`| `/events/{id}/providers/{pid}/delete` | Remove vendor from event |
| `POST`| `/events/{id}/tasks` | Add task to event checklist |
| `POST`| `/events/tasks/{id}/toggle` | AJAX toggle task todo / completed |
| `GET` | `/providers` | Directory of service providers (caterers, DJs, venues) |
| `GET` | `/providers/new` | Register new service provider |
| `POST`| `/providers` | Store provider profile |
| `GET` | `/providers/{id}` | Provider profile & event booking history |
| `GET` | `/providers/{id}/edit` | Edit provider profile |
| `POST`| `/providers/{id}` | Update provider profile |
| `POST`| `/providers/{id}/delete` | Delete provider |
| `GET` | `/categories` | Service categories management |
| `POST`| `/categories` | Add new specialty category |

---

## 🧪 Testing and Commands

You can run CodeIgniter's CLI tool (`spark`) anytime inside the container:
```bash
# Check application routes
docker exec event_planner_app php spark routes

# View database tables
docker exec event_planner_app php spark db:table events
docker exec event_planner_app php spark db:table providers

# Refresh database schema & re-seed
docker exec event_planner_app php spark migrate:refresh
docker exec event_planner_app php spark db:seed DatabaseSeeder
```
