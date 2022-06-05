-- QUERIES
-- 3.1
-- To search for the requested projects
 SELECT p.project_id AS id, p.project_title AS title 
 FROM project p 
 INNER JOIN elidek_ex e 
 ON p.elidek_ex_id = e.elidek_ex_id 
 WHERE 1=1;  
/* To meet users criteria the following could be added after "1=1":
" AND start_date > '$s_date'"
" AND end_date < '$e_date'"
" AND duration = '$duration'"
" AND e.first_name = '$exec[0]' AND e.last_name = '$exec[1]'"
*/

-- To find the researchers participating in a project with a certain project_id
SELECT * FROM researcher r 
INNER JOIN participates_in p 
ON p.researcher_id = r.researcher_id 
WHERE p.project_id = ? 
ORDER BY r.last_name;
-- questionmark (?) gets binded to the id of the project that the user requested

-- 3.2
-- To create the 1st view
CREATE VIEW projects_per_researcher AS
	SELECT r.researcher_id, r.first_name, r.last_name, p.project_id, p.project_title 
    FROM researcher r 
    INNER JOIN participates_in i
    ON r.researcher_id = i.researcher_id 
    INNER JOIN project p
    ON p.project_id = i.project_id
    GROUP BY r.researcher_id, p.project_id
    ORDER BY r.researcher_id, p.project_id;

-- To select attributes from the 1st view
SELECT ID, first_name, last_name, project_id, project_title 
FROM projects_per_researcher;

-- To create 2nd the view
CREATE VIEW projects_per_organisation AS 
SELECT o.organisation_id, o.organisation_name, p.project_id, p.project_title 
FROM project p
INNER JOIN organisation o
    ON p.organisation_id = o.organisation_id
    GROUP BY o.organisation_id, p.project_id;

-- To select attributes from the 2nd view
SELECT organisation_id, organisation_name, project_id, project_title 
FROM projects_per_organisation;

-- 3.3
SELECT p.project_title AS title, p.project_id AS p_id
FROM project p
INNER JOIN is_about i
ON p.project_id = i.project_id
WHERE field_name ='$name' AND p.end_date > CURDATE() 
ORDER BY title;
-- '$name' is defined by user input

-- 3.4
WITH proj_per_year AS (SELECT o.organisation_id AS ID, YEAR(p.start_date) AS xronos, COUNT(*) AS N 
FROM organisation o, project p
WHERE o.organisation_id = p.organisation_id 
GROUP BY o.organisation_name, YEAR(p.start_date))
SELECT o.organisation_id AS o_id, o.organisation_name AS o_name
FROM organisation o, proj_per_year y1, proj_per_year y2
WHERE (o.organisation_id = y1.ID 
AND o.organisation_id = y2.ID
        AND y1.xronos = y2.xronos-1
        AND y1.N = y2.N
        AND y1.N >= 10);

-- 3.5
SELECT x.field_name as first_field, i.field_name as second_field, COUNT(*) as quantity
        FROM is_about x 
        INNER JOIN is_about i 
        ON i.project_id = x.project_id 
        WHERE i.field_name > x.field_name  
        GROUP BY x.field_name, i.field_name 
        ORDER BY COUNT(*) DESC LIMIT 3;

-- 3.6
WITH max_proj(N, ID) AS (select count(*) AS N, i.researcher_id AS ID
FROM participates_in i, project p
WHERE i.project_id = p.project_id
AND p.end_date >= CURDATE()
GROUP BY i.researcher_id)
SELECT r.first_name as f_name, r.last_name as l_name, (SELECT max(N) from max_proj) AS number_of_projects
FROM researcher r
WHERE (DATEDIFF(CURDATE(), r.date_of_birth) < 40*365.25)
AND (SELECT m.N FROM max_proj m WHERE m.ID = r.researcher_id) = (SELECT max(N) FROM max_proj);

-- 3.7
SELECT org.organisation_name as o_name, SUM(p.fund) as funding, e.first_name as f_name, e.last_name as l_name, COUNT(p.fund) as amount
FROM project p
INNER JOIN elidek_ex e
ON p.elidek_ex_id = e.elidek_ex_id
INNER JOIN organisation org
ON p.organisation_id = org.organisation_id
WHERE org.organisation_type = 'Firm'
GROUP BY e.elidek_ex_id, org.organisation_id
ORDER BY SUM(p.fund) DESC 
LIMIT 5;

-- 3.8
SELECT r.first_name as f_name, r.last_name as l_name, COUNT(*) as amount
FROM researcher r
INNER JOIN participates_in w
ON r.researcher_id = w.researcher_id
INNER JOIN project p 
ON p.project_id = w.project_id
WHERE NOT EXISTS (SELECT * FROM deliverable d WHERE p.project_id = d.project_id) AND DATEDIFF(CURRENT_DATE(), p.end_date) < 0
GROUP BY r.researcher_id
HAVING COUNT(*) > 4
ORDER BY COUNT(*) DESC