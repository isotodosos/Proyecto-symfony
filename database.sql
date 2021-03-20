CREATE DATABASE IF NOT EXISTS symfony_proyecto;
USE symfony_proyecto;

CREATE TABLE IF NOT EXISTS users(
    id           int(255) auto_increment not null,
    role         varchar(50),
    name         varchar(100),
    surname      varchar(200),
    email        varchar(255),
    password     varchar(255),
    created_at   datetime,
    CONSTRAINT pk_users PRIMARY KEY(id)
) ENGINE=InnoDb;

INSERT INTO users VALUES(NULL, 'ROLE_USER', 'Ivan', 'Sotodosos', 'ivan@ivan.es', 'password', CURTIME());
INSERT INTO users VALUES(NULL, 'ROLE_USER', 'Manuel', 'Lopez', 'manuel@manuel.es', 'password', CURTIME());
INSERT INTO users VALUES(NULL, 'ROLE_USER', 'Maria', 'Perez', 'maria@maria.es', 'password', CURTIME());

CREATE TABLE IF NOT EXISTS tasks(
    id           int(255) auto_increment not null,
    user_id      int(255) not null,
    title        varchar(255),
    content      text,
    priority     varchar(20),
    hours        int(100),
    created_at   datetime,
    CONSTRAINT pk_tasks PRIMARY KEY(id),
    CONSTRAINT fk_tasks_users FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDb;

INSERT INTO tasks VALUES(NULL, 1, 'tarea 1', 'Contenido de prueba 1', 'high', 40, CURTIME());
INSERT INTO tasks VALUES(NULL, 2, 'tarea 2', 'Contenido de prueba 2', 'low', 10, CURTIME());
INSERT INTO tasks VALUES(NULL, 3, 'tarea 3', 'Contenido de prueba 3', 'medium', 20, CURTIME());
INSERT INTO tasks VALUES(NULL, 4, 'tarea 4', 'Contenido de prueba 4', 'high', 50, CURTIME());