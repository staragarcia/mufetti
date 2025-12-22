-- initial inserts into search vectors for FTS

UPDATE albums SET title = title WHERE search_vector IS NULL;
        
UPDATE users SET username = username WHERE search_vector IS NULL;
        
UPDATE groups SET name = name WHERE search_vector IS NULL;

UPDATE contents SET description = description WHERE search_vector IS NULL;
        
UPDATE artists SET name = name WHERE search_vector IS NULL;
