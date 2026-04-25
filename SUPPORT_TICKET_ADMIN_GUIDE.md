# Support Ticket Admin Management - Implementation Guide

## Overview

This document describes the admin support ticket management system that allows administrators to manage user support tickets with status updates, password reset emails, and custom email responses.

## Architecture

### Layer Stack
```
Routes (web.php)
    ↓
Controller (AdminController)
    ↓
Service (SupportTicketService)
    ↓
Repository (SupportTicketRepository)
    ↓
Database (MySQL - support_tickets table)
```

## Components

### 1. Database Schema

**Table**: `support_tickets`

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | INT | ✗ | Primary key, auto-increment |
| user_id | INT | ✗ | Foreign key to users table |
| category | VARCHAR(255) | ✗ | Ticket category (password_change, forgot_password, general_inquiry, etc.) |
| message | LONGTEXT | ✗ | User's support request |
| status | VARCHAR(50) | ✗ | Current status: Open, In Progress, Resolved, Closed |
| admin_response | LONGTEXT | ✓ | Admin's response/notes (nullable) |
| created_at | TIMESTAMP | ✗ | Creation timestamp |
| updated_at | TIMESTAMP | ✗ | Last update timestamp |

### 2. API Endpoints

#### Get Ticket Details (AJAX)
```
GET /admin/api/support-tickets/{id}
Content-Type: application/json

Response:
{
  "ticket": {
    "id": 1,
    "user_id": 2,
    "category": "password_change",
    "message": "I forgot my password...",
    "status": "Open",
    "admin_response": null,
    "created_at": "2026-04-24T10:30:00Z",
    "updated_at": "2026-04-24T10:30:00Z",
    "email": "user@example.com",
    "user_name": "John Doe"
  }
}
```

#### Update Ticket Status
```
POST /admin/support-tickets/{id}/status
Content-Type: application/x-www-form-urlencoded

Parameters:
- status: "Open" | "In Progress" | "Resolved" | "Closed"
- _token: CSRF token (auto-included in form)

Response: Redirect back with success/error message
```

#### Send Password Reset Email
```
POST /admin/support-tickets/{id}/password-reset
Content-Type: application/x-www-form-urlencoded

Parameters:
- _token: CSRF token (auto-included in form)

Response: 
- Sends Laravel Password::sendResetLink() email to user
- Auto-marks ticket as "Resolved"
- Redirects back with success/error message
```

#### Send Custom Email
```
POST /admin/support-tickets/{id}/email
Content-Type: application/x-www-form-urlencoded

Parameters:
- email_subject: "Subject line" (min: 5 chars, max: unlimited)
- email_body: "Email body text" (min: 10 chars, max: unlimited)
- _token: CSRF token (auto-included in form)

Response: 
- Sends HTML email to ticket user
- Stores response in admin_response field
- Redirects back with success/error message
```

### 3. Frontend Components

#### Modal Structure
The ticket detail modal appears when admin clicks "View" on any ticket in the list.

**Modal ID**: `ticketModal`

**Contents**:
1. **Ticket Details Section** (read-only)
   - Ticket ID
   - User name and email
   - Category (formatted: password_change → "password change")
   - Current status (with color badge)
   - Submitted date
   - Full message text
   - Admin response history (if exists)

2. **Status Update Form**
   - Dropdown with 4 options: Open, In Progress, Resolved, Closed
   - Submit button
   - Auto-posts to `/admin/support-tickets/{id}/status`

3. **Category-Specific Action Forms** (conditionally displayed)

   **For password-related tickets** (password_change, forgot_password):
   - "Send Password Reset Email" button
   - Single-click action to send reset link
   - Auto-marks ticket as Resolved

   **For general inquiries** (general_inquiry, other, account_update, login_issue):
   - Email subject input field
   - Email body textarea (6 rows)
   - Send button
   - Stores response in database

#### JavaScript Functions

**`showTicketDetails(ticketId)`**
- Called when "View" button is clicked
- Fetches ticket JSON from API
- Populates modal with ticket data
- Detects ticket category and shows appropriate action form
- Sets form action URLs
- Opens modal with fade-in effect

**`closeTicketModal()`**
- Hides the modal
- Can be called by close button or by clicking outside

**Window Click Handler**
- Closes modal when clicking outside content area (on dark overlay)

## Testing Instructions

### Prerequisites
1. User must be logged in as admin (admin@example.com / password)
2. Support tickets must exist in database
3. Docker containers must be running (backend, db, vite)

### Test Workflow

1. **Navigate to Admin Dashboard**
   ```
   URL: http://localhost:8080/admin-dashboard?section=support-tickets
   OR navigate via menu
   ```

2. **View Ticket List**
   - Table shows all support tickets
   - Columns: ID, User, Category, Message (preview), Status, Submitted, Actions
   - Click "View" button on any ticket

3. **Modal Opens and Loads Ticket**
   - Modal displays with ticket details
   - Full message text is visible
   - Admin response shown if exists

4. **Test Status Update**
   - Select "In Progress" from status dropdown
   - Click "Update Status" button
   - Should redirect back with success message
   - Verify status changed in database:
     ```
     SELECT status FROM support_tickets WHERE id = 1;
     ```

5. **Test Password Reset (password_change tickets)**
   - Open ticket with category "password_change"
   - Click "Send Password Reset Email" button
   - Should see success message
   - Check logs for email sent notification
   - Ticket status should change to "Resolved"

6. **Test Email Composer (general inquiry tickets)**
   - Open ticket with category "general_inquiry"
   - Enter email subject (min 5 chars)
   - Enter email body (min 10 chars)
   - Click "Send Email" button
   - Should see success message
   - Verify admin_response field updated:
     ```
     SELECT admin_response FROM support_tickets WHERE id = {id};
     ```

### Database Verification

Check ticket was updated:
```sql
SELECT id, status, admin_response, updated_at 
FROM support_tickets 
WHERE id = 1;
```

### Common Issues

**Issue**: Modal doesn't open
- **Cause**: API not returning data
- **Fix**: Check browser console for fetch errors, verify ticket exists in DB

**Issue**: Form submission doesn't work
- **Cause**: CSRF token missing or invalid
- **Fix**: Ensure form includes @csrf (auto-included in blade template)

**Issue**: Email not sending
- **Cause**: Mail configuration not set up
- **Fix**: Check .env MAIL_* settings and Laravel logs

**Issue**: "User not found" error
- **Cause**: User deleted after ticket created
- **Fix**: Ensure user_id in ticket still references valid user

## Integration with Existing System

### SupportTicketEntity
Located in: `app/Entities/SupportTicketEntity.php`
- Provides type-safe getters for all ticket properties
- Used by service and repository layers
- Ensures data consistency

### SupportTicketService
Located in: `app/Services/SupportTicketService.php`
- Business logic: validation, hydration
- Key method: `getTicketById($id)` - returns SupportTicketEntity
- Key method: `updateTicketStatus($id, $status)` - updates DB
- Key method: `respondToTicket($id, $response)` - marks Resolved

### SupportTicketRepository
Located in: `app/Repositories/SupportTicketRepository.php`
- Direct database access via mysqli
- Methods: save(), getAll(), getById(), updateStatus(), addResponse()
- Uses JOIN with users table to get user details

### AdminController
Located in: `app/Http/Controllers/AdminController.php`
- Dependency injection: SupportTicketService
- Methods:
  - `supportTickets()` - displays list
  - `getTicketDetails($id)` - JSON API
  - `updateSupportTicketStatus()` - status update
  - `sendPasswordResetEmail()` - password reset
  - `sendSupportEmail()` - custom email

## Files Modified/Created

### Modified Files
1. `routes/web.php` - Added 4 new routes
2. `app/Http/Controllers/AdminController.php` - Added 4 new methods
3. `resources/views/admin-dashboard.blade.php` - Added modal and JavaScript

### Files Not Modified (Already Existed)
- `app/Entities/SupportTicketEntity.php`
- `app/Repositories/SupportTicketRepository.php`
- `app/Services/SupportTicketService.php`
- Database migration
- Database seeders

## Security Considerations

✓ All routes protected by `auth` middleware
✓ CSRF tokens required on all forms
✓ Input validation on server side
✓ Error handling prevents exposure of sensitive data
✓ User lookup validates ticket belongs to correct user
✓ Status validation ensures only valid values accepted
✓ Email subject/body validated for minimum length

## Performance Notes

- Modal uses AJAX for single-page experience (no reload)
- API endpoint returns only necessary ticket data
- Database query uses user JOIN for efficiency
- Forms submit via POST (not AJAX) to leverage Laravel's redirect with messages
- No N+1 queries (single query per operation)

## Future Enhancements

1. Bulk status updates for multiple tickets
2. Ticket assignment to specific admins
3. Priority levels for tickets
4. Ticket search/filter functionality
5. Email templates for standardized responses
6. Ticket SLA tracking and reminders
7. Notification history for audit trail
8. File attachments support
9. Ticket categories management interface
10. Response time analytics

## Support

For issues or questions about this implementation, refer to:
- Session memory: `/memories/session/support-ticket-admin-implementation.md`
- Code comments in AdminController.php
- Blade template inline styles and JavaScript

---

**Last Updated**: 2026-04-25
**Status**: Complete and ready for testing
