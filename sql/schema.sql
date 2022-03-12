CREATE DATABASE IF NOT EXISTS taskforce_77211
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE taskforce_77211;

CREATE TABLE categories
(
  id    INT PRIMARY KEY AUTO_INCREMENT,
  name  VARCHAR(255) NOT NULL,
  alias VARCHAR(255) NOT NULL
);

CREATE TABLE cities
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(255) NOT NULL,
  coordinates POINT        NOT NULL
);

CREATE TABLE files
(
  id   INT PRIMARY KEY AUTO_INCREMENT,
  path VARCHAR(255) NOT NULL
);

CREATE TABLE users
(
  id                 INT PRIMARY KEY AUTO_INCREMENT,
  email              VARCHAR(255) UNIQUE                     NOT NULL,
  password           VARCHAR(255)                            NOT NULL,
  name               VARCHAR(255)                            NOT NULL,
  birthdate          DATE                                    NULL,
  info               TEXT                                    NULL,
  avatar_file_id     INT                                     NULL,
  rating             DECIMAL(3, 2) DEFAULT 0                 NOT NULL,
  city_id            INT                                     NOT NULL,
  phone              VARCHAR(255)                            NULL,
  telegram           VARCHAR(64)                             NULL,
  role               TINYINT       DEFAULT 1                 NOT NULL,
  status             TINYINT                                 NULL,
  last_activity_at   DATETIME      DEFAULT CURRENT_TIMESTAMP NOT NULL,
  failed_tasks_count INT           DEFAULT 0                 NOT NULL,
  show_only_customer BOOL          DEFAULT 0                 NOT NULL,
  FOREIGN KEY (city_id) REFERENCES cities (id),
  FOREIGN KEY (avatar_file_id) REFERENCES files (id)
);

CREATE TABLE user_categories
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  user_id     INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE tasks
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT                                NOT NULL,
  executor_id INT                                NULL,
  status      TINYINT  DEFAULT 1                 NOT NULL,
  title       VARCHAR(255)                       NOT NULL,
  description TEXT                               NULL,
  category_id INT                                NOT NULL,
  budget      INT                                NULL,
  city_id     INT                                NULL,
  coordinates POINT                              NULL,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  deadline_at DATETIME                           NULL,
  FOREIGN KEY (customer_id) REFERENCES users (id),
  FOREIGN KEY (executor_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id),
  FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE task_files
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  task_id INT NOT NULL,
  file_id INT NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  FOREIGN KEY (file_id) REFERENCES files (id)
);

CREATE TABLE responses
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  task_id     INT            NOT NULL,
  executor_id INT            NOT NULL,
  comment     TEXT           NULL,
  budget      INT            NULL,
  is_refused  BOOL DEFAULT 0 NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  FOREIGN KEY (executor_id) REFERENCES users (id)
);

CREATE TABLE reviews
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  task_id INT        NOT NULL,
  rate    TINYINT(5) NOT NULL,
  comment TEXT       NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id)
);
