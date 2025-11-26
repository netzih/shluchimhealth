<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$pageTitle = 'Settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name',
        'site_tagline',
        'site_description',
        'posts_per_page',
        'products_per_page',
        'amazon_tag',
        'logo_url',
        'calcom_username',
        'calcom_event_type',
        'calcom_followup_event_type',
        'enable_booking_widget'
    ];

    foreach ($settings as $key) {
        if (isset($_POST[$key])) {
            updateSetting($key, $_POST[$key]);
        }
    }

    // Handle password change
    if (!empty($_POST['new_password'])) {
        $userId = $_SESSION['user_id'];
        $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        db()->update('users', ['password' => $hashedPassword], 'id = :id', ['id' => $userId]);
        setFlash('success', 'Settings and password updated successfully.');
    } else {
        setFlash('success', 'Settings updated successfully.');
    }

    redirect(BASE_URL . '/admin/settings.php');
}

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Settings</h1>
    </div>

    <form method="POST" class="settings-form">
        <div class="settings-section">
            <h2>Site Information</h2>

            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="<?php echo escape(getSetting('site_name')); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="site_tagline">Site Tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" value="<?php echo escape(getSetting('site_tagline')); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="site_description">Site Description</label>
                <textarea id="site_description" name="site_description" rows="3" class="form-control"><?php echo escape(getSetting('site_description')); ?></textarea>
            </div>

            <div class="form-group">
                <label for="logo_url">Logo URL</label>
                <input type="url" id="logo_url" name="logo_url" value="<?php echo escape(getSetting('logo_url')); ?>" class="form-control">
            </div>
        </div>

        <div class="settings-section">
            <h2>Display Settings</h2>

            <div class="form-group">
                <label for="posts_per_page">Blog Posts Per Page</label>
                <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo escape(getSetting('posts_per_page')); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="products_per_page">Products Per Page</label>
                <input type="number" id="products_per_page" name="products_per_page" value="<?php echo escape(getSetting('products_per_page')); ?>" class="form-control">
            </div>
        </div>

        <div class="settings-section">
            <h2>Affiliate Settings</h2>

            <div class="form-group">
                <label for="amazon_tag">Amazon Associate Tag</label>
                <input type="text" id="amazon_tag" name="amazon_tag" value="<?php echo escape(getSetting('amazon_tag')); ?>" class="form-control">
                <small>Your Amazon Associates tracking ID</small>
            </div>
        </div>

        <div class="settings-section">
            <h2>Booking & Appointments</h2>

            <div class="form-group">
                <label for="calcom_username">Cal.com Username</label>
                <input type="text" id="calcom_username" name="calcom_username" value="<?php echo escape(getSetting('calcom_username')); ?>" class="form-control" placeholder="yourusername">
                <small>Your Cal.com username (e.g., "john" from cal.com/john)</small>
            </div>

            <div class="form-group">
                <label for="calcom_event_type">New Client Event Type</label>
                <input type="text" id="calcom_event_type" name="calcom_event_type" value="<?php echo escape(getSetting('calcom_event_type')); ?>" class="form-control" placeholder="consultation">
                <small>Event slug for first-time clients (e.g., "consultation", "initial-consult")</small>
            </div>

            <div class="form-group">
                <label for="calcom_followup_event_type">Follow-up Event Type</label>
                <input type="text" id="calcom_followup_event_type" name="calcom_followup_event_type" value="<?php echo escape(getSetting('calcom_followup_event_type')); ?>" class="form-control" placeholder="follow-up">
                <small>Event slug for returning clients (e.g., "follow-up", "check-in")</small>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_booking_widget" value="1" <?php echo getSetting('enable_booking_widget') ? 'checked' : ''; ?>>
                    Enable Floating Booking Button
                </label>
                <small>Show a floating "Book Consultation" button on all pages</small>
            </div>
        </div>

        <div class="settings-section">
            <h2>Change Password</h2>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
                <small>Leave empty to keep current password</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Settings</button>
    </form>
</div>

<?php include 'footer.php'; ?>
