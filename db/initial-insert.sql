-- Inserting default user; password=123456
INSERT INTO users(id, name, password, email, role)
    VALUES (nextval('users_id_seq'), 'Sample Admin', 
        '$2y$10$NFK2QvA1FQiXOI3ZU8i9IuBYObL2yN/Od36zPlqDi6jtQ1Expi8p2', 'test1@mailinator.com', 999);