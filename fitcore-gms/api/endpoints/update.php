<?php
// api/endpoints/update.php — PUT /api/members/{id}

if (!$id) {
    sendResponse(false, "Member ID is required. Use /api/members/{id}", null, 400);
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data)) {
    sendResponse(false, "No data provided for update.", null, 400);
}

$conn = getConnection();

// Check if member exists
$check = $conn->prepare("SELECT id FROM members WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $check->close();
    $conn->close();
    sendResponse(false, "Member not found.", null, 404);
}
$check->close();

// Build dynamic SET clause from provided fields only
$allowed = ['full_name', 'email', 'phone', 'plan', 'status', 'start_date', 'end_date'];
$setClauses = [];
$params     = [];
$types      = "";

foreach ($allowed as $field) {
    if (array_key_exists($field, $data)) {
        $value = $data[$field];

        if ($field === 'email') {
            $value = filter_var(trim($value), FILTER_SANITIZE_EMAIL);
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $conn->close();
                sendResponse(false, "Invalid email address.", null, 400);
            }
            // Check email uniqueness excluding current member
            $emailCheck = $conn->prepare("SELECT id FROM members WHERE email = ? AND id != ?");
            $emailCheck->bind_param("si", $value, $id);
            $emailCheck->execute();
            $emailCheck->store_result();
            if ($emailCheck->num_rows > 0) {
                $emailCheck->close();
                $conn->close();
                sendResponse(false, "Email is already used by another member.", null, 409);
            }
            $emailCheck->close();
        }

        if (in_array($field, ['plan']) && !in_array($value, ['Basic', 'Standard', 'Premium'])) {
            $conn->close();
            sendResponse(false, "Invalid plan. Choose Basic, Standard, or Premium.", null, 400);
        }

        if ($field === 'status' && !in_array($value, ['Active', 'Inactive', 'Suspended'])) {
            $conn->close();
            sendResponse(false, "Invalid status. Choose Active, Inactive, or Suspended.", null, 400);
        }

        $setClauses[] = "$field = ?";
        $params[]     = htmlspecialchars(strip_tags(trim($value)));
        $types       .= "s";
    }
}

if (empty($setClauses)) {
    $conn->close();
    sendResponse(false, "No valid fields provided for update.", null, 400);
}

$params[] = $id;
$types   .= "i";

$sql  = "UPDATE members SET " . implode(", ", $setClauses) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendResponse(true, "Member updated successfully.");
} else {
    $stmt->close();
    $conn->close();
    sendResponse(false, "Failed to update member.", null, 500);
}
