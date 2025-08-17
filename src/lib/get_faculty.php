<?php
// src/lib/get_faculty.php

/**
 * Returns all non‐archived faculty with their department names.
 * @param PDO $pdo
 * @return array
 */
function getFacultyList(PDO $pdo): array
{
    $sql = "
      SELECT 
        f.id,
        f.faculty_code,
        f.f_name,
        f.m_name,
        f.l_name,
        f.suffix,
        d.name as department_name,
        f.role,
        f.status
      FROM faculty_tbl AS f
      JOIN departments_tbl AS d
        ON f.department_id = d.id
      WHERE f.status != 'Archived'
      ORDER BY f.id DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Returns a list of all departments for the <select>.
 * @param PDO $pdo
 * @return array
 */
function getDepartments(PDO $pdo): array
{
    $sql = "SELECT id, name FROM departments_tbl ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
