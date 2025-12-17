--
-- Schema selection
--
-- This script can be executed directly in psql/pgAdmin, or from PHP/Laravel.
-- It reads the session setting `app.schema` to decide which schema to target.
--  * If `app.schema` is set (e.g. in Laravel with DB::statement('SET app.schema TO ?')),
--    that schema will be used.
--  * If not set, it falls back to the default schema name "thingy".
--

--
-- Schema (re)creation
-- The DO block is needed because identifiers (schema names) cannot be parameterized.
--
DO $do$
DECLARE
  s text := COALESCE(current_setting('app.schema', true), 'mufetti');
BEGIN
  -- identifiers require dynamic SQL
  EXECUTE format('DROP SCHEMA IF EXISTS %I CASCADE', s);
  EXECUTE format('CREATE SCHEMA IF NOT EXISTS %I', s);

  -- set search_path for the rest of the script
  PERFORM set_config('search_path', format('%I, public', s), false);
END
$do$ LANGUAGE plpgsql;

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
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
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
    release_date DATE,
    musicbrainz_id UUID NOT NULL UNIQUE,
    avg_rating NUMERIC(2,1) DEFAULT 0,
    reviews_total INTEGER DEFAULT 0,
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
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    rating INTEGER NOT NULL CHECK (rating >= 0 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_album INTEGER NOT NULL REFERENCES albums(id) ON DELETE CASCADE,
    id_user INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT unique_album_review_per_user UNIQUE (id_album, id_user)
);


CREATE TABLE artists (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name TEXT NOT NULL,
    musicbrainz_id UUID NOT NULL UNIQUE,
    country TEXT,
    description TEXT
);

CREATE TABLE album_artists (
    id_album INTEGER NOT NULL REFERENCES albums(id) ON DELETE CASCADE,
    id_artist INTEGER NOT NULL REFERENCES artists(id) ON DELETE CASCADE,
    PRIMARY KEY (id_album, id_artist)
);

CREATE TABLE songs (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title TEXT NOT NULL,
    track_number INTEGER NOT NULL CHECK (track_number >= 0),
    duration INTEGER CHECK (duration >= 0), -- segundos
    musicbrainz_id UUID UNIQUE,
    id_album INTEGER NOT NULL REFERENCES albums(id) ON DELETE CASCADE
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
    id_user INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_content INTEGER NOT NULL REFERENCES contents (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    is_read BOOLEAN DEFAULT FALSE,
    type NotificationTypes NOT NULL,
    receiver INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    actor INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    id_follow_request INTEGER REFERENCES follow_requests (id) ON UPDATE CASCADE,
    id_group_join_request INTEGER REFERENCES join_requests (id) ON UPDATE CASCADE,
    id_comment INTEGER REFERENCES contents (id) ON UPDATE CASCADE,
    id_reaction INTEGER REFERENCES reactions (id) ON UPDATE CASCADE,
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

CREATE INDEX album_title_idx ON albums USING btree (title);
CREATE INDEX artist_name_idx ON artists USING btree (name);
CREATE INDEX song_album_idx ON songs USING btree (id_album);
CREATE INDEX review_album_idx ON album_reviews USING btree (id_album);

CREATE INDEX content_owner_idx ON contents USING btree (owner);
CLUSTER contents USING content_owner_idx;

CREATE INDEX reaction_content_idx ON reactions USING hash (id_content);


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

ALTER TABLE albums ADD COLUMN search_vector tsvector;

-----------------------------------------
-- Triggers
-----------------------------------------

CREATE FUNCTION album_search_update() RETURNS trigger AS $$
BEGIN
  NEW.search_vector :=
    setweight(to_tsvector('english', NEW.title), 'A');
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER album_search_trigger
BEFORE INSERT OR UPDATE ON albums
FOR EACH ROW EXECUTE FUNCTION album_search_update();


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





