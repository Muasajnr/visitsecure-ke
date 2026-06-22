-- =========================================================
-- VisitSecure KE - Database Schema
-- Multi-tenant Visitor & Booking Management System
-- =========================================================

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS visitsecure_ke CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE visitsecure_ke;

-- =========================================================
-- 1. ORGANIZATIONS (Tenants)
-- =========================================================
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    subscription_status ENUM('trial','active','suspended','expired') NOT NULL DEFAULT 'trial',
    subscription_plan VARCHAR(50) DEFAULT 'basic',
    subscription_expires_at DATE DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================
-- 2. BUILDINGS (belong to an organization)
-- e.g. Bihi Towers, CMS Africa Building
-- =========================================================
CREATE TABLE buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_buildings_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 3. FLOORS (belong to a building)
-- =========================================================
CREATE TABLE floors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT NOT NULL,
    building_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,         -- e.g. "1st Floor", "Floor 6"
    floor_number INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_floors_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_floors_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 4. FIRMS / ROOMS (belong to a floor)
-- e.g. a firm's office, a conference room "Conference 6"
-- =========================================================
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT NOT NULL,
    floor_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,          -- e.g. "Conference 6", "Suite 4B"
    room_type ENUM('office','conference','reception','other') NOT NULL DEFAULT 'office',
    firm_name VARCHAR(150) DEFAULT NULL, -- name of the firm occupying it, if any
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_rooms_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_rooms_floor FOREIGN KEY (floor_id) REFERENCES floors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 5. USERS (all human accounts: super admin, org admin, gateman, host, event manager)
-- Visitors who self-register also live here with role='visitor'
-- =========================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    org_id INT DEFAULT NULL,             -- NULL for super_admin (platform-level)
    role ENUM('super_admin','org_admin','gateman','host','event_manager','visitor') NOT NULL,
    room_id INT DEFAULT NULL,            -- which room/firm a host belongs to
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    id_number VARCHAR(50) DEFAULT NULL,  -- national ID, for visitors/hosts if needed
    password_hash VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    email_verified_at DATETIME DEFAULT NULL,
    last_login_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_org_email (org_id, email),
    CONSTRAINT fk_users_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_users_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================
-- 6. EVENTS (scheduled by event managers / org admins)
-- e.g. a conference at CMS Africa Building, Floor 1, Conference 6
-- =========================================================
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    org_id INT NOT NULL,
    building_id INT NOT NULL,
    floor_id INT DEFAULT NULL,
    room_id INT DEFAULT NULL,
    created_by INT NOT NULL,             -- user id of event manager/admin
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    max_visitors INT DEFAULT NULL,
    status ENUM('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_events_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE CASCADE,
    CONSTRAINT fk_events_floor FOREIGN KEY (floor_id) REFERENCES floors(id) ON DELETE SET NULL,
    CONSTRAINT fk_events_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    CONSTRAINT fk_events_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 7. VISITS (the core booking / gate-pass record)
-- Covers: self-registered visitor, host-registered visitor,
-- event visitor, and unregistered walk-in registered by gateman.
-- =========================================================
CREATE TABLE visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    org_id INT NOT NULL,
    building_id INT NOT NULL,
    floor_id INT DEFAULT NULL,
    room_id INT DEFAULT NULL,
    event_id INT DEFAULT NULL,                 -- linked event, if any

    -- visitor identity (visitor may or may not have a users row)
    visitor_user_id INT DEFAULT NULL,          -- FK to users if visitor has an account
    visitor_name VARCHAR(150) NOT NULL,
    visitor_phone VARCHAR(30) NOT NULL,
    visitor_email VARCHAR(150) DEFAULT NULL,
    visitor_id_number VARCHAR(50) DEFAULT NULL,
    visitor_company VARCHAR(150) DEFAULT NULL,
    visitor_photo VARCHAR(255) DEFAULT NULL,

    -- host being visited
    host_id INT DEFAULT NULL,                  -- FK to users (role=host)
    purpose VARCHAR(255) DEFAULT NULL,

    -- who created this booking
    created_by INT DEFAULT NULL,               -- users.id (gateman, host, admin, or visitor themself)
    source ENUM('self','host','gateman','event','admin') NOT NULL DEFAULT 'self',

    -- gate pass / approval flow
    status ENUM('pending','approved','rejected','checked_in','checked_out','expired','cancelled') NOT NULL DEFAULT 'pending',
    is_walk_in TINYINT(1) NOT NULL DEFAULT 0,   -- 1 = unregistered visitor registered at gate

    qr_code VARCHAR(255) DEFAULT NULL,          -- unique token encoded in QR
    qr_image_path VARCHAR(255) DEFAULT NULL,

    scheduled_start DATETIME DEFAULT NULL,
    scheduled_end DATETIME DEFAULT NULL,

    approved_by INT DEFAULT NULL,               -- host/admin who approved
    approved_at DATETIME DEFAULT NULL,
    rejected_reason VARCHAR(255) DEFAULT NULL,

    checked_in_at DATETIME DEFAULT NULL,
    checked_in_by INT DEFAULT NULL,              -- gateman user id
    checked_out_at DATETIME DEFAULT NULL,
    checked_out_by INT DEFAULT NULL,

    notes TEXT DEFAULT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_visits_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_visits_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE CASCADE,
    CONSTRAINT fk_visits_floor FOREIGN KEY (floor_id) REFERENCES floors(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_visitor_user FOREIGN KEY (visitor_user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_host FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_approved_by FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_checked_in_by FOREIGN KEY (checked_in_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_visits_checked_out_by FOREIGN KEY (checked_out_by) REFERENCES users(id) ON DELETE SET NULL,

    INDEX idx_visits_qr (qr_code),
    INDEX idx_visits_status (status),
    INDEX idx_visits_org_building (org_id, building_id)
) ENGINE=InnoDB;

-- =========================================================
-- 8. NOTIFICATIONS (in-app + email log)
-- =========================================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT DEFAULT NULL,
    user_id INT NOT NULL,                 -- recipient
    visit_id INT DEFAULT NULL,
    type VARCHAR(50) NOT NULL,            -- e.g. 'visit_approved','visit_checked_in','new_booking'
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    channel ENUM('app','email','both') NOT NULL DEFAULT 'both',
    email_status ENUM('not_applicable','pending','sent','failed') NOT NULL DEFAULT 'not_applicable',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_notifications_visit FOREIGN KEY (visit_id) REFERENCES visits(id) ON DELETE CASCADE,
    CONSTRAINT fk_notifications_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 9. AUDIT LOG (for analytics / accountability)
-- =========================================================
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT DEFAULT NULL,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================
-- 10. SETTINGS (per organization, e.g. SMTP overrides)
-- =========================================================
CREATE TABLE org_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_id INT NOT NULL UNIQUE,
    smtp_host VARCHAR(150) DEFAULT NULL,
    smtp_port INT DEFAULT 587,
    smtp_username VARCHAR(150) DEFAULT NULL,
    smtp_app_password VARCHAR(255) DEFAULT NULL,
    smtp_from_name VARCHAR(150) DEFAULT NULL,
    smtp_from_email VARCHAR(150) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orgsettings_org FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 11. PASSWORD RESETS
-- =========================================================
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_resets_token (token)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS=1;

-- =========================================================
-- SEED DATA: Super Admin + Demo Organization
-- Default super admin password: Admin@123  (CHANGE AFTER FIRST LOGIN)
-- Password hash below = bcrypt('Admin@123')
-- Run database/seed.php after import for full demo users (all roles).
-- =========================================================
INSERT INTO organizations (uuid, name, slug, email, phone, address, subscription_status, is_active)
VALUES (UUID(), 'VisitSecure Platform', 'platform', 'platform@visitsecure.ke', '0700000000', 'Nairobi, Kenya', 'active', 1);

INSERT INTO users (uuid, org_id, role, full_name, email, password_hash, is_active, email_verified_at)
VALUES (UUID(), NULL, 'super_admin', 'Platform Super Admin', 'admin@visitsecure.ke',
'$2y$10$9ruFnSc60U0W.3bKxcEAP.TF.Y0iOUZOgsZa.vCnX9D3sfA4QlXnS', 1, NOW());

-- Demo organization for testing (Bihi Towers example)
INSERT INTO organizations (uuid, name, slug, email, phone, address, subscription_status, is_active)
VALUES (UUID(), 'Bihi Properties Ltd', 'bihi-properties', 'info@bihiproperties.co.ke', '0711111111', 'Bihi Towers, Nairobi CBD', 'trial', 1);
