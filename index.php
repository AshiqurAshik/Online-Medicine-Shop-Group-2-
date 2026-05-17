<?php
// ================================================================
// FRONT CONTROLLER (ROUTER)
// Online Medicine Shop
// ================================================================

session_start();

require 'config.php';
require 'models.php';
require 'controllers.php';


$page   = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? '';


/* ================= LOGOUT ================= */

if ($page === 'logout') {

    $_SESSION = [];
    session_destroy();

    header('Location: index.php?page=login');
    exit;
}


/* ================= AJAX SEARCH ================= */

if ($page === 'ajax') {

    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {

        http_response_code(403);

        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $type = $_GET['type'] ?? '';
    $q    = trim($_GET['q'] ?? '');

    /* ===== MEDICINE SEARCH ===== */
    if ($type === 'medicine' && $_SESSION['user']['role'] === 'admin') {

        if ($q === '') {

            echo json_encode(getMedicines($conn));
            exit;
        }

        $like = "%$q%";

        $stmt = mysqli_prepare(
            $conn,
            "SELECT *
             FROM medicines
             WHERE name LIKE ?
             OR vendor_name LIKE ?
             ORDER BY id DESC"
        );

        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));

        mysqli_stmt_close($stmt);
        exit;
    }

    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}


/* ================= PUBLIC PAGES ================= */

$publicPages = ['login', 'register'];


/* ================= AUTO REDIRECT ================= */

if (in_array($page, $publicPages) && isset($_SESSION['user'])) {

    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: index.php?page=admin');
    } else {
        header('Location: index.php?page=customer');
    }

    exit;
}


/* ================= LOGIN REQUIRED ================= */

if (!in_array($page, $publicPages) && !isset($_SESSION['user'])) {

    header('Location: index.php?page=login');
    exit;
}


/* ================= ROLE SECURITY ================= */

if ($page === 'admin' && $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

if ($page === 'customer' && $_SESSION['user']['role'] !== 'customer') {
    header('Location: index.php?page=login');
    exit;
}


/* ================= ROUTES ================= */

switch ($page) {


    case 'login':
        loginCtrl($conn);
        break;


    case 'register':
        registerCtrl($conn);
        break;


    /* ================= ADMIN ROUTING ================= */

case 'admin':

    // ADD MEDICINE
    if ($action === 'addMedicine') {
        addMedicineCtrl($conn);
        break;
    }

    // DELETE MEDICINE
    if ($action === 'deleteMedicine') {
        deleteMedicineCtrl($conn);
        break;
    }

    // EDIT MEDICINE
    if ($action === 'editMedicine') {
        editMedicineCtrl($conn);
        break;
    }

    if ($action === 'updateMedicine') {
    updateMedicineCtrl($conn);
    break;
}

    // DEFAULT ADMIN DASHBOARD
    adminCtrl($conn);
    break;


case 'customer':
    if ($action === 'addToCart') {
        addToCartCtrl($conn);
        break;
    }
    customerCtrl($conn);
    break;

case 'cart':
    cartCtrl($conn);
    break;

case 'checkout':
    checkoutCtrl($conn);
    break;

case 'invoice':
    invoiceCtrl($conn);
    break;

default:
    header('Location: index.php?page=login');
    exit;
}


/* ================= CLOSE DB ================= */

mysqli_close($conn);

?>