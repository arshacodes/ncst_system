<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/db/db_conn.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Expected fields
$fields = [
    'email',
    'firstName','midName','lastName','suffix',
    'address','birthPlace','phone','birthDate','gender','nationality','course', 'nstp',
    'primarySchool','primaryYear',
    'secondarySchool','secondaryYear',
    'tertiarySchool','tertiaryYear','courseGraduated',
    'guardianFirstName','guardianMidName','guardianLastName','guardianSuffix',
    'guardianAddress','guardianRelationship','guardianPhone'
];

// 1) Collect + trim + normalize empty → null
$data = [];
foreach ($fields as $f) {
    $val = isset($_POST[$f]) ? trim((string)$_POST[$f]) : null;
    $data[$f] = ($val === '' ? null : $val);
}

error_log('Registration POST payload: ' . print_r($_POST, true));
error_log('Normalized data array: ' . print_r($data, true));

$required = [
    'email',
    'firstName','lastName',
    'address','birthPlace','phone','birthDate','gender','nationality','course', 'nstp',
    'primarySchool','primaryYear',
    'secondarySchool','secondaryYear',
    'guardianFirstName','guardianLastName',
    'guardianAddress','guardianRelationship','guardianPhone'
  ];
$missing  = [];
foreach ($required as $r) {
    if (empty($data[$r])) {
        $missing[] = $r;
    }
}
if (!empty($missing)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields: ' . implode(', ', $missing)
    ]);
    exit;
}

$check = $pdo->prepare('SELECT COUNT(*) FROM applicants_tbl WHERE email = :email');
$check->execute([':email' => $data['email']]);
if ((int)$check->fetchColumn() > 0) {
    // email already registered → short-circuit
    echo json_encode([
        'success' => false,
        'message' => 'That email address is already registered.'
    ]);
    exit;
}


try {
    $pdo->beginTransaction();

    // 1) Insert into applicants_tbl
    $stmt = $pdo->prepare(<<<'SQL'
        INSERT INTO applicants_tbl (
            email,
            f_name,
            m_name,
            l_name,
            suffix,
            course_id,
            year_level,
            phone,
            nstp,
            applicant_type,
            user_type,
            status
        ) VALUES (
            :email,
            :firstName,
            :midName,
            :lastName,
            :suffix,
            :course,
            1,
            :phone,
            :nstp,
            'New',
            'Applicant',
            'Pending'
        )
    SQL);
    $stmt->execute([
        ':email'     => $data['email'],
        ':firstName' => $data['firstName'],
        ':midName'   => $data['midName'],
        ':lastName'  => $data['lastName'],
        ':suffix'    => $data['suffix'],
        ':course'    => $data['course'],
        ':phone'     => $data['phone'],
        ':nstp'      => $data['nstp'],
    ]);
    $userId = (int)$pdo->lastInsertId();

    // 2) Personal details
    $stmt = $pdo->prepare(<<<'SQL'
        INSERT INTO personal_details_tbl (
            user_id,
            user_type,
            address,
            nationality,
            birth_date,
            birth_place,
            gender
        ) VALUES (
            :user_id,
            'Applicant',
            :address,
            :nationality,
            :birthDate,
            :birthPlace,
            :gender
        )
    SQL);
    $stmt->execute([
        ':user_id'     => $userId,
        ':address'     => $data['address'],
        ':nationality' => $data['nationality'],
        ':birthDate'   => $data['birthDate'],
        ':birthPlace'  => $data['birthPlace'],
        ':gender'      => $data['gender'],
    ]);

    // 3) Education details
    $stmt = $pdo->prepare(<<<'SQL'
        INSERT INTO educ_details_tbl (
            user_id,
            user_type,
            primary_school,
            primary_year,
            secondary_school,
            secondary_year,
            tertiary_school,
            tertiary_year,
            course_graduated
        ) VALUES (
            :user_id,
            'Applicant',
            :primarySchool,
            :primaryYear,
            :secondarySchool,
            :secondaryYear,
            :tertiarySchool,
            :tertiaryYear,
            :courseGraduated
        )
    SQL);
    $stmt->execute([
        ':user_id'          => $userId,
        ':primarySchool'    => $data['primarySchool'],
        ':primaryYear'      => $data['primaryYear'],
        ':secondarySchool'  => $data['secondarySchool'],
        ':secondaryYear'    => $data['secondaryYear'],
        ':tertiarySchool'   => $data['tertiarySchool'],
        ':tertiaryYear'     => $data['tertiaryYear'],
        ':courseGraduated'  => $data['courseGraduated'],
    ]);

    // 4) Family details
    $stmt = $pdo->prepare(<<<'SQL'
        INSERT INTO family_details_tbl (
            user_id,
            user_type,
            guardian_f_name,
            guardian_m_name,
            guardian_l_name,
            guardian_suffix,
            guardian_address,
            guardian_phone,
            guardian_relationship
        ) VALUES (
            :user_id,
            'Applicant',
            :guardianFirstName,
            :guardianMidName,
            :guardianLastName,
            :guardianSuffix,
            :guardianAddress,
            :guardianPhone,
            :guardianRelationship
        )
    SQL);
    $stmt->execute([
        ':user_id'              => $userId,
        ':guardianFirstName'    => $data['guardianFirstName'],
        ':guardianMidName'      => $data['guardianMidName'],
        ':guardianLastName'     => $data['guardianLastName'],
        ':guardianSuffix'       => $data['guardianSuffix'],
        ':guardianAddress'      => $data['guardianAddress'],
        ':guardianPhone'        => $data['guardianPhone'],
        ':guardianRelationship' => $data['guardianRelationship'],
    ]);

    //records
    // $stmt = $pdo->prepare(<<<'SQL'
    //     INSERT INTO records_tbl (
    //         student_id,
    //         form137,
    //         good_moral,
    //         birth_cert,
    //     ) VALUES (
    //         false,
    //         false,
    //         false,
    //         false
    //     )
    // SQL);
    // $stmt->execute([
    //     ':user_id'              => $userId,
    //     ':guardianFirstName'    => $data['guardianFirstName'],
    //     ':guardianMidName'      => $data['guardianMidName'],
    //     ':guardianLastName'     => $data['guardianLastName'],
    //     ':guardianSuffix'       => $data['guardianSuffix'],
    //     ':guardianAddress'      => $data['guardianAddress'],
    //     ':guardianPhone'        => $data['guardianPhone'],
    //     ':guardianRelationship' => $data['guardianRelationship'],
    // ]);

    $pdo->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Student registered successfully.'
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('[Registration Error] ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

header('Location: ../../admissions_login.php');
exit;
?>