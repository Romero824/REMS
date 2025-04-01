<?php
session_start();
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="hero-section position-relative">
    <div class="container">
        <div class="row min-vh-75 align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-white mb-4">Manage Your Real Estate Portfolio with Ease</h1>
                <p class="lead text-white mb-4">Streamline your property management process with our comprehensive real estate management system.</p>
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
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-house-door display-4"></i>
                    </div>
                    <h3 class="h5 mb-3">Property Management</h3>
                    <p class="text-muted mb-0">Easily manage all your properties in one place with detailed information and status tracking.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-graph-up display-4"></i>
                    </div>
                    <h3 class="h5 mb-3">Portfolio Analytics</h3>
                    <p class="text-muted mb-0">Track your portfolio performance with comprehensive analytics and reporting tools.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-shield-check display-4"></i>
                    </div>
                    <h3 class="h5 mb-3">Secure & Reliable</h3>
                    <p class="text-muted mb-0">Your data is protected with industry-standard security measures and regular backups.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 mb-4">Ready to Get Started?</h2>
                <p class="lead text-muted mb-4">Join thousands of property managers who trust our platform for their real estate management needs.</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus"></i> Create Your Account
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>

<?php include 'includes/footer.php'; ?> 