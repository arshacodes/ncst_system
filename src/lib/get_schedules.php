<?php
/**
 * Fetch schedules for a given department.
 * If $deptId === 1, returns all schedules (superadmin).
 * Otherwise returns only schedules where faculty.department_id = $deptId.
 *
 * @param PDO $pdo
 * @param int $deptId
 * @return array
 */
function fetchSchedules(PDO $pdo, int $userDept = 1): array
{
    $sql = "
        SELECT
            ib.id AS schedule_id,
            CONCAT(f.f_name, ' ', f.l_name) AS instructor,
            d.name AS day,
            CONCAT(
                DATE_FORMAT(t.start_time, '%H:%i'),
                '–',
                DATE_FORMAT(t.end_time,   '%H:%i')
            ) AS time,
            ib.status
        FROM instructors_busy_tbl ib
        JOIN faculty_tbl   f ON f.id = ib.instructor_id
        JOIN days_tbl      d ON d.id = ib.day_id
        JOIN timeslots_tbl t ON t.id = ib.timeslot_id
        WHERE (:deptCheck = 1 OR f.department_id = :deptFilter)
        ORDER BY ib.id ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'deptCheck'  => $userDept,
        'deptFilter' => $userDept,
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
