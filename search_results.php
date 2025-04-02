<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Get search parameters
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$property_type = isset($_GET['property_type']) ? trim($_GET['property_type']) : '';
$price_range = isset($_GET['price_range']) ? trim($_GET['price_range']) : '';

// Build the SQL query
$sql = "SELECT * FROM properties WHERE status = 'Available'";
$params = [];
$types = "";

// Add location filter if provided
if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}

// Add property type filter if provided
if (!empty($property_type)) {
    $sql .= " AND property_type = ?";
    $params[] = $property_type;
    $types .= "s";
}

// Add price range filter if provided
if (!empty($price_range)) {
    switch ($price_range) {
        case '1-5':
            $sql .= " AND price BETWEEN 1000000 AND 5000000";
            break;
        case '5-10':
            $sql .= " AND price BETWEEN 5000000 AND 10000000";
            break;
        case '10-20':
            $sql .= " AND price BETWEEN 10000000 AND 20000000";
            break;
        case '20+':
            $sql .= " AND price > 20000000";
            break;
    }
}

$sql .= " ORDER BY created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold">Search Results</h1>
            <p class="lead text-muted">
                <?php if (!empty($location) || !empty($property_type) || !empty($price_range)): ?>
                    Showing properties matching your search criteria
                <?php else: ?>
                    Showing all available properties
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="index.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <form class="row g-3" method="GET" action="search_results.php">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="location" placeholder="Search by location" value="<?php echo htmlspecialchars($location); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="property_type">
                        <option value="" <?php echo empty($property_type) ? 'selected' : ''; ?>>Property Type</option>
                        <option value="House" <?php echo $property_type == 'House' ? 'selected' : ''; ?>>House</option>
                        <option value="Apartment" <?php echo $property_type == 'Apartment' ? 'selected' : ''; ?>>Apartment</option>
                        <option value="Condo" <?php echo $property_type == 'Condo' ? 'selected' : ''; ?>>Condo</option>
                        <option value="Land" <?php echo $property_type == 'Land' ? 'selected' : ''; ?>>Land</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="price_range">
                        <option value="" <?php echo empty($price_range) ? 'selected' : ''; ?>>Price Range</option>
                        <option value="1-5" <?php echo $price_range == '1-5' ? 'selected' : ''; ?>>₱1M - ₱5M</option>
                        <option value="5-10" <?php echo $price_range == '5-10' ? 'selected' : ''; ?>>₱5M - ₱10M</option>
                        <option value="10-20" <?php echo $price_range == '10-20' ? 'selected' : ''; ?>>₱10M - ₱20M</option>
                        <option value="20+" <?php echo $price_range == '20+' ? 'selected' : ''; ?>>₱20M+</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
        <p class="text-muted">
            <?php echo $result->num_rows; ?> properties found
            <?php if (!empty($location) || !empty($property_type) || !empty($price_range)): ?>
                matching your criteria
            <?php endif; ?>
        </p>
    </div>

    <!-- Property Results -->
    <div class="row g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($property = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card property-card h-100 border-0 shadow-sm">
                        <div class="property-image position-relative">
                            <img src="<?php echo !empty($property['image_url']) ? htmlspecialchars($property['image_url']) : 'images/cebu-city-1.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            <div class="property-status position-absolute top-0 end-0 m-3">
                                <span class="badge bg-<?php echo $property['status'] === 'Available' ? 'success' : ($property['status'] === 'Sold' ? 'danger' : 'warning'); ?>">
                                    <?php echo htmlspecialchars($property['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($property['title']); ?></h5>
                            <p class="card-text text-muted">
                                <i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($property['location']); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0">₱<?php echo number_format($property['price'], 2); ?></h4>
                                <div>
                                    <?php if ($property['bedrooms']): ?>
                                        <span class="me-2"><i class="bi bi-door-open"></i> <?php echo $property['bedrooms']; ?></span>
                                    <?php endif; ?>
                                    <?php if ($property['bathrooms']): ?>
                                        <span><i class="bi bi-water"></i> <?php echo $property['bathrooms']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="view_property.php?id=<?php echo $property['id']; ?>" class="btn btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> No properties found matching your search criteria. Try adjusting your filters.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Property Cards */
.property-card {
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.property-card:hover {
    transform: translateY(-10px);
}

.property-image {
    height: 200px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.property-card:hover .property-image img {
    transform: scale(1.1);
}
</style>

<?php include 'includes/footer.php'; ?> 