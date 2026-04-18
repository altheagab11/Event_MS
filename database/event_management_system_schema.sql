-- Event Management System database schema
-- Generated from the ERD image provided by the user.
-- Note:
-- The image shows several columns as ENUM but does not specify the allowed values.
-- Reasonable placeholder ENUM values were used below. Adjust them if your project needs different ones.

CREATE DATABASE IF NOT EXISTS event_ms;
USE event_ms;

-- =========================================================
-- 1) USERS
-- =========================================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('participant', 'organizer', 'evaluator', 'admin') NOT NULL
) ENGINE=InnoDB;

-- =========================================================
-- 2) EVENTS
-- =========================================================
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    event_date DATE NOT NULL
) ENGINE=InnoDB;

-- =========================================================
-- 3) REGISTRATIONS
-- =========================================================
CREATE TABLE registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
    CONSTRAINT fk_registrations_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 4) ATTENDANCE
-- =========================================================
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    check_in_time DATETIME NULL,
    check_out_time DATETIME NULL,
    CONSTRAINT fk_attendance_registration
        FOREIGN KEY (registration_id) REFERENCES registrations(registration_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 5) DIGITAL IDS
-- =========================================================
CREATE TABLE digital_ids (
    digital_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    qr_code VARCHAR(255) NOT NULL,
    issued_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_digital_ids_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_digital_ids_event
        FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 6) PAPERS
-- =========================================================
CREATE TABLE papers (
    paper_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('submitted', 'under_review', 'accepted', 'rejected') NOT NULL DEFAULT 'submitted',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_papers_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_papers_event
        FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- 7) EVALUATIONS
-- =========================================================
CREATE TABLE evaluations (
    evaluation_id INT AUTO_INCREMENT PRIMARY KEY,
    paper_id INT NOT NULL,
    evaluator_id INT NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    comment TEXT,
    evaluated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_evaluations_paper
        FOREIGN KEY (paper_id) REFERENCES papers(paper_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_evaluations_evaluator
        FOREIGN KEY (evaluator_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- INDEXES
-- =========================================================
CREATE INDEX idx_registrations_user_id ON registrations(user_id);
CREATE INDEX idx_registrations_event_id ON registrations(event_id);

CREATE INDEX idx_attendance_registration_id ON attendance(registration_id);

CREATE INDEX idx_digital_ids_user_id ON digital_ids(user_id);
CREATE INDEX idx_digital_ids_event_id ON digital_ids(event_id);

CREATE INDEX idx_papers_user_id ON papers(user_id);
CREATE INDEX idx_papers_event_id ON papers(event_id);

CREATE INDEX idx_evaluations_paper_id ON evaluations(paper_id);
CREATE INDEX idx_evaluations_evaluator_id ON evaluations(evaluator_id);

-- =========================================================
-- OPTIONAL IMPROVEMENTS
-- Uncomment these if they match your intended rules:
-- 1) Prevent duplicate event registrations per user
-- ALTER TABLE registrations
-- ADD CONSTRAINT uq_registrations_user_event UNIQUE (user_id, event_id);

-- 2) Prevent duplicate digital IDs per user per event
-- ALTER TABLE digital_ids
-- ADD CONSTRAINT uq_digital_ids_user_event UNIQUE (user_id, event_id);
