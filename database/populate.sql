-- script for populating the database with sample information

-----------------------------------------
-- USERS
-----------------------------------------
INSERT INTO users (name, username, email, password, birth_date, profile_picture, description, is_public, is_admin)
VALUES
('Alice Martins', 'alice_m', 'alice@email.com', 'pass123', '1998-04-21', 'pic1.jpg', 'Music lover and vinyl collector.', TRUE, FALSE),
('Bruno Costa', 'brunoc', 'bruno@email.com', 'pass456', '1995-08-10', 'pic2.jpg', 'Into indie and rock.', TRUE, FALSE),
('Carla Silva', 'carla_s', 'carla@email.com', 'pass789', '2000-02-15', NULL, 'Pop and dance enthusiast.', TRUE, FALSE),
('Daniel Ferreira', 'danielf', 'daniel@email.com', 'pass000', '1992-11-05', 'pic3.jpg', 'Metal head 🤘', TRUE, FALSE),
('Eva Ramos', 'evar', 'eva@email.com', 'passabc', '1999-06-12', 'pic4.jpg', 'Dream pop and shoegaze.', FALSE, FALSE),
('Filipe Sousa', 'filipe_s', 'filipe@email.com', 'passdef', '2001-09-23', NULL, 'Punk is not dead.', TRUE, FALSE),
('Gonçalo Lima', 'goncalo', 'goncalo@email.com', 'passghi', '1997-01-02', 'pic5.jpg', 'Collector of obscure records.', TRUE, FALSE),
('Helena Duarte', 'helenad', 'helena@email.com', 'passjkl', '2002-03-30', NULL, 'Just vibes 🎶', TRUE, FALSE),
('Inês Oliveira', 'ines_o', 'ines@email.com', 'passmno', '1996-12-19', 'pic6.jpg', 'New wave forever.', TRUE, FALSE),
('João Pinto', 'joaop', 'joao@email.com', 'passpqr', '1994-05-08', 'pic7.jpg', 'Admin account', TRUE, TRUE);

-----------------------------------------
-- FOLLOWING
-----------------------------------------
INSERT INTO following (id_user, id_following)
VALUES
(1,2), (1,3), (2,1), (2,4), (3,5), (4,2),
(5,1), (6,2), (7,1), (8,3), (9,2), (10,1);

-----------------------------------------
-- GENRE
-----------------------------------------
INSERT INTO genre (name)
VALUES
('Rock'), ('Pop'), ('Metal'), ('Indie'), ('Punk'),
('Jazz'), ('Electronic'), ('Hip-Hop'), ('Classical'), ('Folk');

-----------------------------------------
-- ALBUM
-----------------------------------------
INSERT INTO album (title, artist, release_date, songlist, id_music_brainz)
VALUES
('OK Computer', 'Radiohead', '1997-05-21', 'Airbag; Paranoid Android; No Surprises', 101),
('Nevermind', 'Nirvana', '1991-09-24', 'Smells Like Teen Spirit; Come As You Are', 102),
('Future Nostalgia', 'Dua Lipa', '2020-03-27', 'Levitating; Don’t Start Now', 103),
('Kind of Blue', 'Miles Davis', '1959-08-17', 'So What; Freddie Freeloader', 104),
('Discovery', 'Daft Punk', '2001-03-12', 'One More Time; Harder Better Faster Stronger', 105),
('Hybrid Theory', 'Linkin Park', '2000-10-24', 'In the End; Crawling', 106),
('The Dark Side of the Moon', 'Pink Floyd', '1973-03-01', 'Time; Money', 107),
('Folklore', 'Taylor Swift', '2020-07-24', 'Cardigan; Exile', 108),
('American Idiot', 'Green Day', '2004-09-21', 'Holiday; Boulevard of Broken Dreams', 109),
('To Pimp a Butterfly', 'Kendrick Lamar', '2015-03-15', 'Alright; King Kunta', 110);

-----------------------------------------
-- FAVOURITE_GENRE
-----------------------------------------
INSERT INTO favourite_genre (id_user, id_genre)
VALUES
(1,4), (1,1), (2,1), (3,2), (4,3),
(5,4), (6,5), (7,1), (8,2), (9,4);

-----------------------------------------
-- ALBUM_GENRE
-----------------------------------------
INSERT INTO album_genre (id_album, id_genre)
VALUES
(1,4), (2,1), (3,2), (4,6), (5,7),
(6,3), (7,1), (8,2), (9,5), (10,8);

-----------------------------------------
-- FAVOURITE_ALBUM
-----------------------------------------
INSERT INTO favourite_album (id_user, id_album)
VALUES
(1,1), (2,2), (3,3), (4,6), (5,8),
(6,9), (7,5), (8,4), (9,10), (10,7);

-----------------------------------------
-- ALBUM_REVIEW
-----------------------------------------
INSERT INTO album_review (rating, review_date, id_album, id_user)
VALUES
(5, '2024-02-02', 1, 1),
(4, '2024-02-03', 2, 2),
(5, '2024-03-01', 3, 3),
(3, '2024-03-05', 4, 4),
(5, '2024-04-10', 5, 5),
(4, '2024-04-15', 6, 6),
(5, '2024-05-01', 7, 7),
(3, '2024-05-12', 8, 8),
(4, '2024-06-08', 9, 9),
(5, '2024-07-01', 10, 10);

-----------------------------------------
-- GROUPS
-----------------------------------------
INSERT INTO groups (name, owner, description, is_public, member_count)
VALUES
('Indie Lovers', 1, 'For fans of indie rock and dream pop.', TRUE, 5),
('Metalheads United', 4, 'All things metal.', TRUE, 4),
('Pop Central', 3, 'Pop fans unite.', TRUE, 6),
('Jazz Appreciation', 8, 'Discover and share jazz classics.', FALSE, 3),
('Punk Rebellion', 6, 'Loud and proud.', TRUE, 4),
('Vinyl Collectors', 7, 'Share your latest vinyl finds.', TRUE, 3),
('Classical Harmony', 9, 'Orchestra enthusiasts.', FALSE, 2),
('Electronic Nation', 5, 'Beats, bass, and synths.', TRUE, 3),
('Rhyme & Flow', 10, 'Hip-hop and rap lovers.', TRUE, 5),
('Album Reviewers', 2, 'For thoughtful review discussions.', TRUE, 5);

-----------------------------------------
-- GROUP_MEMBER
-----------------------------------------
INSERT INTO group_member (id_group, id_user)
VALUES
(1,1),(1,2),(1,5),(1,8),
(2,4),(2,6),(2,7),
(3,3),(3,1),(3,5),
(4,8),(4,9),
(5,6),(5,2),
(6,7),(6,1),
(7,9),(7,10),
(8,5),(8,6);

-----------------------------------------
-- JOIN_REQUEST
-----------------------------------------
INSERT INTO join_request (status, created_at, id_group, id_user)
VALUES
('pending', '2024-07-10', 1, 3),
('accepted', '2024-07-12', 1, 5),
('declined', '2024-07-15', 2, 2),
('pending', '2024-08-01', 3, 4),
('accepted', '2024-08-05', 4, 8),
('accepted', '2024-08-07', 6, 1),
('pending', '2024-08-09', 9, 10),
('declined', '2024-09-01', 8, 7),
('accepted', '2024-09-10', 3, 9),
('pending', '2024-09-20', 10, 5);

-----------------------------------------
-- FOLLOW_REQUEST
-----------------------------------------
INSERT INTO follow_request (created_at, status, id_follower, id_followed)
VALUES
('2024-05-01', 'pending', 3, 5),
('2024-05-02', 'accepted', 1, 2),
('2024-05-03', 'accepted', 2, 1),
('2024-05-04', 'declined', 4, 5),
('2024-05-05', 'accepted', 5, 3),
('2024-05-06', 'accepted', 6, 7),
('2024-05-07', 'pending', 8, 9),
('2024-05-08', 'accepted', 9, 10),
('2024-05-09', 'declined', 10, 4),
('2024-05-10', 'pending', 2, 3);

-----------------------------------------
-- CONTENT
-----------------------------------------
INSERT INTO content (type, created_at, likes, comments, title, description, img, owner, id_group, reply_to)
VALUES
('post', '2024-05-01', 10, 2, 'Best album ever?', 'I still think OK Computer is unbeatable.', NULL, 1, 1, NULL),
('comment', '2024-05-02', 3, 0, NULL, 'Agree completely!', NULL, 2, 1, 1),
('post', '2024-05-03', 8, 1, 'New Dua Lipa album', 'Love the production on this one.', NULL, 3, 3, NULL),
('post', '2024-05-05', 5, 0, 'Metal fans?', 'What’s your favorite Linkin Park track?', NULL, 4, 2, NULL),
('comment', '2024-05-06', 2, 0, NULL, 'In the End, hands down.', NULL, 6, 2, 4),
('post', '2024-05-07', 7, 1, 'Jazz night', 'Currently spinning Kind of Blue.', NULL, 8, 4, NULL),
('comment', '2024-05-08', 1, 0, NULL, 'A masterpiece.', NULL, 9, 4, 6),
('post', '2024-05-09', 12, 3, 'New music Friday!', 'What’s everyone listening to?', NULL, 5, 8, NULL),
('comment', '2024-05-10', 2, 0, NULL, 'Levitating never gets old!', NULL, 3, 8, 8),
('post', '2024-05-12', 9, 2, 'Check out my latest playlist', 'A bit of indie + synthwave.', 'img1.jpg', 7, 6, NULL);

-----------------------------------------
-- REACTION
-----------------------------------------
INSERT INTO reaction (type, created_at, id_user, id_content)
VALUES
('like', '2024-05-02', 2, 1),
('confetti', '2024-05-02', 3, 1),
('like', '2024-05-04', 1, 3),
('like', '2024-05-05', 5, 4),
('report', '2024-05-06', 7, 4),
('confetti', '2024-05-07', 8, 6),
('like', '2024-05-08', 9, 6),
('like', '2024-05-09', 4, 8),
('report', '2024-05-10', 10, 9),
('confetti', '2024-05-11', 5, 10);

-----------------------------------------
-- NOTIFICATION
-----------------------------------------
INSERT INTO notification (created_at, is_read, type, receiver, actor, id_follow_request, id_group_join_request, id_comment, id_reaction)
VALUES
('2024-05-02', FALSE, 'followRequest', 2, 1, 2, NULL, NULL, NULL),
('2024-05-03', TRUE, 'acceptedFollowRequest', 1, 2, 2, NULL, NULL, NULL),
('2024-05-04', FALSE, 'reaction', 1, 2, NULL, NULL, NULL, 1),
('2024-05-05', FALSE, 'comment', 1, 2, NULL, NULL, 2, NULL),
('2024-05-06', FALSE, 'joinGroupRequest', 3, 4, NULL, 1, NULL, NULL),
('2024-05-07', TRUE, 'acceptedJoinGroupRequest', 5, 1, NULL, 2, NULL, NULL),
('2024-05-08', FALSE, 'reaction', 4, 5, NULL, NULL, NULL, 5),
('2024-05-09', FALSE, 'comment', 8, 3, NULL, NULL, 9, NULL),
('2024-05-10', TRUE, 'reaction', 6, 7, NULL, NULL, NULL, 10),
('2024-05-11', FALSE, 'startFollowing', 9, 10, NULL, NULL, NULL, NULL);
