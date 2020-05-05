CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    twitter_account VARCHAR(255),
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL
);

CREATE TABLE bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bookmark_url_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(50) NOT NULL,
    description TEXT,
    count INT NOT NULL,
    looked_status INT NOT NULL DEFAULT 0,
    favorite_status INT NOT NULL DEFAULT 0,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    deleted INT NOT NULL DEFAULT 0
);

CREATE TABLE bookmark_urls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url TEXT NOT NULL,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    deleted INT NOT NULL DEFAULT 0
);

CREATE TABLE bookmarks_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bookmark_id INT NOT NULL,
    tag_id INT NOT NULL,
    created DATETIME NOT NULL,
    deleted INT NOT NULL DEFAULT 0
);

CREATE TABLE tag_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tag_id INT NOT NULL,
    created DATETIME NOT NULL,
    deleted INT NOT NULL DEFAULT 0
);
