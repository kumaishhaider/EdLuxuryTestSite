<?php
/**
 * Register Page - Premium Luxury Design
 */

require_once 'config/config.php';

// Redirect if already logged in
if (Security::isLoggedIn()) {
    Helpers::redirect(Helpers::url('index.php'));
}

$pageTitle = Helpers::translate('register');
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (!Security::validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error = 'Invalid security token';
    } else {
        $data = [
            'first_name' => Security::sanitize($_POST['first_name'] ?? ''),
            'last_name' => Security::sanitize($_POST['last_name'] ?? ''),
            'email' => Security::sanitize($_POST['email'] ?? ''),
            'phone' => Security::sanitize($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'status' => 'active'
        ];

        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($data['password'] !== $confirmPassword) {
            $error = 'Passwords do not match';
        } else {
            $userModel = new User();
            $result = $userModel->register($data);

            if ($result['success']) {
                // Auto login or redirect to login
                $userModel->login($data['email'], $data['password']);
                Helpers::redirect(Helpers::url('index.php'));
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>

<div class="auth-page-wrapper py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-dark mb-2">
                                <?php echo Helpers::translate('register'); ?>
                            </h2>
                            <p class="text-muted small">
                                <?php echo CURRENT_LANG === 'ar' ? 'انضم إلى مجتمعنا الحصري لتجربة تسوق فريدة' : 'Join our exclusive community for a unique shopping experience'; ?>
                            </p>
                            <div class="mx-auto mt-3 bg-primary" style="height: 3px; width: 50px;"></div>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger border-0 rounded-3 small">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <?php echo Security::getCSRFInput(); ?>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label small fw-bold text-uppercase opacity-75">
                                        <?php echo CURRENT_LANG === 'ar' ? 'الاسم الأول' : 'First Name'; ?>
                                    </label>
                                    <input type="text" name="first_name" class="form-control bg-light border-0 py-3"
                                        required
                                        placeholder="<?php echo CURRENT_LANG === 'ar' ? 'مثال: محمد' : 'e.g. John'; ?>"
                                        value="<?php echo Security::escape($_POST['first_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label small fw-bold text-uppercase opacity-75">
                                        <?php echo CURRENT_LANG === 'ar' ? 'اسم العائلة' : 'Last Name'; ?>
                                    </label>
                                    <input type="text" name="last_name" class="form-control bg-light border-0 py-3"
                                        required
                                        placeholder="<?php echo CURRENT_LANG === 'ar' ? 'مثال: أحمد' : 'e.g. Doe'; ?>"
                                        value="<?php echo Security::escape($_POST['last_name'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase opacity-75">
                                    <?php echo CURRENT_LANG === 'ar' ? 'البريد الإلكتروني' : 'Email Address'; ?>
                                </label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-3" required
                                    placeholder="name@example.com"
                                    value="<?php echo Security::escape($_POST['email'] ?? ''); ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase opacity-75">
                                    <?php echo CURRENT_LANG === 'ar' ? 'رقم الهاتف' : 'Phone Number'; ?>
                                </label>
                                <input type="tel" name="phone" class="form-control bg-light border-0 py-3" required
                                    placeholder="+971 50 000 0000"
                                    value="<?php echo Security::escape($_POST['phone'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label small fw-bold text-uppercase opacity-75">
                                        <?php echo CURRENT_LANG === 'ar' ? 'كلمة المرور' : 'Password'; ?>
                                    </label>
                                    <input type="password" name="password" class="form-control bg-light border-0 py-3"
                                        required placeholder="••••••••">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label small fw-bold text-uppercase opacity-75">
                                        <?php echo CURRENT_LANG === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password'; ?>
                                    </label>
                                    <input type="password" name="confirm_password"
                                        class="form-control bg-light border-0 py-3" required placeholder="••••••••">
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small text-muted" for="terms">
                                    <?php echo CURRENT_LANG === 'ar' ? 'أوافق على الشروط والأحكام' : 'I agree to the Terms & Conditions'; ?>
                                </label>
                            </div>

                            <button type="submit" name="register"
                                class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow-sm mb-4">
                                <?php echo Helpers::translate('create_account'); ?>
                            </button>

                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    <?php echo CURRENT_LANG === 'ar' ? 'لديك حساب بالفعل؟' : "Already have an account?"; ?>
                                    <a href="<?php echo Helpers::url('login.php'); ?>"
                                        class="text-primary fw-bold text-decoration-none">
                                        <?php echo Helpers::translate('login'); ?>
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo Helpers::url('index.php'); ?>" class="text-muted small text-decoration-none">
                        <i class="bi bi-<?php echo IS_RTL ? 'arrow-right' : 'arrow-left'; ?> me-1"></i>
                        <?php echo CURRENT_LANG === 'ar' ? 'العودة للرئيسية' : 'Back to Home'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-page-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .form-control:focus {
        background-color: #fff !important;
        box-shadow: none;
        border: 1px solid var(--primary-color) !important;
    }
</style>

<?php require_once 'includes/footer.php'; ?>