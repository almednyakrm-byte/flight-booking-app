CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE رحلات (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE ملاحة (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE ملاحة_الرحلات (
  id INT AUTO_INCREMENT,
  الرحلة_id INT NOT NULL,
  ملاحة_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (رحلة_id),
  KEY (ملاحة_id),
  CONSTRAINT fk_رحلة FOREIGN KEY (رحلة_id) REFERENCES رحلات (id),
  CONSTRAINT fk_ملاحة FOREIGN KEY (ملاحة_id) REFERENCES ملاحة (id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO رحلات (name, description)
VALUES ('رحلة 1', 'وصف الرحلة 1'),
       ('رحلة 2', 'وصف الرحلة 2');

INSERT INTO ملاحة (name, description)
VALUES ('ملاحة 1', 'وصف الملاحة 1'),
       ('ملاحة 2', 'وصف الملاحة 2');

INSERT INTO ملاحة_الرحلات (رحلة_id, ملاحة_id)
VALUES (1, 1),
       (1, 2),
       (2, 1);