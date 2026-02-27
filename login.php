<?php
/**
 * Login Page - Premium Shopify-Grade Design
 * Edluxury - VIBRANT & User-Friendly
 */

require_once 'config/config.php';

// Redirect if already logged in
if (Security::isLoggedIn()) {
    header('Location: ' . SITE_URL);
    exit;
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password.';
        } else {
            $userModel = new User();
            $result = $userModel->login($email, $password);

            if ($result['success']) {
                // Set remember me cookie if checked
                if ($remember) {
                    setcookie('remember_user', $result['user']['id'], time() + (30 * 24 * 60 * 60), '/');
                }

                // Redirect to intended page or homepage
                $redirect = $_GET['redirect'] ?? SITE_URL;
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = $result['message'] ?? 'Invalid email or password.';
            }
        }
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<!-- Login Section -->
<section class="sh-section d-flex align-items-center"
    style="min-height: calc(100vh - 300px); background: linear-gradient(135deg, var(--sh-gray-50) 0%, var(--sh-white) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="text-center mb-4" data-aos="fade-up">
                    <a href="<?php echo Helpers::url(); ?>" class="sh-logo d-inline-block mb-3"
                        style="font-size: 36px;">
                        Edluxury
                    </a>
                    <h2 class="sh-heading-2">Welcome Back!</h2>
                    <p class="text-muted">Sign in to your account to continue shopping</p>
                </div>

                <div class="bg-white rounded-4 shadow-lg p-4 p-md-5" data-aos="fade-up" data-aos-delay="100">

                    <?php if ($error): ?>
                        <div class="alert alert-danger rounded-3 border-0 mb-4">
                            <i class="bi bi-exclamation-circle me-2"></i> <?php echo Security::escape($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success rounded-3 border-0 mb-4">
                            <i class="bi bi-check-circle me-2"></i> <?php echo Security::escape($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="loginForm">
                        <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>"
                            value="<?php echo Security::generateCSRFToken(); ?>">

                        <div class="mb-4">
                            <label class="sh-form-label">Email Address</label>
                            <div class="position-relative">
                                <input type="email" name="email" class="sh-form-input ps-5" placeholder="your@email.com"
                                    value="<?php echo Security::escape($_POST['email'] ?? ''); ?>" required autofocus>
                                <i
                                    class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="sh-form-label mb-0">Password</label>
                                <a href="<?php echo Helpers::url('forgot-password.php'); ?>"
                                    class="small text-decoration-none" style="color: var(--sh-primary);">
                                    Forgot Password?
                                </a>
                            </div>
                            <div class="position-relative mt-2">
                                <input type="password" name="password" class="sh-form-input ps-5 pe-5"
                                    placeholder="Enter your password" id="passwordInput" required>
                                <i
                                    class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-1"
                                    onclick="togglePassword()" id="toggleBtn">
                                    <i class="bi bi-eye text-muted" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">
                                    Remember me for 30 days
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="sh-btn sh-btn-primary sh-btn-full mb-4" id="submitBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                        </button>

                        <!-- Divider -->
                        <div class="text-center my-4">
                            <span class="bg-white px-3 text-muted small position-relative" style="z-index: 1;">or
                                continue with</span>
                            <hr class="position-relative" style="margin-top: -10px;">
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="d-flex gap-3 mb-4">
                            <button type="button" class="btn btn-outline-secondary flex-grow-1 py-3 rounded-3" disabled>
                                <i class="bi bi-google me-2"></i> Google
                            </button>
                            <button type="button" class="btn btn-outline-secondary flex-grow-1 py-3 rounded-3" disabled>
                                <i class="bi bi-apple me-2"></i> Apple
                            </button>
                        </div>

                        <p class="text-center mb-0">
                            Don't have an account?
                            <a href="<?php echo Helpers::url('register.php'); ?>" class="fw-bold text-decoration-none"
                                style="color: var(--sh-primary);">
                                Create Account
                            </a>
                        </p>
                    </form>
                </div>

                <!-- Benefits -->
                <div class="mt-4 text-center" data-aos="fade-up" data-aos-delay="200">
                    <p class="small text-muted mb-3">Why create an account?</p>
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <span class="small"><i class="bi bi-check-circle me-1" style="color: var(--sh-accent);"></i>
                            Track orders</span>
                        <span class="small"><i class="bi bi-check-circle me-1" style="color: var(--sh-accent);"></i>
                            Faster checkout</span>
                        <span class="small"><i class="bi bi-check-circle me-1" style="color: var(--sh-accent);"></i>
                            Exclusive offers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Toggle password visibility
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('toggleIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Signing in...';
        btn.disabled = true;
    });
</script>

<?php require_once 'includes/footer.php'; ?>