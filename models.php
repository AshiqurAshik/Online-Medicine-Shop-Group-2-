<?php
// ================================================================
// MODELS - Online Medicine Shop
// All DB access using procedural mysqli + prepared statements
// ================================================================


/* ================= USER AUTH ================= */

function authUser($conn, $email, $password) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE email = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return ($row && password_verify($password, $row['password_hash']))
        ? $row
        : false;
}


function emailExists($conn, $email, $excludeId = null) {

    if ($excludeId) {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? AND id != ?"
        );

        mysqli_stmt_bind_param($stmt, 'si', $email, $excludeId);

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param($stmt, 's', $email);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $exists = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);

    return $exists;
}


function addUser($conn, $name, $email, $password, $role, $address, $phone) {

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users
        (name, email, password_hash, role, address, phone)
        VALUES (?, ?, ?, ?, ?, ?)"
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

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}



/* ================= MEDICINES ================= */

function getMedicines($conn) {

    $query = "
        SELECT medicines.*, categories.name AS category_name
        FROM medicines
        LEFT JOIN categories
        ON medicines.category_id = categories.id
        ORDER BY medicines.id DESC
    ";

    $result = mysqli_query($conn, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getMedicine($conn, $id) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM medicines WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );

    mysqli_stmt_close($stmt);

    return $row;
}


function addMedicine(
    $conn,
    $name,
    $categoryId,
    $vendor,
    $price,
    $stock,
    $description,
    $image
) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO medicines
        (name, category_id, vendor_name, price,
        availability, description, image_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sisdiis',
        $name,
        $categoryId,
        $vendor,
        $price,
        $stock,
        $description,
        $image
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function updateMedicine(
    $conn,
    $id,
    $name,
    $categoryId,
    $vendor,
    $price,
    $stock,
    $description
) {

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE medicines
        SET name=?,
            category_id=?,
            vendor_name=?,
            price=?,
            availability=?,
            description=?
        WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sisdisi',
        $name,
        $categoryId,
        $vendor,
        $price,
        $stock,
        $description,
        $id
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function deleteMedicine($conn, $id) {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM medicines WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}



/* ================= CATEGORIES ================= */

function getCategories($conn) {

    $result = mysqli_query(
        $conn,
        "SELECT * FROM categories ORDER BY id DESC"
    );

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function addCategory($conn, $name, $type) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO categories(name, category_type)
        VALUES(?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'ss',
        $name,
        $type
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function deleteCategory($conn, $id) {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM categories WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}



/* ================= ORDERS ================= */

function getOrders($conn) {

    $query = "
        SELECT orders.*, users.name
        FROM orders
        JOIN users
        ON orders.user_id = users.id
        ORDER BY orders.id DESC
    ";

    $result = mysqli_query($conn, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function updateOrderStatus($conn, $id, $status) {

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE orders SET status=? WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'si',
        $status,
        $id
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


/* ================= CART ================= */

function addToCart($conn, $userId, $medicineId) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT availability FROM medicines WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, 'i', $medicineId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $med = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$med || $med['availability'] <= 0) {
        return false;
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, quantity FROM cart WHERE user_id = ? AND medicine_id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'ii', $userId, $medicineId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($row) {

        $newQty = $row['quantity'] + 1;
        if ($newQty > $med['availability']) {
            return false;
        }

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE cart SET quantity = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, 'ii', $newQty, $row['id']);

        $ok = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return $ok;
    }

    $qty = 1;

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO cart (user_id, medicine_id, quantity) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, 'iii', $userId, $medicineId, $qty);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function getCartItems($conn, $userId) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT cart.*, medicines.name, medicines.price, medicines.image_path, medicines.availability
         FROM cart
         JOIN medicines ON cart.medicine_id = medicines.id
         WHERE cart.user_id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $data;
}


function updateCartQty($conn, $cartId, $quantity) {

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE cart SET quantity = ? WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'ii', $quantity, $cartId);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function removeCartItem($conn, $cartId) {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM cart WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $cartId);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function clearCart($conn, $userId) {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM cart WHERE user_id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $userId);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}



/* ================= ORDER CREATION ================= */

function createOrder($conn, $userId, $total, $address, $paymentMethod) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO orders
        (user_id, total_amount, shipping_address, payment_method)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'idss',
        $userId,
        $total,
        $address,
        $paymentMethod
    );

    $ok = mysqli_stmt_execute($stmt);

    $orderId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    return $ok ? $orderId : false;
}


function addOrderItem($conn, $orderId, $medicineId, $qty, $price) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO order_items
        (order_id, medicine_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'iiid',
        $orderId,
        $medicineId,
        $qty,
        $price
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}


function getOrderById($conn, $orderId) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT orders.*, users.name, users.email
         FROM orders
         JOIN users ON orders.user_id = users.id
         WHERE orders.id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row;
}


function getOrderItems($conn, $orderId) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT order_items.*, medicines.name
         FROM order_items
         JOIN medicines ON order_items.medicine_id = medicines.id
         WHERE order_items.order_id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $data;
}

function getCustomerOrders($conn, $userId) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC"
    );

    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $data;
}
?>