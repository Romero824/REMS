<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // If user not found, log them out and redirect to login
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch user's properties count
$sql = "SELECT COUNT(*) as total FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_properties = $stmt->get_result()->fetch_assoc()['total'];

// Fetch available properties count
$sql = "SELECT COUNT(*) as available FROM properties WHERE user_id = ? AND status = 'Available'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$available_properties = $stmt->get_result()->fetch_assoc()['available'];

// Fetch total portfolio value
$sql = "SELECT COALESCE(SUM(price), 0) as total_value FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_value = $stmt->get_result()->fetch_assoc()['total_value'];

// Fetch recent properties
$sql = "SELECT * FROM properties WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_properties = $stmt->get_result();

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p class="lead text-muted">Here's an overview of your real estate portfolio</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-house-door text-primary display-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Properties</h6>
                            <h3 class="card-title mb-0"><?php echo $total_properties; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-success display-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Available Properties</h6>
                            <h3 class="card-title mb-0"><?php echo $available_properties; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-currency-peso text-info display-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Portfolio Value</h6>
                            <h3 class="card-title mb-0">₱<?php echo number_format($total_value, 2); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Properties -->
    <div class="row">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Properties</h5>
                        <a href="add_property.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New Property
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($recent_properties->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($property = $recent_properties->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($property['title']); ?></td>
                                            <td><?php echo htmlspecialchars($property['location']); ?></td>
                                            <td>₱<?php echo number_format($property['price'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $property['status'] === 'Available' ? 'success' : ($property['status'] === 'Sold' ? 'danger' : 'warning'); ?>">
                                                    <?php echo htmlspecialchars($property['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="view_property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="bi bi-house-door display-1"></i>
                            </div>
                            <h5>No Properties Yet</h5>
                            <p class="text-muted">Start by adding your first property to your portfolio.</p>
                            <a href="add_property.php" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Add Your First Property
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
