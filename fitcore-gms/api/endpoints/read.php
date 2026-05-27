<?php
// api/endpoints/read.php — GET /api/members or GET /api/members/{id}

$conn = getConnection();

if ($id) {
    // Read single member
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        sendResponse(false, "Member not found.", null, 404);
    }

    $member = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    sendResponse(true, "Member retrieved.", $member);

} else {
    // Read all members — support optional search & filter
    $where  = [];
    $params = [];
    $types  = "";

    if (!empty($_GET['search'])) {
        $search   = "%" . $_GET['search'] . "%";
        $where[]  = "(full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params   = array_merge($params, [$search, $search, $search]);
        $types   .= "sss";
    }

    if (!empty($_GET['plan']) && in_array($_GET['plan'], ['Basic', 'Standard', 'Premium'])) {
        $where[]  = "plan = ?";
        $params[] = $_GET['plan'];
        $types   .= "s";
    }

    if (!empty($_GET['status']) && in_array($_GET['status'], ['Active', 'Inactive', 'Suspended'])) {
        $where[]  = "status = ?";
        $params[] = $_GET['status'];
        $types   .= "s";
    }

    $sql = "SELECT * FROM members";
    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result  = $stmt->get_result();
    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    $stmt->close();
    $conn->close();
    sendResponse(true, count($members) . " member(s) found.", $members);
}
