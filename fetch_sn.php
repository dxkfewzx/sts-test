<?php
// Simulate fetch_sn.php functionality - in a real app this would query a database
// For HTML version, we'll return JSON data based on item_id

header('Content-Type: application/json');

// Sample data that would normally come from database
$sampleData = [
    1 => ['SN001234567', 'SN001234568', 'SN001234569', 'SN001234570', 'SN001234571', 'SN001234572'],
    2 => ['SN002234567', 'SN002234568', 'SN002234569', 'SN002234570', 'SN002234571', 'SN002234572', 'SN002234573', 'SN002234574', 'SN002234575', 'SN002234576', 'SN002234577', 'SN002234578', 'SN002234579', 'SN002234580', 'SN002234581'],
    3 => ['SN003234567', 'SN003234568', 'SN003234569', 'SN003234570', 'SN003234571'],
    4 => ['SN004234567', 'SN004234568', 'SN004234569', 'SN004234570'],
    5 => ['SN005234567', 'SN005234568', 'SN005234569', 'SN005234570', 'SN005234571', 'SN005234572', 'SN005234573', 'SN005234574', 'SN005234575', 'SN005234576', 'SN005234577', 'SN005234578', 'SN005234579', 'SN005234580']
];

$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

if (isset($sampleData[$item_id])) {
    echo json_encode($sampleData[$item_id]);
} else {
    echo json_encode([]); // Return empty array if item not found
}
?>