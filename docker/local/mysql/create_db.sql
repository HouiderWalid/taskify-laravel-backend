CREATE DATABASE test_db;
CREATE USER 'test_user'@'%' IDENTIFIED BY 'test_password';
GRANT ALL PRIVILEGES ON test_db.* TO 'test_user'@'%';
FLUSH PRIVILEGES;

SELECT 'Initialization script has run successfully!' AS message;
