-- script for creating the database schema

-----------------------------------------
-- Config
-----------------------------------------

CREATE SCHEMA IF NOT EXISTS lbaw2585;
SET search_path TO lbaw2585;

-----------------------------------------
-- Cleanup
-----------------------------------------
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS reaction CASCADE;
DROP TABLE IF EXISTS content CASCADE;
DROP TABLE IF EXISTS follow_request CASCADE;
DROP TABLE IF EXISTS join_request CASCADE;
DROP TABLE IF EXISTS group_member CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS album_review CASCADE;
DROP TABLE IF EXISTS favourite_album CASCADE;
DROP TABLE IF EXISTS album_genre CASCADE;
DROP TABLE IF EXISTS favourite_genre CASCADE;
DROP TABLE IF EXISTS album CASCADE;
DROP TABLE IF EXISTS genre CASCADE;
DROP TABLE IF EXISTS following CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS RequestStatus CASCADE;
DROP TYPE IF EXISTS ContentTypes CASCADE;
DROP TYPE IF EXISTS ReactionTypes CASCADE;
DROP TYPE IF EXISTS NotificationTypes CASCADE;

-----------------------------------------
-- Types
-----------------------------------------
CREATE TYPE RequestStatus AS ENUM ('accepted', 'declined', 'pending');
CREATE TYPE ContentTypes AS ENUM ('post', 'comment');
CREATE TYPE ReactionTypes AS ENUM ('like','confetti','report');
CREATE TYPE NotificationTypes AS ENUM (
  'followRequest',
  'startFollowing',
  'acceptedFollowRequest',
  'joinGroupRequest',
  'acceptedJoinGroupRequest',
  'comment',
  'reaction'
);

-----------------------------------------
-- Tables
-----------------------------------------


-- Note that a plural 'users' name was adopted because user is a reserved word in PostgreSQL.

CREATE TABLE users (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT NOT NULL CONSTRAINT user_username_uk UNIQUE,
    email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
    password TEXT NOT NULL,
    birth_date DATE NOT NULL,
    profile_picture TEXT,
    description TEXT,
    is_public BOOLEAN NOT NULL,
    is_admin BOOLEAN NOT NULL,
    CONSTRAINT birth_date CHECK (birth_date < CURRENT_DATE)
);

CREATE TABLE following (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_following INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_following)
);

CREATE TABLE genre (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT genre_name_uk UNIQUE
);

CREATE TABLE album (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title TEXT NOT NULL,
    artist TEXT NOT NULL,
    release_date DATE NOT NULL,
    songlist TEXT NOT NULL,
    id_music_brainz INTEGER NOT NULL CONSTRAINT album_idBrainz_uk UNIQUE,
    CONSTRAINT album_release_date_ck CHECK (release_date < CURRENT_DATE)
);

CREATE TABLE favourite_genre (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_genre INTEGER NOT NULL REFERENCES genre (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_genre)
);

CREATE TABLE album_genre (
    id_album INTEGER NOT NULL REFERENCES album (id) ON UPDATE CASCADE,
    id_genre INTEGER NOT NULL REFERENCES genre (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_album, id_genre)
);

CREATE TABLE favourite_album (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_album INTEGER NOT NULL REFERENCES album (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_album)
);

CREATE TABLE album_review (
    rating INTEGER NOT NULL CHECK (rating >= 0 AND rating <= 5),
    review_date DATE NOT NULL DEFAULT CURRENT_DATE,
    id_album INTEGER NOT NULL REFERENCES album (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_album)	
);

-- Note that a plural 'groups' name was adopted because group is a reserved word in PostgreSQL.

CREATE TABLE groups (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL,
    owner INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    description TEXT,
    is_public BOOLEAN NOT NULL,
    member_count INTEGER NOT NULL
);

CREATE TABLE group_member (
    id_group INTEGER NOT NULL REFERENCES groups (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_group, id_user)
);

CREATE TABLE join_request (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    status RequestStatus NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    id_group INTEGER NOT NULL REFERENCES groups (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE follow_request (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    status RequestStatus NOT NULL,
    id_follower INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_followed INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE content (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    type ContentTypes NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    likes INTEGER DEFAULT 0,
    comments INTEGER DEFAULT 0,
    title TEXT,
    description TEXT NOT NULL,
    img TEXT,
    owner INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_group INTEGER REFERENCES groups (id) ON UPDATE CASCADE,
    reply_to INTEGER REFERENCES content (id) ON UPDATE CASCADE,
    CONSTRAINT content_type_ck CHECK (
        (type <> 'comment') OR (reply_to IS NOT NULL)
    )
);

CREATE TABLE reaction (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    type ReactionTypes NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_content INTEGER NOT NULL REFERENCES content (id) ON UPDATE CASCADE
);

CREATE TABLE notification (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    is_read BOOLEAN DEFAULT FALSE,
    type NotificationTypes NOT NULL,
    receiver INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    actor INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_follow_request INTEGER REFERENCES follow_request (id) ON UPDATE CASCADE,
    id_group_join_request INTEGER REFERENCES join_request (id) ON UPDATE CASCADE,
    id_comment INTEGER REFERENCES content (id) ON UPDATE CASCADE,
    id_reaction INTEGER REFERENCES reaction (id) ON UPDATE CASCADE,
    CONSTRAINT notification_type_ck CHECK (
        (type <> 'followRequest' OR id_follow_request IS NOT NULL) AND
        (type <> 'acceptedFollowRequest' OR id_follow_request IS NOT NULL) AND
        (type <> 'joinGroupRequest' OR id_group_join_request IS NOT NULL) AND
        (type <> 'acceptedJoinGroupRequest' OR id_group_join_request IS NOT NULL) AND
        (type <> 'comment' OR id_comment IS NOT NULL) AND
        (type <> 'reaction' OR id_reaction IS NOT NULL)
    )
);

-----------------------------------------
-- Indexes
-----------------------------------------

--IDX01
CREATE INDEX content_owner_idx ON content USING btree (owner);
CLUSTER content USING content_owner_idx;

--IDX02
CREATE INDEX reaction_content_idx ON reaction USING hash (id_content);

--IDX03
CREATE INDEX album_review_album_idx ON album_review USING btree (id_album);

--IDX11
-- Add column to store precomputed text search vectors
ALTER TABLE content
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update the tsvector field
CREATE FUNCTION content_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.title), 'A') ||
      setweight(to_tsvector('english', NEW.description), 'B')
    );
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create trigger to keep tsvector updated
CREATE TRIGGER content_search_update
BEFORE INSERT OR UPDATE ON content
FOR EACH ROW
EXECUTE PROCEDURE content_search_update();

-- Create GIN index on tsvectors for fast text search
CREATE INDEX search_idx ON content USING GIN (tsvectors);


-----------------------------------------
-- Triggers
-----------------------------------------

-- TRIGGER 01
CREATE FUNCTION spam_control() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM content
        WHERE owner = NEW.owner
          AND type = 'post'
          AND created_at > (CURRENT_TIMESTAMP - INTERVAL '20 seconds')
    ) THEN
        RAISE EXCEPTION 'Spam control: Users must wait 20 seconds between posts.';
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER spam_control
BEFORE INSERT ON content
FOR EACH ROW
WHEN (NEW.type = 'post')
EXECUTE FUNCTION spam_control();

-- TRIGGER 02
CREATE FUNCTION check_minimum_age() RETURNS TRIGGER AS
$BODY$
DECLARE
    user_age INTEGER;
BEGIN
    user_age := DATE_PART('year', AGE(CURRENT_DATE, NEW.birth_date));
    IF user_age < 13 THEN
        RAISE EXCEPTION 'User must be at least 13 years old to register.';
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_minimum_age
BEFORE INSERT ON user
FOR EACH ROW
EXECUTE FUNCTION check_minimum_age();

-- TRIGGER 03
CREATE FUNCTION anonymize_user() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE user
    SET name = 'Deleted User',
        username = CONCAT('deleted_', id),
        email = NULL,
        profile_picture = NULL,
        description = NULL,
        is_public = FALSE
    WHERE id = OLD.id;

    RETURN NULL; -- Prevent actual deletion
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER anonymize_user
BEFORE DELETE ON user
FOR EACH ROW
EXECUTE FUNCTION anonymize_user();

-- TRIGGER 04
CREATE FUNCTION delete_group_content() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM content WHERE id_group = OLD.id;
    DELETE FROM group_member WHERE id_group = OLD.id;
    DELETE FROM join_request WHERE id_group = OLD.id;
    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_group_content
AFTER DELETE ON group
FOR EACH ROW
EXECUTE FUNCTION delete_group_content();

-----------------------------------------
-- Triggers
-----------------------------------------

-- TRAN01
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

-- 1. Insert the reaction record
INSERT INTO reaction (type, created_at, id_user, id_content)
VALUES ($reaction_type, CURRENT_DATE, $user_id, $content_id);

-- 2. Increment the likes counter on the content post (only if it's a 'like')
IF $reaction_type = 'like' THEN
    UPDATE content
    SET likes = likes + 1
    WHERE id = $content_id;
END IF;

COMMIT;

-- TRAN02
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

-- 1. Anonymize the user
UPDATE users
SET name = 'Deleted User',
    username = CONCAT('deleted_', id),
    email = NULL,
    profile_picture = NULL,
    description = NULL,
    is_public = FALSE
WHERE id = $user_id;

-- 2. Remove user from groups
DELETE FROM group_member
WHERE id_user = $user_id;

-- 3. Delete follow relationships
DELETE FROM following
WHERE id_user = $user_id OR id_following = $user_id;

-- 4. Delete join requests
DELETE FROM join_request
WHERE id_user = $user_id;

-- 5. Delete follow requests
DELETE FROM follow_request
WHERE id_follower = $user_id OR id_followed = $user_id;

COMMIT;


-- TRAN03
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

-- 1. Delete notifications related to group join requests
DELETE FROM notification
WHERE id_group_join_request IN (
    SELECT id FROM join_request WHERE id_group = $group_id
);

-- 2. Delete join requests for this group
DELETE FROM join_request
WHERE id_group = $group_id;

-- 3. Delete group members
DELETE FROM group_member
WHERE id_group = $group_id;

-- 4. Optional: delete or anonymize content posted in the group
DELETE FROM content
WHERE id_group = $group_id;

-- 5. Finally, delete the group itself
DELETE FROM groups
WHERE id = $group_id;

COMMIT;
