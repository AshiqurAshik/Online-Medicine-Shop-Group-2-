<?php
// ================================================================
// CONTROLLERS - Online Medicine Shop (FULL FIXED VERSION)
// ================================================================


/* ================= LOGIN ================= */

function loginCtrl($conn) {

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {

            $error = 'Please fill all fields.';

        } else {

            // 🔥 HARD-CODED ADMIN LOGIN
            if ($email === 'admin@gmail.com' && $password === 'admin123') {

                $_SESSION['user'] = [
                    'id'    => 1,
                    'name'  => 'Admin',
                    'email' => 'admin@gmail.com',
                    'role'  => 'admin'
                ];

                header('Location: index.php?page=admin');
                exit;
            }

            // 👇 DATABASE LOGIN
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id, name, email, password_hash, role 
                 FROM users 
                 WHERE email = ? 
                 LIMIT 1"
            );

            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if ($user && password_verify($password, $user['password_hash'])) {

                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                header('Location: index.php?page=' .
                    ($user['role'] === 'admin' ? 'admin' : 'customer')
                );
                exit;

            } else {
                $error = 'Invalid email or password.';
            }
        }
    }

    require 'views/login.php';
}


/* ================= REGISTER ================= */

function registerCtrl($conn) {

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'customer';

        if ($name === '' || $email === '' || $password === '' || $address === '' || $phone === '') {

            $error = 'All fields are required.';

        } elseif (strlen($password) < 8) {

            $error = 'Password must be at least 8 characters.';

        } else {

            // CHECK EMAIL EXISTS
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $exists = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if ($exists) {

                $error = 'Email already exists.';

            } else {

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO users
                    (name, email, password_hash, role, address, phone)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssss",
                    $name,
                    $email,
                    $hash,
                    $role,
                    $address,
                    $phone
                );

                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Registration successful.';
                } else {
                    $error = 'Registration failed.';
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    require 'views/register.php';
}


/* ================= ADMIN ================= */

function adminCtrl($conn) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    // LOAD DATA
    $categories = getAllCategories($conn);
    $medicines  = getAllMedicines($conn);
    $orders     = getAllOrders($conn);

    require 'views/admin.php';
}



/* ================= LOGOUT ================= */

function logoutCtrl() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    header('Location: index.php?page=login');
    exit;
}


/* ================= GET CATEGORIES ================= */

function getAllCategories($conn) {

    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}


/* ================= update MEDICINES ================= */

function updateMedicineCtrl($conn) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $vendor = trim($_POST['vendor_name']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['availability']);
        $description = trim($_POST['description']);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE medicines 
             SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=? 
             WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sisdiss",
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

        header("Location: index.php?page=admin");
        exit;
    }
}

/* ================= Edit MEDICINES ================= */

function editMedicineCtrl($conn) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?page=login");
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM medicines WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $editing = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    // load all data for admin page
    $categories = getAllCategories($conn);
    $medicines  = getAllMedicines($conn);
    $orders     = getAllOrders($conn);

    require "views/admin.php";
}

/* ================= DELETE MEDICINES ================= */

function deleteMedicineCtrl($conn) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?page=login");
        exit;
    }

    $id = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM medicines WHERE id = $id");

    header("Location: index.php?page=admin");
    exit;
}

/* ================= ADD MEDICINES ================= */

function addMedicineCtrl($conn) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?page=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $vendor_name = trim($_POST['vendor_name']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['availability']);
        $description = trim($_POST['description']);

        // ================= IMAGE UPLOAD =================
        $imagePath = "";

        if (!empty($_FILES['image']['name'])) {

            $fileName = time() . "_" . basename($_FILES['image']['name']);
            $target = "public/uploads/" . $fileName;

            move_uploaded_file($_FILES['image']['tmp_name'], $target);

            $imagePath = $target;
        }

        // ================= INSERT QUERY =================
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO medicines
            (name, category_id, vendor_name, price, availability, description, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sisdiss",
            $name,
            $category_id,
            $vendor_name,
            $price,
            $stock,
            $description,
            $imagePath
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?page=admin");
            exit;
        } else {
            echo "Failed to add medicine";
        }
    }
}

/* ================= GET MEDICINES ================= */

function getAllMedicines($conn) {

    $sql = "SELECT m.*, c.name AS category_name
            FROM medicines m
            LEFT JOIN categories c ON m.category_id = c.id";

    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}


/* ================= GET ORDERS ================= */

function getAllOrders($conn) {

    $sql = "SELECT o.*, u.name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";

    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

//  =================  ================= 
//                 CUSTOMER
//  =================  ================= 

function customerCtrl($conn) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }

    // LOAD MEDICINES
    $medicines = getAllMedicines($conn);

    // LOAD ORDERS (optional)
    $orders = getAllOrders($conn);

    require 'views/customer.php';
}

function addToCartCtrl($conn) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }
    $userId = $_SESSION['user']['id'];
    $medicineId = intval($_POST['medicine_id'] ?? $_GET['id'] ?? 0);
    if ($medicineId > 0) {
        addToCart($conn, $userId, $medicineId);
    }
    header('Location: index.php?page=customer');
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
        $qty = intval($_GET['qty']);
        
        $stmt = mysqli_prepare($conn, "SELECT cart.quantity, medicines.availability FROM cart JOIN medicines ON cart.medicine_id = medicines.id WHERE cart.id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $cartId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $cartItem = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        
        if ($cartItem) {
            if ($qty > $cartItem['availability']) {
                $qty = $cartItem['availability'];
            }
            if ($qty > 0) {
                updateCartQty($conn, $cartId, $qty);
            } else {
                removeCartItem($conn, $cartId);
            }
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
        $paymentMethod = trim($_POST['payment_method'] ?? '');
        
        if ($address === '' || $paymentMethod === '') {
            $error = 'Please fill all fields.';
        } else {
            $orderId = createOrder($conn, $userId, $total, $address, $paymentMethod);
            if ($orderId) {
                foreach ($cartItems as $item) {
                    addOrderItem($conn, $orderId, $item['medicine_id'], $item['quantity'], $item['price']);
                }
                clearCart($conn, $userId);
                header('Location: index.php?page=invoice&id=' . $orderId);
                exit;
            } else {
                $error = 'Failed to create order.';
            }
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