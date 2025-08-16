<?php
session_start();

// Include database connection
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/Login1.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- Handle Form Submissions ---

// Delete a single notification (accepted/declined)
if (isset($_POST['delete_notification']) && isset($_POST['request_id'])) {
    $request_id = intval($_POST['request_id']);
    $delete_sql = "DELETE FROM good_exchange_request 
                   WHERE request_id = ? AND (receiver_id = ? OR sender_id = ?) 
                   AND status IN ('accepted', 'declined')";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("iii", $request_id, $user_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: notificationDisplay.php");
    exit();
}

// Clear all processed notifications
if (isset($_POST['clear_all_notifications'])) {
    $clear_sql = "DELETE FROM good_exchange_request 
                  WHERE (receiver_id = ? OR sender_id = ?) 
                  AND status IN ('accepted', 'declined')";
    $stmt = $conn->prepare($clear_sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: notificationDisplay.php");
    exit();
}

// --- Fetch Received Notifications (where user is the receiver) ---
$received_sql = "SELECT 
                    ger.request_id, 
                    ger.sender_id, 
                    ger.receiver_id,
                    ger.post_id, 
                    ger.item_id, 
                    ger.status, 
                    ger.exchange_status,
                    ger.request_date,
                    u_sender.full_name AS sender_name
                FROM good_exchange_request AS ger
                JOIN user AS u_sender ON ger.sender_id = u_sender.user_id
                WHERE ger.receiver_id = ?
                ORDER BY
                    CASE
                        WHEN ger.status = 'pending' THEN 1
                        WHEN ger.status = 'accepted' THEN 2
                        WHEN ger.status = 'declined' THEN 3
                    END,
                    ger.request_date DESC";

$received_stmt = $conn->prepare($received_sql);
$received_stmt->bind_param("i", $user_id);
$received_stmt->execute();
$received_result = $received_stmt->get_result();

// --- Fetch Sent Notifications (where user is the sender) ---
$sent_sql = "SELECT 
                ger.request_id, 
                ger.sender_id, 
                ger.receiver_id,
                ger.post_id, 
                ger.item_id, 
                ger.status, 
                ger.exchange_status,
                ger.request_date,
                u_receiver.full_name AS receiver_name
            FROM good_exchange_request AS ger
            JOIN user AS u_receiver ON ger.receiver_id = u_receiver.user_id
            WHERE ger.sender_id = ?
            ORDER BY
                CASE
                    WHEN ger.status = 'pending' THEN 1
                    WHEN ger.status = 'accepted' THEN 2
                    WHEN ger.status = 'declined' THEN 3
                END,
                ger.request_date DESC";

$sent_stmt = $conn->prepare($sent_sql);
$sent_stmt->bind_param("i", $user_id);
$sent_stmt->execute();
$sent_result = $sent_stmt->get_result();

// Count processed notifications (for the clear button)
$processed_sql = "SELECT COUNT(*) as processed_count FROM good_exchange_request WHERE (receiver_id = ? OR sender_id = ?) AND status IN ('accepted', 'declined')";
$processed_stmt = $conn->prepare($processed_sql);
$processed_stmt->bind_param("ii", $user_id, $user_id);
$processed_stmt->execute();
$processed_result = $processed_stmt->get_result();
$processed_count = $processed_result->fetch_assoc()['processed_count'];
$processed_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notifications - CeylonConnect</title>
    <link rel="stylesheet" href="notifications.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
<header class="main-header">
    <div class="header-container">
        <div class="logo-group">
            <img src="photos/logo.png" alt="CeylonConnect Logo" class="logo-img" />
            <h1 class="logo-header">CeylonConnect</h1>
        </div>
        <nav class="nav-links">
            <a href="/ceylonconnect/Profile/AllPost/profile.html">Profile</a>
        </nav>
    </div>
</header>

<main class="main-content">
    <div class="notifications-header">
        <h2>Exchange Requests</h2>
        <div class="notification-controls">
            <?php if ($processed_count > 0): ?>
                <span class="processed-count">
                    <i class="fas fa-check"></i>
                    <?php echo $processed_count; ?> processed
                </span>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="clear_all_notifications" class="btn btn-secondary"
                            onclick="return confirm('Are you sure you want to clear all processed notifications?')">
                        <i class="fas fa-trash-alt"></i> Clear Processed
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="notifications-section">
        <h3 class="section-title">Requests Received 📥</h3>
        <div class="notifications">
            <?php if ($received_result->num_rows > 0): ?>
                <?php while ($row = $received_result->fetch_assoc()): ?>
                    <?php 
                        $notification_text = "";
                        switch ($row['status']) {
                            case 'pending':
                                $notification_text = "New Exchange Request";
                                break;
                            case 'accepted':
                                $notification_text = "Request Accepted";
                                break;
                            case 'declined':
                                $notification_text = "Request Declined";
                                break;
                        }
                    ?>
                    <div class="notification <?php echo $row['status']; ?>">
                        <div class="icon">
                            <?php
                            switch ($row['status']) {
                                case 'pending':
                                    echo '<i class="fas fa-hourglass-half"></i>';
                                    break;
                                case 'accepted':
                                    echo '<i class="fas fa-check-circle"></i>';
                                    break;
                                case 'declined':
                                    echo '<i class="fas fa-times-circle"></i>';
                                    break;
                            }
                            ?>
                        </div>
                        <div class="notification-content">
                            <h3>
                                <?php echo htmlspecialchars($notification_text); ?>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </h3>
                            <div class="exchange-details">
                                <p><strong>Item Post ID:</strong> <?php echo htmlspecialchars($row['post_id']); ?></p>
                                <p><strong>Requested Item ID:</strong> <?php echo htmlspecialchars($row['item_id']); ?></p>
                            </div>
                            <div class="notification-details">
                                <span class="notification-sender">
                                    <i class="fas fa-user"></i> From: <strong><?php echo htmlspecialchars($row['sender_name']); ?></strong>
                                </span>
                                <span class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('F j, Y, g:i a', strtotime($row['request_date'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="notification-actions">
                            <?php if ($row['exchange_status'] == 'success'): ?>
                                <span class="btn-action success-display">
                                    <i class="fas fa-handshake"></i> Exchange Success
                                </span>
                            <?php elseif ($row['status'] == 'pending'): ?>
                                <a href="/ceylonconnect/notificationItem.php?request_id=<?php echo $row['request_id']; ?>" class="btn-action view" title="View details">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            <?php elseif ($row['status'] == 'accepted'): ?>
                                <a href="/ceylonconnect/php/chat.php?request_id=<?php echo $row['request_id']; ?>" class="btn-action chat" title="Go to Chat">
                                    <i class="fas fa-comment"></i> Go to Chat
                                </a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn-action delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            <?php elseif ($row['status'] == 'declined'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn-action delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-notifications">You have no new requests.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="notifications-section">
        <h3 class="section-title">Requests Sent 📤</h3>
        <div class="notifications">
            <?php if ($sent_result->num_rows > 0): ?>
                <?php while ($row = $sent_result->fetch_assoc()): ?>
                    <?php 
                        $notification_text = "";
                        switch ($row['status']) {
                            case 'pending':
                                $notification_text = "Your Request Is Pending";
                                break;
                            case 'accepted':
                                $notification_text = "Your Request Was Accepted";
                                break;
                            case 'declined':
                                $notification_text = "Your Request Was Declined";
                                break;
                        }
                    ?>
                    <div class="notification <?php echo $row['status']; ?>">
                        <div class="icon">
                            <?php
                            switch ($row['status']) {
                                case 'pending':
                                    echo '<i class="fas fa-hourglass-half"></i>';
                                    break;
                                case 'accepted':
                                    echo '<i class="fas fa-check-circle"></i>';
                                    break;
                                case 'declined':
                                    echo '<i class="fas fa-times-circle"></i>';
                                    break;
                            }
                            ?>
                        </div>
                        <div class="notification-content">
                            <h3>
                                <?php echo htmlspecialchars($notification_text); ?>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </h3>
                            <div class="exchange-details">
                                <p><strong>Item Post ID:</strong> <?php echo htmlspecialchars($row['post_id']); ?></p>
                                <p><strong>Requested Item ID:</strong> <?php echo htmlspecialchars($row['item_id']); ?></p>
                            </div>
                            <div class="notification-details">
                                <span class="notification-sender">
                                    <i class="fas fa-user"></i> To: <strong><?php echo htmlspecialchars($row['receiver_name']); ?></strong>
                                </span>
                                <span class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('F j, Y, g:i a', strtotime($row['request_date'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="notification-actions">
                            <?php if ($row['exchange_status'] == 'success'): ?>
                                <span class="btn-action success-display">
                                    <i class="fas fa-handshake" style="color: #28a745;"></i> <span style="color: #28a745; font-weight: bold;">Exchange Success</span>
                                </span>
                            <?php elseif ($row['status'] == 'accepted'): ?>
                                <a href="/ceylonconnect/php/chat.php?request_id=<?php echo $row['request_id']; ?>" class="btn-action chat" title="Go to Chat">
                                    <i class="fas fa-comment"></i> Go to Chat
                                </a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn-action delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            <?php elseif ($row['status'] == 'declined'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn-action delete"
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-notifications">You have not sent any requests.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>