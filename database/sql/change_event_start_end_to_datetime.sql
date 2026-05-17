-- Run in phpMyAdmin if you manage the DB manually (event_ms database).
-- Converts start_date and end_date from DATE to DATETIME so event times can be stored.

USE event_ms;

ALTER TABLE events
  MODIFY start_date DATETIME NULL,
  MODIFY end_date DATETIME NULL;
