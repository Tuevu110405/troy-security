-- Tạo database
CREATE DATABASE IF NOT EXISTS socialnet;
USE socialnet;

-- Tạo bảng account theo đúng yêu cầu
CREATE TABLE account (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT
);

-- Tạo bảng friendships (Tính năng mở rộng của bạn)
CREATE TABLE friendships (
    username1 VARCHAR(50),
    username2 VARCHAR(50),
    status VARCHAR(20),
    PRIMARY KEY (username1, username2),
    FOREIGN KEY (username1) REFERENCES account(username) ON DELETE CASCADE,
    FOREIGN KEY (username2) REFERENCES account(username) ON DELETE CASCADE
);
