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
