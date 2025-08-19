<?php
// src/lib/get_staff.php

// declare(strict_types=1);

/**
 * Fetches all non‐archived staff records along with their department names.
 *
 * @param PDO $pdo
 * @return array<int, array<string,mixed>>
 */
function getStaffList(PDO $pdo): array
{
    $sql = "
      SELECT
        s.id,
        s.staff_code,
        s.f_name,
        s.m_name,
        s.suffix,
        s.l_name,
        s.email,
        d.name AS department_name,
        s.status
      FROM staff_tbl AS s
      JOIN departments_tbl AS d
        ON s.department_id = d.id
      WHERE s.status != 'Archived'
      ORDER BY s.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
