-- script for populating database with sample data

SET search_path TO lbaw2585;

-----------------------------------------
-- USERS (25)
-----------------------------------------
INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Alice Martins', 'alice_m', 'alice@example.com', 'pass123', '1998-04-21', 'pic1.jpg', 'Music lover and vinyl collector.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Bruno Costa', 'brunoc', 'bruno@example.com', 'pass456', '1995-08-10', 'pic2.jpg', 'Into indie and rock.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Carla Silva', 'carla_s', 'carla@example.com', 'pass789', '2000-02-15', NULL, 'Pop and dance enthusiast.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Daniel Ferreira', 'danielf', 'daniel@example.com', 'pass000', '1992-11-05', 'pic3.jpg', 'Metal head 🤘', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Eva Ramos', 'evar', 'eva@example.com', 'passabc', '1999-06-12', 'pic4.jpg', 'Dream pop and shoegaze.', FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Filipe Sousa', 'filipe_s', 'filipe@example.com', 'passdef', '2001-09-23', NULL, 'Punk is not dead.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Gonçalo Lima', 'goncalo', 'goncalo@example.com', 'passghi', '1997-01-02', 'pic5.jpg', 'Collector of obscure records.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Helena Duarte', 'helenad', 'helena@example.com', 'passjkl', '2002-03-30', NULL, 'Just vibes 🎶', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Inês Oliveira', 'ines_o', 'ines@example.com', 'passmno', '1996-12-19', 'pic6.jpg', 'New wave forever.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('João Pinto', 'joaop', 'joao@example.com', 'passpqr', '1994-05-08', 'pic7.jpg', 'Admin account', TRUE, TRUE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Maya Johnson', 'maya_j', 'maya@example.com', 'mypass1', '1990-11-11', 'maya.jpg', 'Indie singer-songwriter.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Liam OConnor', 'liam_oc', 'liam@example.com', 'liam123', '1988-07-07', NULL, 'Guitar nerd from Dublin.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Sakura Tanaka', 'sakura_t', 'sakura@example.jp', 'sakura!', '1993-05-03', 'sakura.jpg', 'Electronic producer from Tokyo.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Diego Morales', 'diego_m', 'diego@example.es', 'diegopass', '1991-09-09', NULL, 'Rap and beats curator.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Amelia Brown', 'amelia_b', 'amelia@example.co.uk', 'abrown', '1994-12-02', 'amelia.jpg', 'Classical pianist and teacher.', FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Noah Smith', 'noah_s', 'noah@example.com', 'noahs', '2000-06-18', NULL, 'Hip-hop playlists curator.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Yara Costa', 'yara_c', 'yara@example.pt', 'yarapw', '1995-10-20', NULL, 'Portuguese singer.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Oliver James', 'oliver_j', 'oliver@example.com', 'ojames', '1987-03-03', 'oliver.jpg', 'Bass player.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Priya Kumar', 'priya_k', 'priya@example.in', 'priya99', '1996-08-08', NULL, 'Bollywood & fusion fan.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Luca Romano', 'luca_r', 'luca@example.it', 'lucaPW', '1992-02-22', NULL, 'Italian opera enthusiast.', FALSE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Chloe Martin', 'chloe_m', 'chloe@example.fr', 'chloepass', '1998-01-01', 'chloe.jpg', 'Indie playlists and reviews.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Hiroshi Sato', 'hiro_s', 'hiro@example.jp', 'hiro2020', '1989-10-10', NULL, 'Vinyl collector, Tokyo.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Fatima Al-Masri', 'fatima_m', 'fatima@example.sa', 'fatimaPW', '1993-04-29', NULL, 'World music curator.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Mateo Alvarez', 'mateo_a', 'mateo@example.mx', 'mateopass', '1991-11-30', NULL, 'Latin beats lover.', TRUE, FALSE);

INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES ('Zoe Green', 'zoe_g', 'zoe@example.com', 'zoegreen', '1999-09-09', 'zoe.jpg', 'Festival photographer & music lover.', TRUE, FALSE);

-----------------------------------------
-- FOLLOWING
-----------------------------------------
INSERT INTO followings (id_user, id_following) VALUES (1,2);
INSERT INTO followings (id_user, id_following) VALUES (1,3);
INSERT INTO followings (id_user, id_following) VALUES (2,1);
INSERT INTO followings (id_user, id_following) VALUES (2,4);
INSERT INTO followings (id_user, id_following) VALUES (3,5);
INSERT INTO followings (id_user, id_following) VALUES (4,2);
INSERT INTO followings (id_user, id_following) VALUES (5,1);
INSERT INTO followings (id_user, id_following) VALUES (6,2);
INSERT INTO followings (id_user, id_following) VALUES (7,1);
INSERT INTO followings (id_user, id_following) VALUES (8,3);
INSERT INTO followings (id_user, id_following) VALUES (9,2);
INSERT INTO followings (id_user, id_following) VALUES (10,1);

INSERT INTO followings (id_user, id_following) VALUES (11,1);
INSERT INTO followings (id_user, id_following) VALUES (12,11);
INSERT INTO followings (id_user, id_following) VALUES (13,3);
INSERT INTO followings (id_user, id_following) VALUES (14,2);
INSERT INTO followings (id_user, id_following) VALUES (15,7);
INSERT INTO followings (id_user, id_following) VALUES (16,8);
INSERT INTO followings (id_user, id_following) VALUES (17,5);
INSERT INTO followings (id_user, id_following) VALUES (18,9);
INSERT INTO followings (id_user, id_following) VALUES (19,10);
INSERT INTO followings (id_user, id_following) VALUES (20,2);
INSERT INTO followings (id_user, id_following) VALUES (21,4);
INSERT INTO followings (id_user, id_following) VALUES (22,6);
INSERT INTO followings (id_user, id_following) VALUES (23,7);
INSERT INTO followings (id_user, id_following) VALUES (24,8);
INSERT INTO followings (id_user, id_following) VALUES (25,11);

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
-- ALBUM
-----------------------------------------
INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('OK Computer', 'Radiohead', '1997-05-21', 'Airbag; Paranoid Android; No Surprises', 101);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Nevermind', 'Nirvana', '1991-09-24', 'Smells Like Teen Spirit; Come As You Are', 102);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Future Nostalgia', 'Dua Lipa', '2020-03-27', 'Levitating; Don’t Start Now', 103);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Kind of Blue', 'Miles Davis', '1959-08-17', 'So What; Freddie Freeloader', 104);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Discovery', 'Daft Punk', '2001-03-12', 'One More Time; Harder Better Faster Stronger', 105);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Hybrid Theory', 'Linkin Park', '2000-10-24', 'In the End; Crawling', 106);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('The Dark Side of the Moon', 'Pink Floyd', '1973-03-01', 'Time; Money', 107);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Folklore', 'Taylor Swift', '2020-07-24', 'Cardigan; Exile', 108);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('American Idiot', 'Green Day', '2004-09-21', 'Holiday; Boulevard of Broken Dreams', 109);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('To Pimp a Butterfly', 'Kendrick Lamar', '2015-03-15', 'Alright; King Kunta', 110);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('A Moon Shaped Pool', 'Radiohead', '2016-05-08', 'Burn the Witch; Daydreaming', 111);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Blonde', 'Frank Ocean', '2016-08-20', 'Nikes; Ivy', 112);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Random Access Memories', 'Daft Punk', '2013-05-17', 'Get Lucky; Instant Crush', 113);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Channel Orange', 'Frank Ocean', '2012-07-10', 'Thinkin Bout You; Pyramids', 114);

INSERT INTO albums (title, artist, release_date, songlist, id_music_brainz)
VALUES ('Currents', 'Tame Impala', '2015-07-17', 'Let It Happen; The Less I Know', 115);

-----------------------------------------
-- FAVOURITE_GENRE
-----------------------------------------
INSERT INTO favourite_genres (id_user, id_genre) VALUES (1,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (1,1);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (2,1);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (3,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (4,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (5,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (6,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (7,1);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (8,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (9,4);

INSERT INTO favourite_genres (id_user, id_genre) VALUES (10,6);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (11,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (12,7);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (13,8);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (14,1);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (15,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (16,7);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (17,8);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (18,9);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (19,2);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (20,3);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (21,1);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (22,5);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (23,4);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (24,7);
INSERT INTO favourite_genres (id_user, id_genre) VALUES (25,2);

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
INSERT INTO favourite_albums (id_user, id_album) VALUES (1,1);
INSERT INTO favourite_albums (id_user, id_album) VALUES (2,2);
INSERT INTO favourite_albums (id_user, id_album) VALUES (3,3);
INSERT INTO favourite_albums (id_user, id_album) VALUES (4,6);
INSERT INTO favourite_albums (id_user, id_album) VALUES (5,8);
INSERT INTO favourite_albums (id_user, id_album) VALUES (6,9);
INSERT INTO favourite_albums (id_user, id_album) VALUES (7,5);
INSERT INTO favourite_albums (id_user, id_album) VALUES (8,4);
INSERT INTO favourite_albums (id_user, id_album) VALUES (9,10);
INSERT INTO favourite_albums (id_user, id_album) VALUES (10,7);

INSERT INTO favourite_albums (id_user, id_album) VALUES (11,11);
INSERT INTO favourite_albums (id_user, id_album) VALUES (12,12);
INSERT INTO favourite_albums (id_user, id_album) VALUES (13,13);
INSERT INTO favourite_albums (id_user, id_album) VALUES (14,14);
INSERT INTO favourite_albums (id_user, id_album) VALUES (15,15);

-----------------------------------------
-- ALBUM_REVIEW
-----------------------------------------
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2024-02-02', 1, 1);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (4, '2024-02-03', 2, 2);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2024-03-01', 3, 3);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (3, '2024-03-05', 4, 4);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2024-04-10', 5, 5);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (4, '2024-04-15', 6, 6);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2024-05-01', 7, 7);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (3, '2024-05-12', 8, 8);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (4, '2024-06-08', 9, 9);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2024-07-01', 10, 10);

INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (4, '2023-10-02', 11, 11);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2022-08-20', 12, 12);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2018-06-11', 13, 13);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (4, '2019-09-30', 14, 14);
INSERT INTO album_reviews (rating, review_date, id_album, id_user) VALUES (5, '2021-01-15', 15, 15);

-----------------------------------------
-- GROUPS
-----------------------------------------
INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Indie Lovers', 1, 'For fans of indie rock and dream pop.', TRUE, 5);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Metalheads United', 4, 'All things metal.', TRUE, 4);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Pop Central', 3, 'Pop fans unite.', TRUE, 6);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Jazz Appreciation', 8, 'Discover and share jazz classics.', FALSE, 3);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Punk Rebellion', 6, 'Loud and proud.', TRUE, 4);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Vinyl Collectors', 7, 'Share your latest vinyl finds.', TRUE, 3);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Classical Harmony', 9, 'Orchestra enthusiasts.', FALSE, 2);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Electronic Nation', 5, 'Beats, bass, and synths.', TRUE, 3);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Rhyme & Flow', 10, 'Hip-hop and rap lovers.', TRUE, 5);

INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES ('Album Reviewers', 2, 'For thoughtful review discussions.', TRUE, 5);

-----------------------------------------
-- GROUP_MEMBER
-----------------------------------------
INSERT INTO group_members (id_group, id_user) VALUES (1,1);
INSERT INTO group_members (id_group, id_user) VALUES (1,2);
INSERT INTO group_members (id_group, id_user) VALUES (1,5);
INSERT INTO group_members (id_group, id_user) VALUES (1,8);
INSERT INTO group_members (id_group, id_user) VALUES (1,11);

INSERT INTO group_members (id_group, id_user) VALUES (2,4);
INSERT INTO group_members (id_group, id_user) VALUES (2,6);
INSERT INTO group_members (id_group, id_user) VALUES (2,7);
INSERT INTO group_members (id_group, id_user) VALUES (2,16);

INSERT INTO group_members (id_group, id_user) VALUES (3,3);
INSERT INTO group_members (id_group, id_user) VALUES (3,1);
INSERT INTO group_members (id_group, id_user) VALUES (3,5);
INSERT INTO group_members (id_group, id_user) VALUES (3,12);
INSERT INTO group_members (id_group, id_user) VALUES (3,13);

INSERT INTO group_members (id_group, id_user) VALUES (4,8);
INSERT INTO group_members (id_group, id_user) VALUES (4,9);
INSERT INTO group_members (id_group, id_user) VALUES (4,18);

INSERT INTO group_members (id_group, id_user) VALUES (5,6);
INSERT INTO group_members (id_group, id_user) VALUES (5,2);
INSERT INTO group_members (id_group, id_user) VALUES (5,20);

INSERT INTO group_members (id_group, id_user) VALUES (6,7);
INSERT INTO group_members (id_group, id_user) VALUES (6,1);
INSERT INTO group_members (id_group, id_user) VALUES (6,21);

INSERT INTO group_members (id_group, id_user) VALUES (7,9);
INSERT INTO group_members (id_group, id_user) VALUES (7,10);

INSERT INTO group_members (id_group, id_user) VALUES (8,5);
INSERT INTO group_members (id_group, id_user) VALUES (8,6);
INSERT INTO group_members (id_group, id_user) VALUES (8,24);

INSERT INTO group_members (id_group, id_user) VALUES (9,10);
INSERT INTO group_members (id_group, id_user) VALUES (9,19);
INSERT INTO group_members (id_group, id_user) VALUES (9,23);

INSERT INTO group_members (id_group, id_user) VALUES (10,2);
INSERT INTO group_members (id_group, id_user) VALUES (10,14);
INSERT INTO group_members (id_group, id_user) VALUES (10,25);

-----------------------------------------
-- JOIN_REQUEST
-----------------------------------------
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-07-10', 1, 3);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-07-12', 1, 5);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2024-07-15', 2, 2);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-08-01', 3, 4);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-08-05', 4, 8);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-08-07', 6, 1);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-08-09', 9, 10);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2024-09-01', 8, 7);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2024-09-10', 3, 9);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-09-20', 10, 5);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2023-11-05', 2, 15);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2022-06-12', 6, 11);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('declined', '2021-04-03', 7, 16);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('accepted', '2020-09-10', 4, 12);
INSERT INTO join_requests (status, created_at, id_group, id_user) VALUES ('pending', '2024-10-05', 5, 22);

-----------------------------------------
-- FOLLOW_REQUEST
-----------------------------------------
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-01', 'pending', 3, 5);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-02', 'accepted', 1, 2);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-03', 'accepted', 2, 1);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-04', 'declined', 4, 5);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-05', 'accepted', 5, 3);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-06', 'accepted', 6, 7);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-07', 'pending', 8, 9);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-08', 'accepted', 9, 10);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-09', 'declined', 10, 4);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-05-10', 'pending', 2, 3);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2023-02-11', 'accepted', 11, 1);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2021-08-09', 'accepted', 12, 2);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2020-12-24', 'declined', 13, 4);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2019-06-06', 'accepted', 14, 3);
INSERT INTO follow_requests (created_at, status, id_follower, id_followed) VALUES ('2024-04-01', 'accepted', 15, 5);

-----------------------------------------
-- CONTENT
-----------------------------------------
INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-01', 10, 2, 'Best album ever?', 'I still think OK Computer is unbeatable.', NULL, 1, 1, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-03', 8, 1, 'New Dua Lipa album', 'Love the production on this one.', NULL, 3, 3, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-05', 5, 0, 'Metal fans?', 'What’s your favorite Linkin Park track?', NULL, 4, 2, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-07', 7, 1, 'Jazz night', 'Currently spinning Kind of Blue.', NULL, 8, 4, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-09', 12, 3, 'New music Friday!', 'What’s everyone listening to?', NULL, 5, 8, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-05-12', 9, 2, 'Check out my latest playlist', 'A bit of indie + synthwave.', 'img1.jpg', 7, 6, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-01', 4, 0, 'Festival memories', 'Photos from the weekend.', 'fest1.jpg', 25, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-03', 6, 1, 'Bedroom production tips', 'How I EQ my vocals.', NULL, 13, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-10', 15, 5, 'Vinyl for sale', 'Selling a rare pressing of OK Computer.', 'vinyl.jpg', 7, 6, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-06-15', 3, 0, 'Classical q&a', 'What recordings of the 9th do you recommend?', NULL, 15, 7, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-02', 3, 0, NULL, 'Agree completely!', NULL, 2, 1, 1);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-06', 2, 0, NULL, 'In the End, hands down.', NULL, 6, 2, 3);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-08', 1, 0, NULL, 'A masterpiece.', NULL, 9, 4, 4);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-05-10', 2, 0, NULL, 'Levitating never gets old!', NULL, 3, 8, 5);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('comment', '2024-06-12', 0, 0, NULL, 'Is this still available?', NULL, 11, 6, 9);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-01', 8, 2, 'New single released', 'Dropped my new single today — feedback welcome!', NULL, 11, NULL, NULL);

INSERT INTO contents (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES ('post', '2024-07-10', 2, 0, 'Practice session', '30 minute live stream tomorrow.', NULL, 20, NULL, NULL);

-----------------------------------------
-- REACTION
-----------------------------------------
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-02', 2, 1);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-02', 3, 1);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-04', 1, 3);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-05', 5, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-05-06', 7, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-07', 8, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-08', 9, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-05-09', 4, 8);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-05-10', 10, 9);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-05-11', 5, 10);

INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-02', 11, 11);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-03', 12, 12);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-06-04', 13, 9);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-05', 14, 7);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-06', 15, 6);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('report', '2024-06-07', 16, 4);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-08', 17, 11);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('confetti', '2024-06-09', 18, 13);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-06-10', 19, 14);
INSERT INTO reactions (type, created_at, id_user, id_content) VALUES ('like', '2024-07-02', 20, 15);

-----------------------------------------
-- NOTIFICATION
-----------------------------------------
-- Notifications for follow requests
INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-02', FALSE, 'followRequest', 2, 1, 2, NULL, NULL, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-03', TRUE, 'acceptedFollowRequest', 1, 2, 2, NULL, NULL, NULL);

-- reaction notification (reaction id 1 created above)
INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-04', FALSE, 'reaction', 1, 2, NULL, NULL, NULL, 1);

-- comment notification (comment content id 11)
INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-05', FALSE, 'comment', 1, 2, NULL, NULL, 11, NULL);

-- join group request notification (join_request id 1)
INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-06', FALSE, 'joinGroupRequest', 3, 4, NULL, 1, NULL, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-07', TRUE, 'acceptedJoinGroupRequest', 5, 1, NULL, 2, NULL, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-08', FALSE, 'reaction', 4, 5, NULL, NULL, NULL, 5);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-09', FALSE, 'comment', 8, 3, NULL, NULL, 9, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-10', TRUE, 'reaction', 6, 7, NULL, NULL, NULL, 10);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-05-11', FALSE, 'startFollowing', 9, 10, NULL, NULL, NULL, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-02', FALSE, 'comment', 11, 13, NULL, NULL, 11, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-04', FALSE, 'reaction', 12, 11, NULL, NULL, NULL, 12);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-06', FALSE, 'comment', 8, 9, NULL, NULL, 13, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-07', TRUE, 'acceptedFollowRequest', 2, 11, 11, NULL, NULL, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-09', FALSE, 'reaction', 4, 13, NULL, NULL, NULL, 14);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-06-15', FALSE, 'comment', 15, 14, NULL, NULL, 15, NULL);

INSERT INTO  notifications (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES ('2024-07-03', FALSE, 'reaction', 11, 20, NULL, NULL, NULL, 20);
