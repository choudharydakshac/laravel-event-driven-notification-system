# Event-Driven Notification System (Laravel)

## 🎯 Goal

Build a **production-grade, event-driven notification system** similar to what modern SaaS platforms use internally. The system should be **decoupled, scalable, queue-based**, and easy to extend with new notification channels.

This project demonstrates **senior-level backend skills** without exposing any proprietary SaaS logic.

---

## 🧱 High-Level Architecture

```
User Action / System Trigger
        │
        ▼
   Laravel Event
        │
        ▼
   Event Listener
        │
        ▼
 Notification Dispatcher
        │
 ┌──────┼────────┐
 ▼      ▼        ▼
Email  Database  (Future: SMS / Slack / Push)
(Queued Jobs via Redis)
```

---

## ✅ Core Requirements (All Included)

### Functional

* Event-driven architecture (Events & Listeners)
* Asynchronous notification delivery (Queues)
* Multiple channels (Email + Database)
* User notification preferences
* Retry & failure handling
* Rate limiting per user
* Notification logs

### Non‑Functional

* Decoupled & extensible design
* Idempotent processing
* Fault tolerance
* Clean code & SOLID principles

---

## 🛠 Tech Stack

* **Laravel 10/11**
* **Redis** (queues + rate limit)
* **MySQL**
* **Laravel Queue Workers**
* **Mail (Log / SMTP)**

---

## 📂 Folder Structure (Important for Interviews)

```
app/
 ├── Events/
 │    └── UserRegistered.php
 ├── Listeners/
 │    └── SendUserNotifications.php
 ├── Jobs/
 │    ├── SendEmailNotificationJob.php
 │    └── StoreDatabaseNotificationJob.php
 ├── Notifications/
 │    └── Channels/
 │         ├── EmailChannel.php
 │         └── DatabaseChannel.php
 ├── Services/
 │    ├── NotificationDispatcher.php
 │    └── RateLimitService.php
 ├── Models/
 │    ├── NotificationLog.php
 │    └── UserNotificationPreference.php
```

---

## 📊 Database Schema

### users

```
id
name
email
```

### user_notification_preferences

```
id
user_id
channel (email, database)
enabled (boolean)
created_at
```

### notification_logs

```
id
user_id
event
channel
status (pending/sent/failed)
error_message
created_at
```

---

## 🔔 Example Event

**UserRegistered Event**

* Triggered when a new user signs up
* Payload: user_id, email

```
UserRegistered::dispatch($user);
```

---

## 🎧 Listener Logic (High Level)

Listener responsibilities:

* Read user preferences
* Apply rate limiting
* Dispatch channel-specific jobs

Key point:

> Listener contains **NO channel-specific logic**

---

## ⚙️ Notification Dispatcher (Core Component)

Responsibilities:

* Accept event + user
* Resolve enabled channels
* Dispatch jobs
* Maintain consistency

Benefits:

* Easy to add new channels
* Clean separation of concerns

---

## 🔁 Queue Strategy

* Redis as queue driver
* Each channel handled by **separate job**
* Retry: 3 attempts
* Backoff strategy
* Failed jobs logged to DB

---

## 🚦 Rate Limiting Strategy

* Redis-based per-user limit
* Example: Max 5 notifications / minute

```
notification:user:{id}
```

If exceeded:

* Skip dispatch
* Log as `rate_limited`

---

## 🧪 Failure Handling

* Try/Catch inside Jobs
* On failure:

  * Update notification_logs
  * Store error message
* Failed jobs recoverable

---

## 🧩 Extending the System (Interview Gold)

Easy to add:

* SMS channel
* Slack channel
* Push notifications
* Webhooks

Just add:

* New Channel class
* New Job
* Register in Dispatcher

---

## 📘 README Talking Points (What to Say in Interviews)

* Why event-driven architecture
* Why queues are required
* How idempotency is ensured
* How system scales
* How failures are handled
* How rate limiting protects system

---

## 📌 Suggested Repo Name

```
laravel-event-driven-notification-system
```

---

## ⭐ What Recruiters Will Notice

* Clean architecture
* Real SaaS-style backend
* Async processing
* Redis usage
* Senior design decisions

---

Next we can:

* Implement **actual code** step by step
* Write **README.md** fully
* Add **API endpoints**
* Add **unit tests**

Tell me the next step you want to work on.

# 📘 README – Event-Driven Notification System

## 📌 Overview

This repository contains a **production-style, event-driven notification system** built with **Laravel 11**.
The project demonstrates how modern SaaS backends handle notifications in a **decoupled, scalable, and fault-tolerant** way.

The goal of this project is **architecture clarity**, not UI. It is intentionally backend-focused and designed for **remote backend roles**.

---

## 🚀 Key Features

* Event-driven architecture (Laravel Events & Listeners)
* Asynchronous processing using queues
* Multiple notification channels (Email, Database)
* User notification preferences
* Per-user rate limiting
* Idempotent event handling (duplicate protection)
* Failure handling & retries
* Notification audit logs
* Clean, extensible service-oriented design

---

## 🧠 High-Level Architecture

```
HTTP Request / System Action
        ↓
   Laravel Event
        ↓
     Listener
        ↓
Notification Dispatcher
        ↓
Rate Limiter + Idempotency
        ↓
 Queue Jobs (per channel)
        ↓
 Notification Delivery
```

This separation ensures **loose coupling**, **scalability**, and **easy extensibility**.

---

## 🛠 Tech Stack

* Laravel 12
* MySQL
* Queue system (database / Redis-ready)
* Laravel Events & Jobs
* Laravel Cache (Redis-compatible)

---

## 📂 Project Structure

```
app/
 ├── Events/                 # Domain events
 ├── Listeners/              # Event listeners
 ├── Jobs/                   # Async notification jobs
 ├── Services/               # Core business logic
 ├── Models/                 # Eloquent models
 └── Http/Controllers/Api    # API endpoints
```

This structure mirrors **real SaaS backend systems**.

---

## 🧩 Core Concepts Explained

### 🔔 Event-Driven Design

User actions (like registration) emit events.
Listeners react to these events without knowing implementation details.

**Why?**

* Loose coupling
* Easy feature expansion
* Clean domain boundaries

---

### ⚙️ Notification Dispatcher

The dispatcher:

* Reads user preferences
* Applies rate limiting
* Ensures idempotency
* Dispatches channel-specific jobs

This keeps listeners **thin** and **maintainable**.

---

### 🚦 Rate Limiting

Per-user notification limits are enforced using Laravel Cache.

Example:

* Max 5 notifications per minute per user

This prevents spam and protects system resources.

---

### 🔁 Idempotency

Duplicate events are prevented using a deterministic `event_key`:

```
{event}:{user_id}
```

A database-level unique constraint ensures **safe retries** and **no duplicate notifications**.

---

### 🧯 Failure Handling

* Jobs retry automatically with backoff
* Failed notifications are logged
* Attempts are tracked

This allows safe recovery and debugging without losing data.

---

## 🌐 API Endpoints

### Register User & Trigger Notification

```
POST /api/v1/users/register
```

Payload:

```json
{
  "name": "John Doe",
  "email": "john@example.com"
}
```

---

### View Notification Logs

```
GET /api/v1/notifications
```

Returns paginated notification audit logs.

---

## 🧪 Local Setup

```bash
git clone <repo-url>
cd project
composer install
php artisan migrate
php artisan serve
```

(Optional queue worker)

```bash
php artisan queue:work
```

---

## 🔮 Planned Enhancements

* SMS / Push / Slack channels
* Authentication (Sanctum)
* Webhooks
* Metrics dashboard
* Dedicated dead-letter queue

---

## 💼 Interview Talking Points

* Why event-driven architecture
* How idempotency is enforced
* Why queues are necessary
* How rate limiting protects systems
* Failure recovery strategy
* How the system scales

---

## ⭐ Why This Project Matters

This repository demonstrates **real-world backend engineering decisions**, not tutorial-level CRUD.

It reflects how **production SaaS platforms** handle notifications internally.

---

## 👤 Author

Backend-focused Laravel developer with experience in:

* SaaS platforms
* Event-driven systems
* Distributed backend architecture
* High-scale PHP applications

---

If you are reviewing this project for a backend role, feel free to reach out.
