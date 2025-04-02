<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $price = trim($_POST['price']);
    $bedrooms = trim($_POST['bedrooms']);
    $bathrooms = trim($_POST['bathrooms']);
    $property_type = trim($_POST['property_type']);
    $description = trim($_POST['description']);
    $status = 'Available';
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($title) || empty($location) || empty($price)) {
        $error = "Please fill in all required fields";
    } else {
        // Insert property
        $sql = "INSERT INTO properties (user_id, title, location, price, bedrooms, bathrooms, property_type, description, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiiisss", $user_id, $title, $location, $price, $bedrooms, $bathrooms, $property_type, $description, $status);

        if ($stmt->execute()) {
            $success = "Property added successfully!";
            // Clear form data
            $_POST = array();
        } else {
            $error = "Error adding property: " . $conn->error;
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="display-5 fw-bold">Add New Property</h2>
                        <p class="text-muted">Fill in the details of your property</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="row g-4">
                            <!-- Property Title -->
                            <div class="col-12">
                                <label for="title" class="form-label">Property Title <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                    <input type="text" class="form-control" id="title" name="title" required 
                                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-12">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="location" name="location" required
                                           value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price (₱) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-peso"></i></span>
                                    <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01"
                                           value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                                </div>
                            </div>

                            <!-- Property Type -->
                            <div class="col-md-6">
                                <label for="property_type" class="form-label">Property Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <select class="form-select" id="property_type" name="property_type" required>
                                        <option value="">Select type</option>
                                        <option value="House" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'House') ? 'selected' : ''; ?>>House</option>
                                        <option value="Apartment" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'Apartment') ? 'selected' : ''; ?>>Apartment</option>
                                        <option value="Condo" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'Condo') ? 'selected' : ''; ?>>Condo</option>
                                        <option value="Land" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'Land') ? 'selected' : ''; ?>>Land</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bedrooms -->
                            <div class="col-md-6">
                                <label for="bedrooms" class="form-label">Bedrooms</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                                    <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0"
                                           value="<?php echo isset($_POST['bedrooms']) ? htmlspecialchars($_POST['bedrooms']) : ''; ?>">
                                </div>
                            </div>

                            <!-- Bathrooms -->
                            <div class="col-md-6">
                                <label for="bathrooms" class="form-label">Bathrooms</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-water"></i></span>
                                    <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0"
                                           value="<?php echo isset($_POST['bathrooms']) ? htmlspecialchars($_POST['bathrooms']) : ''; ?>">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-plus-circle me-2"></i>Add Property
                                    </button>
                                    <a href="dashboard.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control, .form-select {
    border-left: none;
}

.form-control:focus, .form-select:focus {
    border-color: #ced4da;
    box-shadow: none;
}

.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control,
.input-group:focus-within .form-select {
    border-color: #86b7fe;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-outline-secondary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}
</style>

<?php include 'includes/footer.php'; ?>
