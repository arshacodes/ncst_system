<?php
// src/lib/get_courses.php

declare(strict_types=1);

/**
 * Returns all courses (status != 'Archived').
 *
 * @param PDO $pdo
 * @return array<int,array<string,mixed>>
 */
function get_courses(PDO $pdo): array
{
    $sql = "
      SELECT 
        c.id,
        c.code,
        c.name,
        c.status,
        d.name AS department_name
      FROM courses_tbl AS c
      JOIN departments_tbl AS d ON c.department_id = d.id
      WHERE c.status != 'Archived'
      ORDER BY c.id DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
