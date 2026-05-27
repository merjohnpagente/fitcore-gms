<?php
// api/endpoints/delete.php — DELETE /api/members/{id}

if (!$id) {
    sendResponse(false, "Member ID is required. Use /api/members/{id}", null, 400);
}

$conn = getConnection();

// Check if member exists
$check = $conn->prepare("SELECT id, full_name FROM members WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();
if ($result->num_rows === 0) {
    $check->close();
    $conn->close();
    sendResponse(false, "Member not found.", null, 404);
}
$member = $result->fetch_assoc();
$check->close();

$stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendResponse(true, "Member '{$member['full_name']}' deleted successfully.");
} else {
    $stmt->close();
    $conn->close();
    sendResponse(false, "Failed to delete member.", null, 500);
}
