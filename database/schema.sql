CREATE TABLE project (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, /* можно использовать serial*/
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) Engine=InnoDB;
/*
Не хватает полей active, deleted. В будущем точно пригодятся
 */

CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, /* можно использовать serial*/
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(16) NOT NULL, /* лучше сделать справочник для статусов и здесь хранить id статуса */
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) Engine=InnoDB;
/*
Тоже полей не хватает active, deleted
На project_id желательно повесить foreign key
Также необходимо повесить индекс на project_id и на status, т.к. эти поля буду часто участвовать в выборке
 */