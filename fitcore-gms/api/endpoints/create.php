<?php
// api/endpoints/create.php — POST /api/members

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required = ['full_name', 'email', 'phone', 'plan', 'start_date', 'end_date'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        sendResponse(false, "Field '$field' is required.", null, 400);
    }
}

$full_name  = htmlspecialchars(strip_tags(trim($data['full_name'])));
$email      = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
$phone      = htmlspecialchars(strip_tags(trim($data['phone'])));
$plan       = in_array($data['plan'], ['Basic', 'Standard', 'Premium']) ? $data['plan'] : 'Basic';
$status     = in_array($data['status'] ?? '', ['Active', 'Inactive', 'Suspended']) ? $data['status'] : 'Active';
$start_date = $data['start_date'];
$end_date   = $data['end_date'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "Invalid email address.", null, 400);
}

$conn = getConnection();

// Check for duplicate email
$check = $conn->prepare("SELECT id FROM members WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $conn->close();
    sendResponse(false, "A member with this email already exists.", null, 409);
}
$check->close();

$stmt = $conn->prepare(
    "INSERT INTO members (full_name, email, phone, plan, status, start_date, end_date)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssssss", $full_name, $email, $phone, $plan, $status, $start_date, $end_date);

if ($stmt->execute()) {
    $newId = $conn->insert_id;
    $stmt->close();
    $conn->close();
    sendResponse(true, "Member created successfully.", ["id" => $newId], 201);
} else {
    $stmt->close();
    $conn->close();
    sendResponse(false, "Failed to create member.", null, 500);
}
