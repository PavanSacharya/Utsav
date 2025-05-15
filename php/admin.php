<?php
session_start();
require_once 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle section selection (default to reviews)
$section = $_GET['section'] ?? 'reviews';

// Pagination settings
$items_per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Sorting settings
$sort_column = $_GET['sort'] ?? 'created_at';
$sort_order = $_GET['order'] ?? 'DESC';
$valid_columns = ['id', 'created_at', 'name', 'email', 'event_type', 'amount', 'booking_date'];
$sort_column = in_array($sort_column, $valid_columns) ? $sort_column : 'created_at';
$sort_order = strtoupper($sort_order) === 'ASC' ? 'ASC' : 'DESC';

// Handle deletion
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = (int)$_GET['delete'];
    $type = $_GET['type'];
    
    try {
        if ($type === 'review') {
            $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        } elseif ($type === 'contact') {
            $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        } elseif ($type === 'booking') {
            $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        }
        $stmt->execute([$id]);
        header("Location: admin.php?section=$section&page=$page&sort=$sort_column&order=$sort_order");
        exit;
    } catch (PDOException $e) {
        error_log("Error deleting entry: " . $e->getMessage());
    }
}

// Fetch data based on section
$data = [];
$total_items = 0;
try {
    if ($section === 'reviews') {
        $count_stmt = $conn->query("SELECT COUNT(*) FROM reviews");
        $total_items = $count_stmt->fetchColumn();
        
        $stmt = $conn->prepare("SELECT r.*, u.username FROM reviews r LEFT JOIN users u ON r.user_id = u.id ORDER BY $sort_column $sort_order LIMIT ? OFFSET ?");
        $stmt->execute([$items_per_page, $offset]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($section === 'contact') {
        $count_stmt = $conn->query("SELECT COUNT(*) FROM contact_messages");
        $total_items = $count_stmt->fetchColumn();
        
        $stmt = $conn->prepare("SELECT * FROM contact_messages ORDER BY $sort_column $sort_order LIMIT ? OFFSET ?");
        $stmt->execute([$items_per_page, $offset]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($section === 'bookings') {
        $count_stmt = $conn->query("SELECT COUNT(*) FROM bookings");
        $total_items = $count_stmt->fetchColumn();
        
        $stmt = $conn->prepare("SELECT b.*, u.username FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY $sort_column $sort_order LIMIT ? OFFSET ?");
        $stmt->execute([$items_per_page, $offset]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $data = [];
    error_log("Error fetching data: " . $e->getMessage());
}

// Calculate total pages
$total_pages = max(1, ceil($total_items / $items_per_page));

// Helper function to generate sort links
function sortLink($column, $current_sort, $current_order) {
    global $section, $page;
    $new_order = ($current_sort === $column && $current_order === 'ASC') ? 'DESC' : 'ASC';
    return "admin.php?section=$section&page=$page&sort=$column&order=$new_order";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Organizer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 p-4">
            <h2 class="text-2xl font-bold text-yellow-400 mb-6">Admin Panel</h2>
            <nav>
                <a href="admin.php?section=reviews" class="block py-2 px-4 mb-2 rounded <?php echo $section === 'reviews' ? 'bg-yellow-400 text-gray-900' : 'hover:bg-gray-700'; ?>">Reviews</a>
                <a href="admin.php?section=contact" class="block py-2 px-4 mb-2 rounded <?php echo $section === 'contact' ? 'bg-yellow-400 text-gray-900' : 'hover:bg-gray-700'; ?>">Contact Messages</a>
                <a href="admin.php?section=bookings" class="block py-2 px-4 mb-2 rounded <?php echo $section === 'bookings' ? 'bg-yellow-400 text-gray-900' : 'hover:bg-gray-700'; ?>">Bookings</a>
                <a href="admin_logout.php" class="block py-2 px-4 mt-4 rounded bg-yellow-400 text-gray-900 hover:bg-yellow-500">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold text-yellow-400 mb-6">Admin Dashboard</h1>

            <!-- Data Table -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-yellow-400 mb-4">
                    <?php echo ucfirst($section); ?>
                </h2>

                <?php if ($section === 'reviews'): ?>
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-yellow-400 text-gray-900">
                                <th class="p-3"><a href="<?php echo sortLink('id', $sort_column, $sort_order); ?>" class="sort-link">ID</a></th>
                                <th class="p-3">Username</th>
                                <th class="p-3">Name</th>
                                <th class="p-3">Subject</th>
                                <th class="p-3">Description</th>
                                <th class="p-3"><a href="<?php echo sortLink('created_at', $sort_column, $sort_order); ?>" class="sort-link">Created At</a></th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data)): ?>
                                <tr>
                                    <td colspan="7" class="p-3 text-center">No reviews found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data as $item): ?>
                                    <tr class="border-b border-gray-700">
                                        <td class="p-3"><?php echo htmlspecialchars($item['id']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['username'] ?? 'N/A'); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['subject']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['description']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['created_at']); ?></td>
                                        <td class="p-3">
                                            <a href="admin.php?section=reviews&delete=<?php echo $item['id']; ?>&type=review&page=<?php echo $page; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this review?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($section === 'contact'): ?>
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-yellow-400 text-gray-900">
                                <th class="p-3"><a href="<?php echo sortLink('id', $sort_column, $sort_order); ?>" class="sort-link">ID</a></th>
                                <th class="p-3"><a href="<?php echo sortLink('name', $sort_column, $sort_order); ?>" class="sort-link">Name</a></th>
                                <th class="p-3"><a href="<?php echo sortLink('email', $sort_column, $sort_order); ?>" class="sort-link">Email</a></th>
                                <th class="p-3">Phone</th>
                                <th class="p-3">Subject</th>
                                <th class="p-3">Message</th>
                                <th class="p-3"><a href="<?php echo sortLink('created_at', $sort_column, $sort_order); ?>" class="sort-link">Created At</a></th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data)): ?>
                                <tr>
                                    <td colspan="8" class="p-3 text-center">No contact messages found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data as $item): ?>
                                    <tr class="border-b border-gray-700">
                                        <td class="p-3"><?php echo htmlspecialchars($item['id']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['email']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['phone']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['subject']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['message']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['created_at']); ?></td>
                                        <td class="p-3">
                                            <a href="admin.php?section=contact&delete=<?php echo $item['id']; ?>&type=contact&page=<?php echo $page; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this message?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($section === 'bookings'): ?>
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-yellow-400 text-gray-900">
                                <th class="p-3"><a href="<?php echo sortLink('id', $sort_column, $sort_order); ?>" class="sort-link">ID</a></th>
                                <th class="p-3">Username</th>
                                <th class="p-3"><a href="<?php echo sortLink('event_type', $sort_column, $sort_order); ?>" class="sort-link">Event Type</a></th>
                                <th class="p-3"><a href="<?php echo sortLink('amount', $sort_column, $sort_order); ?>" class="sort-link">Amount</a></th>
                                <th class="p-3"><a href="<?php echo sortLink('booking_date', $sort_column, $sort_order); ?>" class="sort-link">Booking Date</a></th>
                                <th class="p-3"><a href="<?php echo sortLink('created_at', $sort_column, $sort_order); ?>" class="sort-link">Created At</a></th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data)): ?>
                                <tr>
                                    <td colspan="7" class="p-3 text-center">No bookings found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data as $item): ?>
                                    <tr class="border-b border-gray-700">
                                        <td class="p-3"><?php echo htmlspecialchars($item['id']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['username'] ?? 'N/A'); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['event_type']); ?></td>
                                        <td class="p-3">$<?php echo number_format($item['amount'], 2); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['booking_date']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($item['created_at']); ?></td>
                                        <td class="p-3">
                                            <a href="admin.php?section=bookings&delete=<?php echo $item['id']; ?>&type=booking&page=<?php echo $page; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this booking?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="mt-6 flex justify-center space-x-2">
                        <?php if ($page > 1): ?>
                            <a href="admin.php?section=<?php echo $section; ?>&page=<?php echo $page - 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="px-4 py-2 bg-yellow-400 text-gray-900 rounded hover:bg-yellow-500">Previous</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?section=<?php echo $section; ?>&page=<?php echo $i; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="px-4 py-2 <?php echo $i === $page ? 'bg-yellow-500 text-gray-900' : 'bg-gray-700 text-white'; ?> rounded hover:bg-yellow-500"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="admin.php?section=<?php echo $section; ?>&page=<?php echo $page + 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>" class="px-4 py-2 bg-yellow-400 text-gray-900 rounded hover:bg-yellow-500">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>