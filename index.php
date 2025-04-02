<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative">
    <div class="container">
        <div class="row min-vh-75 align-items-center">
            <div class="col-lg-6">
                <h1 class="display-3 fw-bold text-white mb-4">Find Your Dream Property</h1>
                <p class="lead text-white mb-4">Discover the perfect property that matches your lifestyle and investment goals.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn btn-light btn-lg">
                        <i class="bi bi-speedometer2"></i> Go to Dashboard
                    </a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus"></i> Get Started
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 end-0 bottom-0 start-0 bg-dark opacity-50"></div>
</section>

<!-- Search Section -->
<section class="search-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form class="row g-3" method="GET" action="search_results.php">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" name="location" placeholder="Search by location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="property_type">
                                    <option value="" <?php echo (!isset($_GET['property_type']) || $_GET['property_type'] == '') ? 'selected' : ''; ?>>Property Type</option>
                                    <option value="House" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'House') ? 'selected' : ''; ?>>House</option>
                                    <option value="Apartment" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'Apartment') ? 'selected' : ''; ?>>Apartment</option>
                                    <option value="Condo" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'Condo') ? 'selected' : ''; ?>>Condo</option>
                                    <option value="Land" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'Land') ? 'selected' : ''; ?>>Land</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="price_range">
                                    <option value="" <?php echo (!isset($_GET['price_range']) || $_GET['price_range'] == '') ? 'selected' : ''; ?>>Price Range</option>
                                    <option value="1-5" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '1-5') ? 'selected' : ''; ?>>₱1M - ₱5M</option>
                                    <option value="5-10" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '5-10') ? 'selected' : ''; ?>>₱5M - ₱10M</option>
                                    <option value="10-20" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '10-20') ? 'selected' : ''; ?>>₱10M - ₱20M</option>
                                    <option value="20+" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '20+') ? 'selected' : ''; ?>>₱20M+</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties Section -->
<section class="featured-properties py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold">Featured Properties</h2>
                <p class="lead text-muted">Explore our handpicked selection of premium properties</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="view_properties.php" class="btn btn-outline-primary">View All Properties</a>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            // Fetch featured properties from database
            $sql = "SELECT * FROM properties WHERE status = 'Available' ORDER BY created_at DESC LIMIT 3";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($property = $result->fetch_assoc()) {
            ?>
                <div class="col-md-4">
                    <div class="card property-card h-100 border-0 shadow-sm">
                        <div class="property-image position-relative">
                            <img src="<?php echo !empty($property['image_url']) ? htmlspecialchars($property['image_url']) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'; ?>" 
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
            <?php
                }
            } else {
                // If no properties found, show placeholder cards
                for ($i = 0; $i < 3; $i++) {
                    // Different Cebu City images for each card
                    $cebuImages = [
                        'images/cebu-city-1.jpg', // Cebu City skyline
                        'images/cebu-city-2.jpg', // Cebu City street
                        'images/cebu-city-3.jpg'  // Cebu City beach
                    ];
                    
                    // Different locations for each card
                    $locations = [
                        'Verdana Heights Labangon',
                        'Cebu City',
                        'Cebu City'
                    ];
                    
                    // Different prices for each card
                    $prices = [
                        '₱15,000,000',
                        '₱8,500,000',
                        '₱12,750,000'
                    ];
            ?>
                <div class="col-md-4">
                    <div class="card property-card h-100 border-0 shadow-sm">
                        <div class="property-image position-relative">
                            <img src="<?php echo $cebuImages[$i]; ?>" 
                                 class="card-img-top" alt="Cebu City Property">
                            <div class="property-status position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success">Available</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Cebu City</h5>
                            <p class="card-text text-muted">
                                <i class="bi bi-geo-alt me-1"></i> <?php echo $locations[$i]; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0"><?php echo $prices[$i]; ?></h4>
                                <div>
                                    <span class="me-2"><i class="bi bi-door-open"></i> 4</span>
                                    <span><i class="bi bi-water"></i> 3</span>
                                </div>
                            </div>
                            <a href="register.php" class="btn btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold">Why Choose Our Platform</h2>
                <p class="lead text-muted">We provide the best experience for property management</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-house-door text-primary display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">Property Management</h3>
                        <p class="text-muted mb-0">Easily manage all your properties in one place with detailed information and status tracking.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-graph-up text-success display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">Portfolio Analytics</h3>
                        <p class="text-muted mb-0">Track your portfolio performance with comprehensive analytics and reporting tools.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-shield-check text-info display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">Secure & Reliable</h3>
                        <p class="text-muted mb-0">Your data is protected with industry-standard security measures and regular backups.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold">What Our Clients Say</h2>
                <p class="lead text-muted">Hear from our satisfied customers</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Davis Sentillas</h5>
                                <p class="text-muted mb-0">Property Investor</p>
                            </div>
                        </div>
                        <p class="card-text">"This platform has completely transformed how I manage my real estate portfolio. The analytics and tracking features are invaluable."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">James Cort Lasconia</h5>
                                <p class="text-muted mb-0">Real Estate Agent</p>
                            </div>
                        </div>
                        <p class="card-text">"As a real estate agent, I need a reliable platform to manage my listings. This system has everything I need and more."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Chan Alvarez</h5>
                                <p class="text-muted mb-0">Homeowner</p>
                            </div>
                        </div>
                        <p class="card-text">"I found my dream home through this platform. The search features and property details made it easy to find exactly what I was looking for."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
                <p class="lead text-muted mb-4">Join thousands of property managers who trust our platform for their real estate management needs.</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus"></i> Create Your Account
                    </a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2"></i> Go to Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
    background-size: cover;
    background-position: center;
    min-height: 75vh;
    position: relative;
}

.min-vh-75 {
    min-height: 75vh;
}

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

/* Feature Icons */
.feature-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Search Form */
.search-section {
    margin-top: -50px;
    position: relative;
    z-index: 10;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .search-section {
        margin-top: 0;
    }
    
    .hero-section {
        min-height: 60vh;
    }
}
</style>

<?php include 'includes/footer.php'; ?> 