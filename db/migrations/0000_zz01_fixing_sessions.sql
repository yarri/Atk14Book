ALTER TABLE sessions ADD session_name VARCHAR(64) DEFAULT 'session' NOT NULL CHECK(LENGTH(session_name)>0); -- !! hodnota session je zavisla aplikace od aplikace (viz konstance SESSION_STORER_COOKIE_NAME_SESSION)
ALTER TABLE session_values RENAME COLUMN session_name TO section;

ALTER TABLE session_values DROP CONSTRAINT unq_sessionvalues;
ALTER TABLE session_values ADD CONSTRAINT unq_sessionvalues UNIQUE(session_id,section,key);
