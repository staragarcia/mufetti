-- file with transactions to clean create.sql

-----------------------------------------
-- Transactions
-----------------------------------------

-- TRAN01: Like a content post
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

-- TRAN02: Delete a user and clean related data
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


-- TRAN03: Delete a group
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


-- TRAN04: Manage follow request
BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE;

-- Update the follow request status
UPDATE follow_request
SET status = $status
WHERE id = $request_id;

-- If accepted, create the following relationship
INSERT INTO following (id_user, id_following)
SELECT id_followed, id_follower
FROM follow_request
WHERE id = $request_id
AND $status = 'accepted';

-- Notify the requester
INSERT INTO notification (type, receiver, actor, id_follow_request)
SELECT
   CASE WHEN $status = 'accepted' THEN 'acceptedFollowRequest' ELSE 'startFollowing' END,
   id_follower,
   id_followed,
   $request_id
FROM follow_request
WHERE id = $request_id
AND $status = 'accepted';

COMMIT;

-- TRAN05: Approve join request
BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE;

-- 1. Update the join request status
UPDATE join_request
SET status = $status
WHERE id = $request_id;

-- 2. If approved, add user to group members
INSERT INTO group_member (id_group, id_user)
SELECT id_group, id_user
FROM join_request
WHERE id = $request_id
AND $status = 'accepted';

-- 3. Increment the group member count
UPDATE groups
SET member_count = member_count + 1
WHERE id = (SELECT id_group FROM join_request WHERE id = $request_id)
AND $status = 'accepted';

-- 4. Notify the requester if accepted
INSERT INTO notification (type, receiver, actor, id_group_join_request)
SELECT 'acceptedJoinGroupRequest', id_user,
      (SELECT owner FROM groups WHERE id = jr.id_group),
      $request_id
FROM join_request jr
WHERE id = $request_id
AND $status = 'accepted';

COMMIT;
