<?php

$conn = mysqli_connect('localhost', 'root', '', 'medicine_shop');

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

// ================================================================
// One-time auto-seed of default admin
// Email: admin@gmail.com
// Password: admin123
// ================================================================

$check = mysqli_query(
    $conn,
    "SELECT id FROM users WHERE role='admin' LIMIT 1"
);

if ($check && mysqli_num_rows($check) === 0) {

    $hash = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users
        (name, email, password_hash, role, address, phone)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    $name = 'Admin';
    $email = 'admin@gmail.com';
    $role = 'admin';
    $address = 'Dhaka';
    $phone = '01700000000';

    mysqli_stmt_bind_param(
        $stmt,
        'ssssss',
        $name,
        $email,
        $hash,
        $role,
        $address,
        $phone
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>