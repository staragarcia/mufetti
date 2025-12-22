-- script for populating database with sample data

SET search_path TO lbaw2585;

-----------------------------------------
-- Initial system accounts (Corrigido)
-----------------------------------------

-- ID 1 é o utilizador de sistema
INSERT INTO users (id, name, username, email, password, birth_date, is_public, is_admin, is_blocked)
OVERRIDING SYSTEM VALUE 
VALUES (1, 'Anonymous User', 'deleted_user', 'deleted@mufetti.com', 'LOCKED', '1900-01-01', false, false, false);

-- Admin genérico é ID 2 (Password: admin)
INSERT INTO users (id, name, username, email, password, birth_date, is_public, is_admin, is_blocked) 
OVERRIDING SYSTEM VALUE 
VALUES (2, 'Admin', 'admin', 'admin@email.com', '$2y$10$iMTuj9m/31PKIshvWrWYIufsw6JOEKsy.wIiWGhjH9eCTuum2AOc2', '1900-01-01', false, true, false);

-- Sincronizar o contador de IDs
SELECT setval(pg_get_serial_sequence('users', 'id'), coalesce(max(id), 1)) FROM users;

-----------------------------------------
-- USERS (25)
-----------------------------------------


INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Alice Martins', 'alice_m', 'alice@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1998-04-21', '/images/alice.jpg', 'Music lover and vinyl collector.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Bruno Costa', 'brunoc', 'bruno@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1995-08-10', '/images/bruno.jpg', 'Into indie and rock.', TRUE, TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Carla Silva', 'carla_s', 'carla@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '2000-02-15', NULL, 'Pop and dance enthusiast.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Daniel Ferreira', 'danielf', 'daniel@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1992-11-05', '/images/daniel.jpg', 'Metal head', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Eva Ramos', 'evar', 'eva@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1999-06-12', '/images/eva.jpg', 'Dream pop and shoegaze.', FALSE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Filipe Sousa', 'filipe_s', 'filipe@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '2001-09-23', NULL, 'Punk is not dead.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Goncalo Lima', 'goncalo', 'goncalo@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1997-01-02', '/images/goncalo.jpg', 'Collector of obscure records.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Helena Duarte', 'helenad', 'helena@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '2002-03-30', NULL, 'Just vibes', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Ines Oliveira', 'ines_o', 'ines@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1996-12-19', '/images/ines.jpg', 'New wave forever.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Joao Pinto', 'joaop', 'joao@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1994-05-08', '/images/joao.jpg', 'Admin account', TRUE, TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Maya Johnson', 'maya_j', 'maya@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1990-11-11', '/images/maya.jpg', 'Indie singer-songwriter.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Liam OConnor', 'liam_oc', 'liam@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1988-07-07', NULL, 'Guitar nerd from Dublin.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Sakura Tanaka', 'sakura_t', 'sakura@example.jp', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1993-05-03', '/images/sakura.jpg', 'Electronic producer from Tokyo.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Diego Morales', 'diego_m', 'diego@example.es', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1991-09-09', NULL, 'Rap and beats curator.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Amelia Brown', 'amelia_b', 'amelia@example.co.uk', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1994-12-02', '/images/amelia.jpg', 'Classical pianist and teacher.', FALSE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Noah Smith', 'noah_s', 'noah@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '2000-06-18', NULL, 'Hip-hop playlists curator.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Yara Costa', 'yara_c', 'yara@example.pt', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1995-10-20', NULL, 'Portuguese singer.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Oliver James', 'oliver_j', 'oliver@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1987-03-03', '/images/oliver.jpg', 'Bass player.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Priya Kumar', 'priya_k', 'priya@example.in', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1996-08-08', NULL, 'Bollywood & fusion fan.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Luca Romano', 'luca_r', 'luca@example.it', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1992-02-22', NULL, 'Italian opera enthusiast.', FALSE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Chloe Martin', 'chloe_m', 'chloe@example.fr', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1998-01-01', '/images/chloe.jpg', 'Indie playlists and reviews.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Hiroshi Sato', 'hiro_s', 'hiro@example.jp', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1989-10-10', NULL, 'Vinyl collector, Tokyo.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Fatima Al-Masri', 'fatima_m', 'fatima@example.sa', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1993-04-29', NULL, 'World music curator.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Mateo Alvarez', 'mateo_a', 'mateo@example.mx', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1991-11-30', NULL, 'Latin beats lover.', TRUE, FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin, is_blocked)
VALUES ('Zoe Green', 'zoe_g', 'zoe@example.com', '$2y$12$ZTWeADJlJJ6Yw.TLWU30SOdSlSoTZvZl2RkN1NAVg6TBW0ZOH23/2', '1999-09-09', '/images/zoe.jpg', 'Festival photographer & music lover.', TRUE, FALSE, FALSE);
-----------------------------------------
-- FOLLOWING
-----------------------------------------
INSERT INTO followings (id_user, id_following) VALUES (2,3);
INSERT INTO followings (id_user, id_following) VALUES (2,4);
INSERT INTO followings (id_user, id_following) VALUES (3,2);
INSERT INTO followings (id_user, id_following) VALUES (3,5);
INSERT INTO followings (id_user, id_following) VALUES (4,6);
INSERT INTO followings (id_user, id_following) VALUES (5,3);
INSERT INTO followings (id_user, id_following) VALUES (6,2);
INSERT INTO followings (id_user, id_following) VALUES (7,3);
INSERT INTO followings (id_user, id_following) VALUES (8,2);
INSERT INTO followings (id_user, id_following) VALUES (9,4);
INSERT INTO followings (id_user, id_following) VALUES (10,3);
INSERT INTO followings (id_user, id_following) VALUES (11,2);

INSERT INTO followings (id_user, id_following) VALUES (12,2);
INSERT INTO followings (id_user, id_following) VALUES (13,12);
INSERT INTO followings (id_user, id_following) VALUES (14,4);
INSERT INTO followings (id_user, id_following) VALUES (15,3);
INSERT INTO followings (id_user, id_following) VALUES (16,8);
INSERT INTO followings (id_user, id_following) VALUES (17,9);
INSERT INTO followings (id_user, id_following) VALUES (18,6);
INSERT INTO followings (id_user, id_following) VALUES (19,10);
INSERT INTO followings (id_user, id_following) VALUES (20,11);
INSERT INTO followings (id_user, id_following) VALUES (21,3);
INSERT INTO followings (id_user, id_following) VALUES (22,5);
INSERT INTO followings (id_user, id_following) VALUES (23,7);
INSERT INTO followings (id_user, id_following) VALUES (24,8);
INSERT INTO followings (id_user, id_following) VALUES (25,9);

-----------------------------------------
-- GENRE (10)
-----------------------------------------
INSERT INTO genres (name) VALUES ('Rock');
INSERT INTO genres (name) VALUES ('Pop');
INSERT INTO genres (name) VALUES ('Metal');
INSERT INTO genres (name) VALUES ('Indie');
INSERT INTO genres (name) VALUES ('Punk');
INSERT INTO genres (name) VALUES ('Jazz');
INSERT INTO genres (name) VALUES ('Electronic');
INSERT INTO genres (name) VALUES ('Hip-Hop');
INSERT INTO genres (name) VALUES ('Classical');
INSERT INTO genres (name) VALUES ('Folk');

-----------------------------------------
-- ARTISTS
-----------------------------------------
INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Radiohead', 'a74b1b7f-71a5-4011-9441-d0b5e4122711', 'United Kingdom', 'English rock band formed in 1985');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Nirvana', '5b11f4ce-a62d-471e-81fc-a69a8278c7da', 'United States', 'American grunge band');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Dua Lipa', 'db92a151-1ac2-438b-bc43-b82e149ddd50', 'United Kingdom', 'English pop singer');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Miles Davis', '561d854a-6a28-4aa7-8c99-323e6ce46c2a', 'United States', 'American jazz trumpeter');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Daft Punk', '056e4f3e-d505-4dad-8ec1-d04f521cbb56', 'France', 'French electronic duo');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Linkin Park', 'f59c5520-5f46-4d2c-b2c4-822eabf53419', 'United States', 'American rock band');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Pink Floyd', '83d91898-7763-47d7-b03b-b92132375c47', 'United Kingdom', 'English progressive rock band');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Taylor Swift', '20244d07-534f-4eff-b4d4-930878889970', 'United States', 'American singer-songwriter');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Green Day', '084308bd-1654-436f-ba03-df6697104e19', 'United States', 'American punk rock band');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Kendrick Lamar', '381086ea-f511-4aba-bdf9-71c753dc5077', 'United States', 'American rapper');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Frank Ocean', 'e520459c-dff4-491d-a6e4-c97be35e0044', 'United States', 'American R&B singer');

INSERT INTO artists (name, musicbrainz_id, country, description)
VALUES ('Tame Impala', '63aa26c3-d59b-4da4-84ac-716b54f1ef4d', 'Australia', 'Australian psychedelic rock project');

-----------------------------------------
-- ALBUMS
-----------------------------------------
INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('OK Computer', '1997-05-21', '6a09041b-0f79-3278-88d9-7503bc56af10');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Nevermind', '1991-09-24', '1b022e01-4da6-387b-8658-8678046e4cac');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Future Nostalgia', '2020-03-27', 'b8b7ae80-e0ab-46ad-b11d-5c6c93c9e545');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Kind of Blue', '1959-08-17', '2957b7b2-1f2b-3cb8-8d21-74c7ef81e8ca');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Discovery', '2001-03-12', '5d896853-f07b-350f-9e50-b3e9e9e8c90b');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Hybrid Theory', '2000-10-24', '0d94ba69-5ab0-3f4a-b5c6-b82f9c8f6a5f');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('The Dark Side of the Moon', '1973-03-01', 'f6cf124d-0e89-3546-9240-2d9d2c9e8765');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Folklore', '2020-07-24', '254e7dcb-9c1e-4aef-b0f7-2d5a1f4c6b8d');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('American Idiot', '2004-09-21', '19be6e4f-9165-37aa-bb88-102de0eddcd7');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('To Pimp a Butterfly', '2015-03-15', 'ad862a15-2f96-44d7-ba01-75e46045aef0');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('A Moon Shaped Pool', '2016-05-08', '0c1e6c1e-9f5e-4d7b-9c8d-2e3f4a5b6c7d');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Blonde', '2016-08-20', '1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Random Access Memories', '2013-05-17', '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Channel Orange', '2012-07-10', '3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e');

INSERT INTO albums (title, release_date, musicbrainz_id)
VALUES ('Currents', '2015-07-17', '4c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f');

-----------------------------------------
-- ALBUM_ARTISTS
-----------------------------------------
INSERT INTO album_artists (id_album, id_artist) VALUES (1, 1);
INSERT INTO album_artists (id_album, id_artist) VALUES (2, 2);
INSERT INTO album_artists (id_album, id_artist) VALUES (3, 3);
INSERT INTO album_artists (id_album, id_artist) VALUES (4, 4);
INSERT INTO album_artists (id_album, id_artist) VALUES (5, 5);
INSERT INTO album_artists (id_album, id_artist) VALUES (6, 6);
INSERT INTO album_artists (id_album, id_artist) VALUES (7, 7);
INSERT INTO album_artists (id_album, id_artist) VALUES (8, 8);
INSERT INTO album_artists (id_album, id_artist) VALUES (9, 9);
INSERT INTO album_artists (id_album, id_artist) VALUES (10, 10);
INSERT INTO album_artists (id_album, id_artist) VALUES (11, 1);
INSERT INTO album_artists (id_album, id_artist) VALUES (12, 11);
INSERT INTO album_artists (id_album, id_artist) VALUES (13, 5);
INSERT INTO album_artists (id_album, id_artist) VALUES (14, 11);
INSERT INTO album_artists (id_album, id_artist) VALUES (15, 12);

-----------------------------------------
-- SONGS
-----------------------------------------
INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Airbag', 1, 284, '5b8b8e8e-8c7d-4f6e-9d5c-4a3b2c1d0e9f', 1);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Paranoid Android', 2, 383, '6c9c9f9f-9d8e-5f7f-0e6d-5b4c3d2e1f0a', 1);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('No Surprises', 11, 229, '7d0d0a0a-0e9f-6f8a-1f7e-6c5d4e3f2a1b', 1);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Smells Like Teen Spirit', 1, 301, '8e1e1b1b-1f0a-7f9b-2f8f-7d6e5f4a3b2c', 2);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Come As You Are', 3, 219, '9f2f2c2c-2a1b-8a0c-3f9a-8e7f6a5b4c3d', 2);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Levitating', 2, 203, '0a3a3d3d-3b2c-9b1d-4a0b-9f8a7b6c5d4e', 3);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('Don''t Start Now', 1, 183, '1b4b4e4e-4c3d-0c2e-5b1c-0a9b8c7d6e5f', 3);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('So What', 1, 563, '2c5c5f5f-5d4e-1d3f-6c2d-1b0c9d8e7f6a', 4);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('One More Time', 1, 320, '3d6d6a6a-6e5f-2e4a-7d3e-2c1d0e9f8a7b', 5);

INSERT INTO songs (title, track_number, duration, musicbrainz_id, id_album)
VALUES ('In the End', 8, 216, '4e7e7b7b-7f6a-3f5b-8e4f-3d2e1f0a9b8c', 6);

-----------------------------------------
-- FAVOURITE_GENRE
-----------------------------------------
INSERT INTO favourite_genres (id_user, id_genre) VALUES (2,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (2,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (3,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (4,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (5,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (6,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (7,6);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (8,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (9,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (10,5);

INSERT INTO favourite_genres (id_user, id_genre) VALUES (11,7);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (12,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (13,8);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (14,9);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (15,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (16,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (17,8);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (18,9);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (19,10);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (20,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (21,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (22,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (23,6);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (24,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (25,8);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (26,3);

-----------------------------------------
-- ALBUM_GENRE
-----------------------------------------
INSERT INTO album_genres (id_album, id_genre) VALUES (1,4);
INSERT INTO album_genres (id_album, id_genre) VALUES (2,1);
INSERT INTO album_genres (id_album, id_genre) VALUES (3,2);
INSERT INTO album_genres (id_album, id_genre) VALUES (4,6);
INSERT INTO album_genres (id_album, id_genre) VALUES (5,7);
INSERT INTO album_genres (id_album, id_genre) VALUES (6,3);
INSERT INTO album_genres (id_album, id_genre) VALUES (7,1);
INSERT INTO album_genres (id_album, id_genre) VALUES (8,2);
INSERT INTO album_genres (id_album, id_genre) VALUES (9,5);
INSERT INTO album_genres (id_album, id_genre) VALUES (10,8);
INSERT INTO album_genres (id_album, id_genre) VALUES (11,4);
INSERT INTO album_genres (id_album, id_genre) VALUES (12,8);
INSERT INTO album_genres (id_album, id_genre) VALUES (13,7);
INSERT INTO album_genres (id_album, id_genre) VALUES (14,8);
INSERT INTO album_genres (id_album, id_genre) VALUES (15,4);

-----------------------------------------
-- FAVOURITE_ALBUM
-----------------------------------------
INSERT INTO favourite_albums (id_user, id_album) VALUES (2,1);
INSERT INTO favourite_albums (id_user, id_album) VALUES (3,2);
INSERT INTO favourite_albums (id_user, id_album) VALUES (4,3);
INSERT INTO favourite_albums (id_user, id_album) VALUES (5,6);
INSERT INTO favourite_albums (id_user, id_album) VALUES (6,8);
INSERT INTO favourite_albums (id_user, id_album) VALUES (7,9);
INSERT INTO favourite_albums (id_user, id_album) VALUES (8,5);
INSERT INTO favourite_albums (id_user, id_album) VALUES (9,4);
INSERT INTO favourite_albums (id_user, id_album) VALUES (10,10);
INSERT INTO favourite_albums (id_user, id_album) VALUES (11,7);

INSERT INTO favourite_albums (id_user, id_album) VALUES (12,11);
INSERT INTO favourite_albums (id_user, id_album) VALUES (13,12);
INSERT INTO favourite_albums (id_user, id_album) VALUES (14,13);
INSERT INTO favourite_albums (id_user, id_album) VALUES (15,14);
INSERT INTO favourite_albums (id_user, id_album) VALUES (16,15);

-----------------------------------------
-- ALBUM_REVIEW
-----------------------------------------
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Masterpiece of alternative rock', '2024-02-02 10:30:00', 1, 2);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (4, 'Defined a generation', '2024-02-03 14:20:00', 2, 3);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Perfect pop album', '2024-03-01 16:45:00', 3, 4);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (3, 'Classic jazz, essential listening', '2024-03-05 09:15:00', 4, 5);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Electronic perfection', '2024-04-10 18:30:00', 5, 6);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (4, 'Nu-metal at its best', '2024-04-15 11:00:00', 6, 7);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Timeless progressive rock', '2024-05-01 20:00:00', 7, 8);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (3, 'Great storytelling', '2024-05-12 13:30:00', 8, 9);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (4, 'Punk rock opera', '2024-06-08 15:45:00', 9, 10);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Hip-hop masterpiece', '2024-07-01 17:00:00', 10, 11);

INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (4, 'Beautiful and haunting', '2023-10-02 12:00:00', 11, 12);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Emotional and powerful', '2022-08-20 14:30:00', 12, 13);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Funky and fresh', '2018-06-11 16:00:00', 13, 14);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (4, 'R&B excellence', '2019-09-30 19:20:00', 14, 15);
INSERT INTO album_reviews (rating, review_text, created_at, id_album, id_user) VALUES (5, 'Psychedelic journey', '2021-01-15 21:45:00', 15, 16);

-----------------------------------------
-- GROUPS
-----------------------------------------
INSERT INTO groups (name, owner, description, is_active, is_public, member_count)
VALUES 
('Indie Lovers', 2, 'For fans of indie rock and dream pop.', TRUE, TRUE, 5),
('Metalheads United', 5, 'All things metal.', TRUE, TRUE, 4),
('Pop Central', 4, 'Pop fans unite.', TRUE, TRUE, 6),
('Jazz Appreciation', 9, 'Discover and share jazz classics.', TRUE, FALSE, 3),
('Punk Rebellion', 7, 'Loud and proud.', TRUE, TRUE, 4),
('Vinyl Collectors', 8, 'Share your latest vinyl finds.', TRUE, TRUE, 3),
('Classical Harmony', 10, 'Orchestra enthusiasts.', TRUE, FALSE, 2),
('Electronic Nation', 6, 'Beats, bass, and synths.', TRUE, TRUE, 3),
('Rhyme & Flow', 11, 'Hip-hop and rap lovers.', TRUE, TRUE, 5),
('Album Reviewers', 3, 'For thoughtful review discussions.', TRUE, TRUE, 5);
-----------------------------------------
-- GROUP_MEMBER
-----------------------------------------
INSERT INTO group_members (id_group, id_user) VALUES (1,2);
INSERT INTO group_members (id_group, id_user) VALUES (1,3);
INSERT INTO group_members (id_group, id_user) VALUES (1,6);
INSERT INTO group_members (id_group, id_user) VALUES (1,9);
INSERT INTO group_members (id_group, id_user) VALUES (1,12);

INSERT INTO group_members (id_group, id_user) VALUES (2,5);
INSERT INTO group_members (id_group, id_user) VALUES (2,7);
INSERT INTO group_members (id_group, id_user) VALUES (2,8);
INSERT INTO group_members (id_group, id_user) VALUES (2,17);

INSERT INTO group_members (id_group, id_user) VALUES (3,4);
INSERT INTO group_members (id_group, id_user) VALUES (3,2);
INSERT INTO group_members (id_group, id_user) VALUES (3,6);
INSERT INTO group_members (id_group, id_user) VALUES (3,13);
INSERT INTO group_members (id_group, id_user) VALUES (3,14);
INSERT INTO group_members (id_group, id_user) VALUES (3,15);

INSERT INTO group_members (id_group, id_user) VALUES (4,9);
INSERT INTO group_members (id_group, id_user) VALUES (4,10);
INSERT INTO group_members (id_group, id_user) VALUES (4,19);

INSERT INTO group_members (id_group, id_user) VALUES (5,7);
INSERT INTO group_members (id_group, id_user) VALUES (5,3);
INSERT INTO group_members (id_group, id_user) VALUES (5,21);
INSERT INTO group_members (id_group, id_user) VALUES (5,22);

INSERT INTO group_members (id_group, id_user) VALUES (6,8);
INSERT INTO group_members (id_group, id_user) VALUES (6,2);
INSERT INTO group_members (id_group, id_user) VALUES (6,23);

INSERT INTO group_members (id_group, id_user) VALUES (7,10);
INSERT INTO group_members (id_group, id_user) VALUES (7,11);

INSERT INTO group_members (id_group, id_user) VALUES (8,6);
INSERT INTO group_members (id_group, id_user) VALUES (8,7);
INSERT INTO group_members (id_group, id_user) VALUES (8,25);

INSERT INTO group_members (id_group, id_user) VALUES (9,11);
INSERT INTO group_members (id_group, id_user) VALUES (9,20);
INSERT INTO group_members (id_group, id_user) VALUES (9,24);
INSERT INTO group_members (id_group, id_user) VALUES (9,16);
INSERT INTO group_members (id_group, id_user) VALUES (9,18);

INSERT INTO group_members (id_group, id_user) VALUES (10,3);
INSERT INTO group_members (id_group, id_user) VALUES (10,15);
INSERT INTO group_members (id_group, id_user) VALUES (10,26);
INSERT INTO group_members (id_group, id_user) VALUES (10,12);
INSERT INTO group_members (id_group, id_user) VALUES (10,4);

-----------------------------------------
-- JOIN_REQUEST
-----------------------------------------
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-07-10', 1, 4);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-07-12', 1, 6);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2024-07-15', 2, 3);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-08-01', 3, 5);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-08-05', 4, 9);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-08-07', 6, 2);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-08-09', 9, 11);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2024-09-01', 8, 8);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-09-10', 3, 10);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-09-20', 10, 6);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2023-11-05', 2, 16);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2022-06-12', 6, 12);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2021-04-03', 7, 17);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2020-09-10', 4, 13);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-10-05', 5, 23);

-----------------------------------------
-- FOLLOW_REQUEST
-----------------------------------------
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-01', 'pending', 4, 6);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-02', 'accepted', 2, 3);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-03', 'accepted', 3, 2);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-04', 'declined', 5, 6);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-05', 'accepted', 6, 4);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-06', 'accepted', 7, 8);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-07', 'pending', 9, 10);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-08', 'accepted', 10, 11);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-09', 'declined', 11, 5);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-10', 'pending', 3, 4);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2023-02-11', 'accepted', 12, 2);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2021-08-09', 'accepted', 13, 3);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2020-12-24', 'declined', 14, 5);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2019-06-06', 'accepted', 15, 4);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-04-01', 'accepted', 16, 6);

-----------------------------------------
-- CONTENT
-----------------------------------------
INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-01 10:30:00', 10, 2, 'Best album ever?', 'I still think OK Computer is unbeatable.', NULL, 2, 1, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-03 14:20:00', 8, 1, 'New Dua Lipa album', 'Love the production on this one.', NULL, 4, 3, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-05 16:00:00', 5, 0, 'Metal fans?', 'Whats your favorite Linkin Park track?', NULL, 5, 2, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-07 09:15:00', 7, 1, 'Jazz night', 'Currently spinning Kind of Blue.', NULL, 9, 4, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-09 18:30:00', 12, 3, 'New music Friday!', 'Whats everyone listening to?', NULL, 6, 8, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-12 11:00:00', 9, 2, 'Check out my latest playlist', 'A bit of indie + synthwave.', 'img1.jpg', 8, 6, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-01 15:45:00', 4, 0, 'Festival memories', 'Photos from the weekend.', 'fest1.jpg', 26, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-03 16:00:00', 6, 1, 'Bedroom production tips', 'How I EQ my vocals.', NULL, 14, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-10 17:00:00', 15, 5, 'Vinyl for sale', 'Selling a rare pressing of OK Computer.', 'vinyl.jpg', 8, 6, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-15 20:00:00', 3, 0, 'Classical q&a', 'What recordings of the 9th do you recommend?', NULL, 16, 7, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-01', 8, 2, 'New single released', 'Dropped my new single today — feedback welcome!', NULL, 11, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-10', 2, 0, 'Practice session', '30 minute live stream tomorrow.', NULL, 20, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-02', 3, 0, NULL, 'Agree completely!', NULL, 2, 1, 1);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-06 16:30:00', 2, 0, NULL, 'In the End, hands down.', NULL, 7, 2, 3);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-08 10:00:00', 1, 0, NULL, 'A masterpiece.', NULL, 10, 4, 4);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-10 19:00:00', 2, 0, NULL, 'Levitating never gets old!', NULL, 4, 8, 5);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-06-12 18:00:00', 0, 0, NULL, 'Is this still available?', NULL, 12, 6, 9);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-01 17:30:00', 8, 2, 'New single released', 'Dropped my new single today - feedback welcome!', NULL, 12, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-10 12:00:00', 2, 0, 'Practice session', '30 minute live stream tomorrow.', NULL, 21, NULL, NULL);

-----------------------------------------
-- REACTION
-----------------------------------------
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-02', 3, 1);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-02', 4, 1);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-04', 2, 3);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-05', 6, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-05-06', 8, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-07', 9, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-08', 10, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-09', 5, 8);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-05-10', 11, 9);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-11', 6, 10);

INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-02', 12, 11);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-03', 13, 12);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-06-04', 14, 9);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-05', 15, 7);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-06', 16, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-06-07', 17, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-08', 18, 11);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-06-09', 19, 13);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-10', 20, 14);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-07-02', 21, 16);

-----------------------------------------
-- NOTIFICATION
-----------------------------------------
INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-02', FALSE, 'followRequest', 3, 2, 2, NULL, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-03', TRUE, 'acceptedFollowRequest', 2, 3, 2, NULL, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-04', FALSE, 'reaction', 2, 3, NULL, NULL, NULL, 1);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-05', FALSE, 'comment', 2, 3, NULL, NULL, 11, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-06', FALSE, 'joinGroupRequest', 4, 5, NULL, 1, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-07', TRUE, 'acceptedJoinGroupRequest', 6, 2, NULL, 2, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-08', FALSE, 'reaction', 4, 6, NULL, NULL, NULL, 5);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-09', FALSE, 'comment', 9, 4, NULL, NULL, 13, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-10', TRUE, 'reaction', 6, 8, NULL, NULL, NULL, 10);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-11', FALSE, 'startFollowing', 10, 11, NULL, NULL, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-02', FALSE, 'comment', 2, 3, NULL, NULL, 11, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-04', FALSE, 'reaction', 8, 14, NULL, NULL, NULL, 13);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-06', FALSE, 'comment', 4, 4, NULL, NULL, 14, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-07', TRUE, 'acceptedFollowRequest', 2, 12, 11, NULL, NULL, NULL);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-09', FALSE, 'reaction', 4, 14, NULL, NULL, NULL, 15);

INSERT INTO notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-15', FALSE, 'comment', 16, 15, NULL, NULL, 15, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-07-03', FALSE, 'reaction', 11, 20, NULL, NULL, NULL, 20);

-----------------------------------------
-- REPORTS
-----------------------------------------


INSERT INTO reports (id_user, reportable_id, reportable_type, motive, description, status, created_at)
VALUES 
(2, 5, 'post', 'Spam', 'This post is spam.', 'pending', '2025-12-01 10:00:00'),
(3, 12, 'comment', 'Harassment', 'This comment is harassing another user.', 'pending', '2025-12-02 11:00:00'),
(4, 7, 'post', 'Inappropriate Content', 'Contains inappropriate language.', 'reviewed', '2025-12-03 12:00:00');
