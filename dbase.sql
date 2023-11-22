CREATE TABLE users(
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    lastname VARCHAR (100),
    email VARCHAR (200),
    password VARCHAR(200),
    image VARCHAR(200),
    token VARCHAR(200),
    bio TEXT
);

CREATE TABLE movies(
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    image VARCHAR(200),
    trailer VARCHAR(150),
    category VARCHAR(50),
    length VARCHAR(50),
    users_id INT(11) UNSIGNED,
    FOREIGN KEY(users_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE reviews(
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rating INT,
    review TEXT,
    users_id INT(11) UNSIGNED,
    movies_id INT(11) UNSIGNED,
    FOREIGN KEY(users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(movies_id) REFERENCES movies(id) ON DELETE CASCADE
);


--Ajustes de relação para db já criado
ALTER TABLE reviews
DROP FOREIGN KEY reviews_ibfk_2;

ALTER TABLE reviews
ADD FOREIGN KEY (movies_id) REFERENCES movies(id) ON DELETE CASCADE;