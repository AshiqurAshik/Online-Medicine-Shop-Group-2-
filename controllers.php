<?php

function loginCtrl($conn)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        require 'views/login.php';
        return;
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $_SESSION['error'] = 'Please fill all fields.';
        header('Location: index.php?page=login');
        exit;
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, password_hash, role 
         FROM users 
         WHERE email = ? 
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: index.php?page=login');
        exit;
    }

    $_SESSION['user'] = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'],
    ];

    if ($remember) {

        $data  = ['id' => $user['id'], 'email' => $user['email']];
        $token = base64_encode(json_encode($data));

        setcookie('remember_user', $token, time() + (86400 * 30), '/');

    } else {
        setcookie('remember_user', '', time() - 3600, '/');
    }

    header('Location: index.php?page=' .
        ($user['role'] === 'admin' ? 'admin' : 'customer')
    );
    exit;
}


function registerCtrl($conn)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=register');
        exit;
    }

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $address  = trim($_POST['address'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');

    $allowedRoles = ['customer', 'admin'];

    $role = in_array($_POST['role'] ?? '', $allowedRoles)
        ? $_POST['role']
        : 'customer';


    if (
        $name === '' ||
        $email === '' ||
        $password === '' ||
        $address === '' ||
        $phone === ''
    ) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: index.php?page=register');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters.';
        header('Location: index.php?page=register');
        exit;
    }


    $stmt = mysqli_prepare(
        $conn,
        'SELECT id FROM users WHERE email = ?'
    );

    mysqli_stmt_bind_param($stmt, 's', $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $exists = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($exists) {
        $_SESSION['error'] = 'Email already exists.';
        header('Location: index.php?page=register');
        exit;
    }


    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO users
        (name, email, password_hash, role, address, phone)
        VALUES (?, ?, ?, ?, ?, ?)'
    );

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

    if (mysqli_stmt_execute($stmt)) {

        $_SESSION['success'] = 'Registration successful!';

        mysqli_stmt_close($stmt);

        header('Location: index.php?page=login');
        exit;

    } else {

        $_SESSION['error'] = 'Registration failed!';

        mysqli_stmt_close($stmt);

        header('Location: index.php?page=register');
        exit;
    }
}



function adminCtrl($conn)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    $categories = getAllCategories($conn);
    $medicines  = getAllMedicines($conn);
    $orders     = getAllOrders($conn);

    require 'views/admin.php';
}


function profileCtrl($conn)
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $id      = $_SESSION['user']['id'];
    $error   = '';
    $success = '';

    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';

        $passwordHash = $user['password_hash'];

        if ($newPassword !== '') {

            if (!password_verify($currentPassword, $user['password_hash'])) {
                $error = 'Current password is wrong!';
            } else {
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        $profilePic = $user['profile_picture'];

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {

            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName   = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['profile_picture']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $profilePic = $fileName;
            } else {
                $error = 'Image upload failed!';
            }
        }

        if ($error === '') {

            $stmt = mysqli_prepare(
                $conn,
                'UPDATE users 
                 SET name=?, email=?, phone=?, address=?, profile_picture=?, password_hash=? 
                 WHERE id=?'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'ssssssi',
                $name,
                $email,
                $phone,
                $address,
                $profilePic,
                $passwordHash,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {

                $_SESSION['user']['name']            = $name;
                $_SESSION['user']['email']           = $email;
                $_SESSION['user']['profile_picture'] = $profilePic;

                $success = 'Profile updated successfully!';
            } else {
                $error = 'Update failed!';
            }

            mysqli_stmt_close($stmt);
        }
    }

    require 'views/profile.php';
}



function logoutCtrl()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    // Clear remember-me cookie
    setcookie('remember_user', '', time() - 3600, '/');

    header('Location: index.php?page=login');
    exit;
}



function getAllCategories($conn)
{
    $sql    = 'SELECT * FROM categories';
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}



function updateMedicineCtrl($conn)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id          = intval($_POST['id']);
        $name        = trim($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $vendor      = trim($_POST['vendor_name']);
        $price       = floatval($_POST['price']);
        $stock       = intval($_POST['availability']);
        $description = trim($_POST['description']);

        $stmt = mysqli_prepare(
            $conn,
            'UPDATE medicines 
             SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=? 
             WHERE id=?'
        );

        
        mysqli_stmt_bind_param(
            $stmt,
            'sisdisi',
            $name,
            $category_id,
            $vendor,
            $price,
            $stock,
            $description,
            $id
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: index.php?page=admin');
        exit;
    }
}


function editMedicineCtrl($conn)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = mysqli_prepare(
        $conn,
        'SELECT * FROM medicines WHERE id = ?'
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result  = mysqli_stmt_get_result($stmt);
    $editing = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    $categories = getAllCategories($conn);
    $medicines  = getAllMedicines($conn);
    $orders     = getAllOrders($conn);

    require 'views/admin.php';
}


function deleteMedicineCtrl($conn)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, 'DELETE FROM medicines WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: index.php?page=admin');
    exit;
}


function addMedicineCtrl($conn)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name        = trim($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $vendor_name = trim($_POST['vendor_name']);
        $price       = floatval($_POST['price']);
        $stock       = intval($_POST['availability']);
        $description = trim($_POST['description']);

        $imagePath = '';

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {

            $uploadDir = 'public/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name']));
            $target   = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = $target;
            }
        }

        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO medicines
            (name, category_id, vendor_name, price, availability, description, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        
        mysqli_stmt_bind_param(
            $stmt,
            'sisdiss',
            $name,
            $category_id,
            $vendor_name,
            $price,
            $stock,
            $description,
            $imagePath
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: index.php?page=admin');
            exit;
        } else {
            mysqli_stmt_close($stmt);
            echo 'Failed to add medicine: ' . mysqli_error($conn);
        }
    }
}



function getAllMedicines($conn)
{
    $sql = 'SELECT m.*, c.name AS category_name
            FROM medicines m
            LEFT JOIN categories c ON m.category_id = c.id';

    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}




function getAllOrders($conn)
{
    $sql = 'SELECT o.*, u.name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC';

    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

function manageCustomerCtrl($conn) {
    require 'views/manageCustomer.php';
}



function customerCtrl($conn) {

    if (
        !isset($_SESSION['user']) ||
        $_SESSION['user']['role'] !== 'customer'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    $categoryId = intval($_GET['category'] ?? 0);
    $type = trim($_GET['type'] ?? '');

    $categories = getAllCategories($conn);

 

    if ($categoryId > 0) {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT medicines.*, categories.name AS category_name, categories.category_type
             FROM medicines
             JOIN categories ON medicines.category_id = categories.id
             WHERE medicines.category_id = ?"
        );

        mysqli_stmt_bind_param($stmt, 'i', $categoryId);

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT medicines.*, categories.name AS category_name, categories.category_type
             FROM medicines
             JOIN categories ON medicines.category_id = categories.id"
        );
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $medicines = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);


    if ($type !== '') {
        $medicines = array_values(array_filter($medicines, function ($m) use ($type) {
            return strtolower($m['category_type']) === strtolower($type);
        }));
    }

    $orders = getAllOrders($conn);

    require 'views/customer.php';
}

function addToCartCtrl($conn) {

    if (
        !isset($_SESSION['user']) ||
        $_SESSION['user']['role'] !== 'customer'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    $userId     = $_SESSION['user']['id'];
    $medicineId = intval($_POST['medicine_id'] ?? 0);
    $quantity   = intval($_POST['quantity'] ?? 1);

    if ($medicineId <= 0 || $quantity <= 0) {

        $_SESSION['flash_message'] = [
            'type' => 'error',
            'text' => 'Invalid product or quantity!'
        ];

        header('Location: index.php?page=customer');
        exit;
    }



    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, availability FROM medicines WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $medicineId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $medicine = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$medicine) {

        $_SESSION['flash_message'] = [
            'type' => 'error',
            'text' => 'Medicine not found!'
        ];

        header('Location: index.php?page=customer');
        exit;
    }


    if ($quantity > $medicine['availability']) {
        $quantity = $medicine['availability'];
    }


    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, quantity FROM cart WHERE user_id = ? AND medicine_id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'ii', $userId, $medicineId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $existing = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

 

    if ($existing) {

        $newQty = $existing['quantity'] + $quantity;

        if ($newQty > $medicine['availability']) {
            $newQty = $medicine['availability'];
        }

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE cart SET quantity = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, 'ii', $newQty, $existing['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO cart (user_id, medicine_id, quantity)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, 'iii', $userId, $medicineId, $quantity);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }



    $_SESSION['flash_message'] = [
        'type' => 'success',
        'text' => 'Item added to cart successfully!'
    ];

    header('Location: index.php?page=cart');
    exit;
}

function cartCtrl($conn) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $action = $_GET['action'] ?? '';

    if ($action === 'update') {

        $cartId = intval($_GET['id']);
        $qty    = intval($_GET['qty']);

        if ($qty > 0) {
            updateCartQty($conn, $cartId, $qty);
        } else {
            removeCartItem($conn, $cartId);
        }

        header('Location: index.php?page=cart');
        exit;
    }

    if ($action === 'remove') {

        $cartId = intval($_GET['id']);
        removeCartItem($conn, $cartId);

        header('Location: index.php?page=cart');
        exit;
    }

    $cartItems = getCartItems($conn, $userId);

    require 'views/cart.php';
}

function checkoutCtrl($conn) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $cartItems = getCartItems($conn, $userId);

    if (empty($cartItems)) {
        header('Location: index.php?page=customer');
        exit;
    }
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $address = trim($_POST['address'] ?? '');
        $payment = trim($_POST['payment_method'] ?? '');

        if ($address === '' || $payment === '') {
            $error = 'Please fill all fields.';
        } else {

            $orderId = createOrder($conn, $userId, $total, $address, $payment);

            if ($orderId) {

                foreach ($cartItems as $item) {
                    addOrderItem($conn, $orderId, $item['medicine_id'], $item['quantity'], $item['price']);
                }

                clearCart($conn, $userId);

                header('Location: index.php?page=invoice&id=' . $orderId);
                exit;
            }

            $error = 'Failed to create order.';
        }
    }

    require 'views/checkout.php';
}

function invoiceCtrl($conn) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }
    $orderId = intval($_GET['id'] ?? 0);
    $order = getOrderById($conn, $orderId);
    
    if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
        header('Location: index.php?page=customer');
        exit;
    }
    
    $orderItems = getOrderItems($conn, $orderId);
    require 'views/invoice.php';
}

?>