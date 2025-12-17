-- script for creating the database schema

-----------------------------------------
-- Config
-----------------------------------------

DROP SCHEMA IF EXISTS lbaw2585 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2585;
SET search_path TO lbaw2585;

-----------------------------------------
-- Cleanup
-----------------------------------------
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS reactions CASCADE;
DROP TABLE IF EXISTS contents CASCADE;
DROP TABLE IF EXISTS follow_requests CASCADE;
DROP TABLE IF EXISTS join_requests CASCADE;
DROP TABLE IF EXISTS group_members CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS album_reviews CASCADE;
DROP TABLE IF EXISTS favourite_albums CASCADE;
DROP TABLE IF EXISTS album_genres CASCADE;
DROP TABLE IF EXISTS favourite_genres CASCADE;
DROP TABLE IF EXISTS albums CASCADE;
DROP TABLE IF EXISTS genres CASCADE;
DROP TABLE IF EXISTS followings CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS RequestStatus CASCADE;
DROP TYPE IF EXISTS ContentTypes CASCADE;
DROP TYPE IF EXISTS ReactionTypes CASCADE;
DROP TYPE IF EXISTS NotificationTypes CASCADE;

DROP INDEX IF EXISTS content_owner_idx CASCADE;
DROP INDEX IF EXISTS reaction_content_idx CASCADE;
DROP INDEX IF EXISTS album_review_album_idx CASCADE;

DROP FUNCTION IF EXISTS content_search_update() CASCADE;

DROP FUNCTION IF EXISTS spam_control() CASCADE;
DROP FUNCTION IF EXISTS check_minimum_age() CASCADE;
DROP FUNCTION IF EXISTS notify_follow_request() CASCADE;
DROP FUNCTION IF EXISTS notify_join_request() CASCADE;
DROP FUNCTION IF EXISTS notify_reaction() CASCADE;
DROP FUNCTION IF EXISTS notify_comment() CASCADE;
DROP FUNCTION IF EXISTS update_like_count() CASCADE;
DROP FUNCTION IF EXISTS update_comment_count() CASCADE;

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

CREATE TABLE followings (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_following INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_following)
);

CREATE TABLE genres (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT genre_name_uk UNIQUE
);

CREATE TABLE albums (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title TEXT NOT NULL,
    artist TEXT NOT NULL,
    release_date DATE NOT NULL,
    songlist TEXT NOT NULL,
    id_music_brainz INTEGER NOT NULL CONSTRAINT album_idBrainz_uk UNIQUE,
    CONSTRAINT album_release_date_ck CHECK (release_date < CURRENT_DATE)
);

CREATE TABLE favourite_genres (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_genre INTEGER NOT NULL REFERENCES genres (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_genre)
);

CREATE TABLE album_genres (
    id_album INTEGER NOT NULL REFERENCES albums (id) ON UPDATE CASCADE,
    id_genre INTEGER NOT NULL REFERENCES genres (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_album, id_genre)
);

CREATE TABLE favourite_albums (
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_album INTEGER NOT NULL REFERENCES albums (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_album)
);

CREATE TABLE album_reviews (
    rating INTEGER NOT NULL CHECK (rating >= 0 AND rating <= 5),
    review_date DATE NOT NULL DEFAULT CURRENT_DATE,
    id_album INTEGER NOT NULL REFERENCES albums (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_user, id_album)	
);

CREATE TABLE groups (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL,
    owner INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    description TEXT,
    is_public BOOLEAN NOT NULL,
    member_count INTEGER NOT NULL
);

CREATE TABLE group_members (
    id_group INTEGER NOT NULL REFERENCES groups (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_group, id_user)
);

CREATE TABLE join_requests (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    status RequestStatus NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    id_group INTEGER NOT NULL REFERENCES groups (id) ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE follow_requests (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    status RequestStatus NOT NULL,
    id_follower INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_followed INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE contents (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    type ContentTypes NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- changed from date to timestamp to have time not just day
    likes INTEGER DEFAULT 0,
    comments INTEGER DEFAULT 0,
    title TEXT,
    description TEXT NOT NULL,
    img TEXT,
    owner INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_group INTEGER REFERENCES groups (id) ON UPDATE CASCADE,
    reply_to INTEGER REFERENCES contents (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_type_ck CHECK (
        (type <> 'comment') OR (reply_to IS NOT NULL)
    )
);

CREATE TABLE reactions (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    type ReactionTypes NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_content INTEGER NOT NULL REFERENCES contents (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    is_read BOOLEAN DEFAULT FALSE,
    type NotificationTypes NOT NULL,
    receiver INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    actor INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_follow_request INTEGER REFERENCES follow_requests (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_group_join_request INTEGER REFERENCES join_requests (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_comment INTEGER REFERENCES contents (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_reaction INTEGER REFERENCES reactions (id) ON UPDATE CASCADE ON DELETE CASCADE,
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

CREATE INDEX content_owner_idx ON contents USING btree (owner);
CLUSTER contents USING content_owner_idx;

CREATE INDEX reaction_content_idx ON reactions USING hash (id_content);

CREATE INDEX album_review_album_idx ON album_reviews USING btree (id_album);

ALTER TABLE contents
ADD COLUMN tsvectors TSVECTOR;

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

CREATE TRIGGER content_search_update
BEFORE INSERT OR UPDATE ON contents
FOR EACH ROW
EXECUTE PROCEDURE content_search_update();

CREATE INDEX search_idx ON contents USING GIN (tsvectors);

-----------------------------------------
-- Triggers
-----------------------------------------

CREATE FUNCTION spam_control() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM contents
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
BEFORE INSERT ON contents
FOR EACH ROW
WHEN (NEW.type = 'post')
EXECUTE FUNCTION spam_control();

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
BEFORE INSERT ON users
FOR EACH ROW
EXECUTE FUNCTION check_minimum_age();

CREATE FUNCTION notify_follow_request() RETURNS TRIGGER AS
$BODY$
BEGIN
   INSERT INTO notifications (type, receiver, actor, id_follow_request)
   VALUES ('followRequest', NEW.id_followed, NEW.id_follower, NEW.id);
   RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER notify_follow_request
AFTER INSERT ON follow_requests
FOR EACH ROW
EXECUTE FUNCTION notify_follow_request();

CREATE FUNCTION notify_join_request() RETURNS TRIGGER AS
$BODY$
BEGIN
   INSERT INTO notifications (type, receiver, actor, id_group_join_request)
   SELECT 'joinGroupRequest', g.owner, NEW.id_user, NEW.id
   FROM groups g
   WHERE g.id = NEW.id_group;
   RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER notify_join_request
AFTER INSERT ON join_requests
FOR EACH ROW
EXECUTE FUNCTION notify_join_request();

CREATE FUNCTION notify_reaction() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF NEW.type IN ('like', 'confetti') THEN
       INSERT INTO notifications (type, receiver, actor, id_reaction)
       SELECT 'reaction', c.owner, NEW.id_user, NEW.id
       FROM contents c
       WHERE c.id = NEW.id_content
       AND c.owner != NEW.id_user;
   END IF;
   RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER notify_reaction
AFTER INSERT ON reactions
FOR EACH ROW
EXECUTE FUNCTION notify_reaction();

CREATE FUNCTION notify_comment() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF NEW.type = 'comment' THEN
       INSERT INTO notifications (type, receiver, actor, id_comment)
       SELECT 'comment', c.owner, NEW.owner, NEW.id
       FROM contents c
       WHERE c.id = NEW.reply_to
       AND c.owner != NEW.owner;
       END IF;
   RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER notify_comment
AFTER INSERT ON contents
FOR EACH ROW
EXECUTE FUNCTION notify_comment();

CREATE FUNCTION update_like_count() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF TG_OP = 'INSERT' AND NEW.type = 'like' THEN
       UPDATE contents
       SET likes = likes + 1
       WHERE id = NEW.id_content;
   ELSIF TG_OP = 'DELETE' AND OLD.type = 'like' THEN
       UPDATE contents
       SET likes = likes - 1
       WHERE id = OLD.id_content;
   END IF;
   RETURN COALESCE(NEW, OLD);
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_like_count
AFTER INSERT OR DELETE ON reactions
FOR EACH ROW
EXECUTE FUNCTION update_like_count();

CREATE FUNCTION update_comment_count() RETURNS TRIGGER AS
$BODY$
BEGIN
   IF TG_OP = 'INSERT' AND NEW.type = 'comment' THEN
       UPDATE contents
       SET comments = comments + 1
       WHERE id = NEW.reply_to;
   ELSIF TG_OP = 'DELETE' AND OLD.type = 'comment' THEN
       UPDATE contents
       SET comments = comments - 1
       WHERE id = OLD.reply_to;
   END IF;
   RETURN COALESCE(NEW, OLD);
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_comment_count
AFTER INSERT OR DELETE ON contents
FOR EACH ROW
EXECUTE FUNCTION update_comment_count();
