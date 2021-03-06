DROP DATABASE IF EXISTS elidek;
CREATE DATABASE elidek;
USE elidek;

CREATE TABLE IF NOT EXISTS organisation (
	organisation_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	organisation_name VARCHAR(45) UNIQUE NOT NULL,
	city VARCHAR(45) NOT NULL,
	street_name VARCHAR(45) NOT NULL,
	street_number INT(5) NOT NULL,
	postal_code INT(5) NOT NULL,
	abbreviation VARCHAR(45) NULL DEFAULT 'None',
	organisation_type VARCHAR(15) NOT NULL,
    budget1 BIGINT(12) NOT NULL,
    budget2 BIGINT(12) NULL,
	constraint has_type check(organisation_type in ('Research Center','Firm','University')),
	PRIMARY KEY (organisation_id)
);
    
CREATE TABLE IF NOT EXISTS phone_number (
	phone_id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	organisation_id INT(10) UNSIGNED NOT NULL,
    phone BIGINT(10) UNSIGNED NOT NULL,
    FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE,
	PRIMARY KEY (organisation_id, phone)
);
    
CREATE TABLE IF NOT EXISTS research_center (
	organisation_id INT(10) UNSIGNED NOT NULL,
    organisation_type VARCHAR(15) NOT NULL DEFAULT ('Research Center'),
    ministry_budget BIGINT(12) NOT NULL,
    actions_budget BIGINT(12) NOT NULL,
    constraint has_type check(organisation_type in ('Research Center')),
    PRIMARY KEY (organisation_id),
    FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id) 
		ON UPDATE CASCADE 
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS university (
	organisation_id INT(10) UNSIGNED NOT NULL,
	organisation_type VARCHAR(15) NOT NULL DEFAULT ('University'),
    budget BIGINT(12) NOT NULL,
	constraint has_type check(organisation_type in ('University')),
    PRIMARY KEY (organisation_id),
	FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id) 
		ON UPDATE CASCADE 
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS firm (
	organisation_id INT(10) UNSIGNED NOT NULL,
	organisation_type VARCHAR(15) NOT NULL DEFAULT ('Firm'),
    equity BIGINT(12) NOT NULL,
	constraint has_type check(organisation_type in ('Firm')),
    PRIMARY KEY (organisation_id),
	FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id) 
		ON UPDATE CASCADE 
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS researcher (
	researcher_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	first_name varchar(45) NOT NULL,
	last_name varchar(45) NOT NULL,
	sex varchar(6) NOT NULL,
	date_of_birth DATE NOT NULL,
	date_hired DATE NOT NULL,
    organisation_id INT(10) UNSIGNED NOT NULL,
	CONSTRAINT sex_value CHECK (sex IN ('male', 'female')),
    CONSTRAINT age_hired CHECK (DATEDIFF(date_hired, date_of_birth) >= 18*365.25),
	PRIMARY KEY (researcher_id),
	FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id) 
		ON UPDATE CASCADE
        ON DELETE CASCADE
) ;

-- CREATE INDEX ON researcher (first_name, last_name);

CREATE TABLE IF NOT EXISTS program (
	program_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	title VARCHAR(45) NOT NULL,
	elidek_dep VARCHAR(45) NOT NULL, 
	PRIMARY KEY (program_id)
);
   
CREATE TABLE IF NOT EXISTS elidek_ex (
  elidek_ex_id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(45) NOT NULL,
  last_name VARCHAR(45) NOT NULL ,
  PRIMARY KEY (elidek_ex_id)
);

CREATE TABLE IF NOT EXISTS project(
	project_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	start_date DATE NOT NULL,
	end_date DATE NOT NULL,
	duration INT(5) NULL,
	fund BIGINT(10) UNSIGNED NOT NULL,
	project_title VARCHAR(100) UNIQUE NOT NULL,
	project_description VARCHAR(255),
	elidek_ex_id INT(5) UNSIGNED NOT NULL,
	program_id INT(10) UNSIGNED NOT NULL,
	organisation_id int(10) UNSIGNED NOT NULL,
	supervisor_id INT(10) UNSIGNED NOT NULL,
	evaluator_id INT(10) UNSIGNED NOT NULL,
	eval_grade INT(3) UNSIGNED NOT NULL, 
	eval_date DATE NOT NULL,
	FOREIGN KEY (elidek_ex_id) REFERENCES elidek_ex (elidek_ex_id) 
			ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (program_id) REFERENCES program (program_id) 
			ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (organisation_id) REFERENCES organisation (organisation_id) 
			ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (supervisor_id) REFERENCES researcher (researcher_id) 
			ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (evaluator_id) REFERENCES researcher (researcher_id) 
			ON UPDATE CASCADE ON DELETE RESTRICT,
	PRIMARY KEY(project_id),
    CONSTRAINT fund_size CHECK (fund >= 100000 AND fund <= 1000000),
    CONSTRAINT duration_length CHECK (duration <= 4 AND duration >= 1),
    CONSTRAINT start_end CHECK (start_date < end_date),
    CONSTRAINT evaluation CHECK (start_date >= eval_date)
);
--     INDEX ending (end_date)

CREATE TABLE IF NOT EXISTS participates_in(
	p_id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	project_id int(10) UNSIGNED NOT NULL,
	researcher_id int(10) UNSIGNED NOT NULL,
	FOREIGN KEY (project_id) REFERENCES project (project_id) 
		ON UPDATE CASCADE
        ON DELETE CASCADE,
	FOREIGN KEY (researcher_id) REFERENCES researcher (researcher_id) 
		ON UPDATE CASCADE
        ON DELETE CASCADE,
	PRIMARY KEY (project_id, researcher_id)
  );
  
CREATE TABLE IF NOT EXISTS deliverable (
	del_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	project_id INT(10) UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,
    summary VARCHAR(300) NOT NULL,  
    delivery_date DATE NOT NULL,
    FOREIGN KEY (project_id) REFERENCES project (project_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE,
    PRIMARY KEY (del_id)
);
  
CREATE TABLE IF NOT EXISTS research_field (
	field_name VARCHAR(200) NOT NULL,
    PRIMARY KEY (field_name)
);
  
CREATE TABLE IF NOT EXISTS is_about (
	a_id INT(10) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	project_id INT(10) UNSIGNED NOT NULL, 
    field_name VARCHAR(200) NOT NULL,
	FOREIGN KEY (project_id) REFERENCES project (project_id) 
		ON UPDATE CASCADE
        ON DELETE CASCADE,
	FOREIGN KEY (field_name) REFERENCES research_field (field_name)
		ON UPDATE CASCADE
        ON DELETE RESTRICT,
    PRIMARY KEY (project_id, field_name)
);

CREATE VIEW projects_per_researcher AS
	SELECT r.researcher_id, r.first_name, r.last_name, p.project_id, p.project_title 
    FROM researcher r 
    INNER JOIN participates_in i
    ON r.researcher_id = i.researcher_id 
    INNER JOIN project p
    ON p.project_id = i.project_id
    GROUP BY r.researcher_id, p.project_id
    ORDER BY r.researcher_id, p.project_id;
	
CREATE VIEW projects_per_organisation AS 
	SELECT o.organisation_id, o.organisation_name, p.project_id, p.project_title 
    FROM project p
    INNER JOIN organisation o
    ON p.organisation_id = o.organisation_id
    GROUP BY o.organisation_id, p.project_id;

CREATE INDEX s_d ON project (start_date);
CREATE INDEX e_d ON project (end_date);
CREATE INDEX r_id ON participates_in (researcher_id);
CREATE INDEX pa_id ON participates_in (project_id);
CREATE INDEX p_n ON is_about (project_id);


DELIMITER $

CREATE TRIGGER insert_sup AFTER INSERT ON project
	FOR EACH ROW
    BEGIN
		INSERT INTO participates_in (researcher_id, project_id) VALUES (NEW.supervisor_id, NEW.project_id);
END$

CREATE TRIGGER insert_at_once AFTER INSERT ON organisation
	FOR EACH ROW 
	BEGIN
		IF (NEW.organisation_type = "University") THEN
			INSERT INTO university (organisation_id,organisation_type,budget) 
            VALUES (NEW.organisation_id,NEW.organisation_type,NEW.budget1);
		ELSEIF (NEW.organisation_type = "Firm") THEN
			INSERT INTO firm (organisation_id,organisation_type,equity) 
            VALUES (NEW.organisation_id,NEW.organisation_type,NEW.budget1);
		ELSEIF (NEW.organisation_type = "Research Center") THEN
			INSERT INTO research_center (organisation_id,organisation_type,ministry_budget,actions_budget) 
            VALUES (NEW.organisation_id,NEW.organisation_type,NEW.budget1,NEW.budget2);
		END IF;
END$

CREATE TRIGGER update_at_once AFTER UPDATE ON organisation
	FOR EACH ROW 
	BEGIN
		IF (NEW.organisation_type = "University") THEN
			UPDATE university 
            SET organisation_id = NEW.organisation_id, organisation_type = NEW.organisation_type, budget = NEW.budget1 
            WHERE organisation_id = NEW.organisation_id;
		ELSEIF (NEW.organisation_type = "Firm") THEN
			UPDATE firm 
            SET organisation_id = NEW.organisation_id, organisation_type = NEW.organisation_type, equity = NEW.budget1 
            WHERE organisation_id = NEW.organisation_id;		
		ELSEIF (NEW.organisation_type = "Research Center") THEN
			UPDATE research_center 
            SET organisation_id = NEW.organisation_id, organisation_type = NEW.organisation_type, 
            ministry_budget = NEW.budget1, actions_budget = NEW.budget2 
            WHERE organisation_id = NEW.organisation_id;		
		END IF;
END$

CREATE TRIGGER duration_insert BEFORE INSERT ON project
	FOR EACH ROW 
    BEGIN
		SET NEW.duration = DATEDIFF(NEW.end_date, NEW.start_date)/365.25;
END$

CREATE TRIGGER duration_update BEFORE UPDATE ON project
	FOR EACH ROW
    BEGIN
		SET NEW.duration = DATEDIFF(NEW.end_date, NEW.start_date)/365.25;
END$

CREATE TRIGGER ev_works_on_proj_insert BEFORE INSERT ON participates_in
	FOR EACH ROW
	BEGIN
    IF ((SELECT evaluator_id FROM project WHERE project_id = NEW.project_id) = NEW.researcher_id) THEN 
    SIGNAL SQLSTATE '45000'
           SET MESSAGE_TEXT = 'Error: A researcher can not work on a project he evaluated';
    END IF;
END$

CREATE TRIGGER ev_works_on_proj_update1 BEFORE UPDATE ON participates_in
	FOR EACH ROW
	BEGIN
    IF ((SELECT evaluator_id FROM project WHERE project_id = NEW.project_id) = NEW.researcher_id) THEN 
    SIGNAL SQLSTATE '45000'
           SET MESSAGE_TEXT = 'Error: A researcher can not work on a project he evaluated';
    END IF;
END$

CREATE TRIGGER ev_same_org_insert BEFORE INSERT ON project
	FOR EACH ROW
	BEGIN
    IF ((SELECT organisation_id FROM researcher r WHERE r.researcher_id = NEW.evaluator_id) = NEW.organisation_id) THEN 
    SIGNAL SQLSTATE '45000'
           SET MESSAGE_TEXT = 'Error: A researcher can not evaluate a project of the organisation he works for';
    END IF;
END$

CREATE TRIGGER ev_same_org_update BEFORE UPDATE ON project
	FOR EACH ROW
	BEGIN
    IF ((SELECT organisation_id FROM researcher r WHERE r.researcher_id = NEW.evaluator_id) = NEW.organisation_id) THEN 
    SIGNAL SQLSTATE '45000'
           SET MESSAGE_TEXT = 'Error: A researcher can not evaluate a project of the organisation he works for';
    END IF;
END$

CREATE TRIGGER sup_work_on_proj_update AFTER UPDATE ON project
	FOR EACH ROW
	BEGIN
    IF NOT EXISTS(SELECT researcher_id FROM participates_in 
		 WHERE project_id = NEW.project_id AND researcher_id = NEW.supervisor_id) THEN 
		INSERT INTO participates_in (researcher_id, project_id) VALUES (NEW.supervisor_id, NEW.project_id);
    END IF;
END$

CREATE TRIGGER res_part_in_insert BEFORE INSERT ON participates_in
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_id FROM researcher r WHERE r.researcher_id = NEW.researcher_id) 
	   <> (SELECT organisation_id FROM project p WHERE p.project_id = NEW.project_id) THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: A researcher can not work on a project of an organisation he does not work for';
	END IF;
END$

CREATE TRIGGER res_part_in_update BEFORE UPDATE ON participates_in
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_id FROM researcher r WHERE r.researcher_id = NEW.researcher_id) 
       <> (SELECT organisation_id FROM project p WHERE p.project_id = NEW.project_id) THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: A researcher can not work on a project of an organisation he does not work for';
	END IF;
END$

CREATE TRIGGER res_type_insert BEFORE INSERT ON research_center
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
	   <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a Research Center';
	END IF;
END$

CREATE TRIGGER res_type_update BEFORE UPDATE ON research_center
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
	   <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a Research Center';
	END IF;
END$

CREATE TRIGGER uni_type_insert BEFORE INSERT ON university
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
       <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a University';
	END IF;
END$

CREATE TRIGGER uni_type_update BEFORE UPDATE ON university
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
	   <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a University';
	END IF;
END$

CREATE TRIGGER firm_type_insert BEFORE INSERT ON firm
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
       <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a Firm';
	END IF;
END$

CREATE TRIGGER firm_type_update BEFORE UPDATE ON firm
	FOR EACH ROW 
    BEGIN
    IF (SELECT organisation_type FROM organisation o WHERE o.organisation_id = NEW.organisation_id) 
	   <> NEW.organisation_type THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Error: This organisation is not a Firm';
	END IF;
END$

CREATE TRIGGER deliverable_date_in BEFORE INSERT ON deliverable
	FOR EACH ROW 
    BEGIN
    IF ((SELECT start_date FROM project p WHERE p.project_id = NEW.project_id) > NEW.delivery_date) 
	   OR ((SELECT end_date FROM project p WHERE p.project_id=NEW.project_id) < NEW.delivery_date) THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Error: A project's deliverable should have a delivery date before the beginning and after the end of the project";
	END IF;
END$

CREATE TRIGGER deliverable_date_up BEFORE UPDATE ON deliverable
	FOR EACH ROW 
    BEGIN
    IF ((SELECT start_date FROM project p WHERE p.project_id = NEW.project_id) > NEW.delivery_date) 
       OR ((SELECT end_date FROM project p WHERE p.project_id=NEW.project_id) < NEW.delivery_date) THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Error: A project's deliverable should have a delivery date before the beginning and after the end of the project";
	END IF;
END$

CREATE TRIGGER birth_in BEFORE INSERT ON researcher 
	FOR EACH ROW
    BEGIN 
    IF (DATEDIFF(CURDATE(), NEW.date_of_birth) < 18*365.25) THEN 
    SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Error: Can not have a researcher that is younger than 18 years old";
	END IF;
END$

CREATE TRIGGER birth_up BEFORE UPDATE ON researcher 
	FOR EACH ROW
    BEGIN 
    IF (DATEDIFF(CURDATE(), NEW.date_of_birth) < 18*365.25) THEN 
    SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Error: Can not have a researcher that is younger than 18 years old";
	END IF;
END$

CREATE TRIGGER new_org BEFORE UPDATE ON researcher
FOR EACH ROW 
BEGIN 
IF (OLD.organisation_id <> NEW.organisation_id 
	AND EXISTS(SELECT i.researcher_id FROM participates_in i INNER JOIN project p
				ON i.researcher_id = NEW.researcher_id 
					AND i.project_id = p.project_id 
                    AND p.end_date > CURDATE())) THEN
	SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Error: Can not change a researcher's organisation 
							if he still works on his former organisation's projects";
	END IF;
END$
    
DELIMITER ;