<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's properties
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM properties WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4">My Properties</h1>
        <a href="add_property.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Property
        </a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($property = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card property-card h-100">
                        <div class="position-relative">
                            <img src="<?php echo htmlspecialchars($property['image_url'] ?? 'assets/images/placeholder.jpg'); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-<?php echo $property['status'] === 'Available' ? 'success' : 'warning'; ?>">
                                    <?php echo htmlspecialchars($property['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($property['title']); ?></h5>
                            <p class="card-text text-muted">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($property['location']); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0">₹<?php echo number_format($property['price']); ?></h4>
                                <div class="property-features">
                                    <span class="me-2"><i class="bi bi-house-door"></i> <?php echo $property['property_type']; ?></span>
                                    <span class="me-2"><i class="bi bi-bed"></i> <?php echo $property['bedrooms']; ?></span>
                                    <span><i class="bi bi-droplet"></i> <?php echo $property['bathrooms']; ?></span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="edit_property.php?id=<?php echo $property['id']; ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete(<?php echo $property['id']; ?>)">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-house-door display-1 text-muted"></i>
            <h3 class="mt-3">No Properties Found</h3>
            <p class="text-muted">Start by adding your first property!</p>
            <a href="add_property.php" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg"></i> Add New Property
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(propertyId) {
    if (confirm('Are you sure you want to delete this property?')) {
        window.location.href = 'delete_property.php?id=' + propertyId;
    }
}
</script>

<?php include 'includes/footer.php'; ?> 