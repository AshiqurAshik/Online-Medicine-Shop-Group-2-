<?php


session_start();

require 'config.php';
require 'models.php';
require 'controllers.php';


$page   = $_GET['page']   ?? 'login';
$action = $_GET['action'] ?? '';



if ($page === 'logout') {

    session_unset();
    session_destroy();

    setcookie('remember_user', '', time() - 3600, '/');

    header('Location: index.php?page=login');
    exit;
}



if ($page === 'ajax') {

    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $type = $_GET['type'] ?? '';
    $q    = trim($_GET['q'] ?? '');

    

    if ($type === 'medicine' && $_SESSION['user']['role'] === 'admin') {

        if ($q === '') {
            echo json_encode(getMedicines($conn));
            exit;
        }

        $like = "%$q%";

        $stmt = mysqli_prepare(
            $conn,
            'SELECT *
             FROM medicines
             WHERE name LIKE ?
             OR vendor_name LIKE ?
             ORDER BY id DESC'
        );

        mysqli_stmt_bind_param($stmt, 'ss', $like, $like);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));

        mysqli_stmt_close($stmt);

        exit;
    }


    if ($type === 'order_status' && $_SESSION['user']['role'] === 'admin') {

        $body   = json_decode(file_get_contents('php://input'), true);

        $oid    = intval($body['order_id'] ?? 0);

        $status = $body['status'] ?? '';

        if ($oid > 0 && in_array($status, ['accepted', 'rejected'])) {

            updateOrderStatus($conn, $oid, $status);

            echo json_encode(['ok' => true]);

        } else {

            http_response_code(400);

            echo json_encode(['error' => 'Invalid input']);
        }

        exit;
    }

    http_response_code(403);

    echo json_encode(['error' => 'Forbidden']);

    exit;
}




$publicPages = ['login', 'register'];



if (in_array($page, $publicPages) && isset($_SESSION['user'])) {

    if ($_SESSION['user']['role'] === 'admin') {

        header('Location: index.php?page=admin');

    } else {

        header('Location: index.php?page=customer');
    }

    exit;
}



if (!in_array($page, $publicPages) && !isset($_SESSION['user'])) {

    header('Location: index.php?page=login');

    exit;
}


if ($page === 'admin' && $_SESSION['user']['role'] !== 'admin') {

    header('Location: index.php?page=login');

    exit;
}

if (
    in_array($page, ['customer', 'cart', 'checkout', 'invoice']) &&
    $_SESSION['user']['role'] !== 'customer'
) {

    header('Location: index.php?page=login');

    exit;
}



switch ($page) {


    case 'login':

        loginCtrl($conn);

        break;




    case 'register':

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            require 'views/register.php';

            break;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            registerCtrl($conn);

            exit;
        }

        break;



    case 'profile':

        profileCtrl($conn);

        break;


   

    case 'admin':

        if ($action === 'addMedicine') {

            addMedicineCtrl($conn);

            break;
        }

        if ($action === 'deleteMedicine') {

            deleteMedicineCtrl($conn);

            break;
        }

        if ($action === 'editMedicine') {

            editMedicineCtrl($conn);

            break;
        }

        if ($action === 'updateMedicine') {

            updateMedicineCtrl($conn);

            break;
        }


        adminCtrl($conn);

        break;

    case 'manageCustomer':
        manageCustomerCtrl($conn);
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




    case 'categories':

        $catAction = $_GET['action'] ?? '';

        if ($catAction === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $catName = trim($_POST['name'] ?? '');

            $catType = in_array(
                $_POST['type'] ?? '',
                ['solid', 'liquid']
            )
            ? $_POST['type']
            : 'solid';

            if ($catName !== '') {

                addCategory($conn, $catName, $catType);
            }

            header('Location: index.php?page=categories&msg=added');

            exit;
        }

  
        if ($catAction === 'edit') {

            $editing = null;

            $catId = intval($_GET['id'] ?? 0);

            $result = mysqli_query(
                $conn,
                "SELECT * FROM categories WHERE id = $catId LIMIT 1"
            );

            $editing = mysqli_fetch_assoc($result);

            $categories = getAllCategories($conn);

            require 'views/categories.php';

            break;
        }

    
        if ($catAction === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $catId   = intval($_POST['id'] ?? 0);

            $catName = trim($_POST['name'] ?? '');

            $catType = in_array(
                $_POST['type'] ?? '',
                ['solid', 'liquid']
            )
            ? $_POST['type']
            : 'solid';

            if ($catId > 0 && $catName !== '') {

                $stmt = mysqli_prepare(
                    $conn,
                    'UPDATE categories
                     SET name=?, category_type=?
                     WHERE id=?'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'ssi',
                    $catName,
                    $catType,
                    $catId
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
            }

            header('Location: index.php?page=categories&msg=updated');

            exit;
        }

      
        if ($catAction === 'delete') {

            $catId = intval($_GET['id'] ?? 0);

            if ($catId > 0) {

                deleteCategory($conn, $catId);
            }

            header('Location: index.php?page=categories&msg=deleted');

            exit;
        }

   
        $categories = getAllCategories($conn);

        require 'views/categories.php';

        break;



    case 'orders':

        $orders = getAllOrders($conn);

        require 'views/orders.php';

        break;


   

    case 'history':

        $orders = getAllOrders($conn);

        require 'views/history.php';

        break;



    default:

        header('Location: index.php?page=login');

        exit;
}



mysqli_close($conn);
?>