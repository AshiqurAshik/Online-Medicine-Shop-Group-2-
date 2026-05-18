<?php

$conn = mysqli_connect('localhost', 'root', '', 'medicine_shop');

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');


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

    $name    = 'Admin';
    $email   = 'admin@gmail.com';
    $role    = 'admin';
    $address = 'Dhaka';
    $phone   = '01700000000';

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


if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user'])) {

    $decoded = json_decode(base64_decode($_COOKIE['remember_user']), true);

    if ($decoded && isset($decoded['id'], $decoded['email'])) {

        $id    = intval($decoded['id']);
        $email = $decoded['email'];

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, email, role FROM users WHERE id = ? AND email = ? LIMIT 1"
        );

        mysqli_stmt_bind_param($stmt, 'is', $id, $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if ($user) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ];
        } else {
            setcookie('remember_user', '', time() - 3600, '/');
        }
    }
}
?>