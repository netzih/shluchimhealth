<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cal.com Config Check</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #2563eb;
            margin-top: 0;
        }
        h2 {
            color: #1e40af;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .setting {
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #2563eb;
            margin: 10px 0;
        }
        .setting strong {
            display: block;
            color: #374151;
            margin-bottom: 5px;
        }
        .value {
            font-family: monospace;
            background: #1f2937;
            color: #10b981;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        .not-set {
            color: #ef4444;
        }
        .url {
            background: #eff6ff;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            word-break: break-all;
        }
        .check-item {
            padding: 10px;
            margin: 5px 0;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        .raw-data {
            background: #1f2937;
            color: #d1d5db;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔍 Cal.com Configuration Diagnostic</h1>
        <p>This page shows exactly what Cal.com settings are stored in your database and what URLs will be generated.</p>
    </div>

    <div class="card">
        <h2>Database Settings</h2>

        <?php
        $username = getSetting('calcom_username');
        $newClient = getSetting('calcom_event_type');
        $followup = getSetting('calcom_followup_event_type');
        $enableWidget = getSetting('enable_booking_widget');
        ?>

        <div class="setting">
            <strong>Cal.com Username:</strong>
            <span class="value <?php echo empty($username) ? 'not-set' : ''; ?>">
                <?php echo $username ? htmlspecialchars($username) : 'NOT SET'; ?>
            </span>
        </div>

        <div class="setting">
            <strong>New Client Event Type:</strong>
            <span class="value <?php echo empty($newClient) ? 'not-set' : ''; ?>">
                <?php echo $newClient ? htmlspecialchars($newClient) : 'NOT SET (will use default: 30min)'; ?>
            </span>
        </div>

        <div class="setting">
            <strong>Follow-up Event Type:</strong>
            <span class="value <?php echo empty($followup) ? 'not-set' : ''; ?>">
                <?php echo $followup ? htmlspecialchars($followup) : 'NOT SET (will use default: follow-up)'; ?>
            </span>
        </div>

        <div class="setting">
            <strong>Enable Booking Widget:</strong>
            <span class="value">
                <?php echo $enableWidget ? 'YES' : 'NO'; ?>
            </span>
        </div>
    </div>

    <div class="card">
        <h2>Generated Cal.com URLs</h2>
        <p>These are the actual URLs that will be used in the Cal.com embed:</p>

        <div class="url">
            <strong>New Client URL:</strong><br>
            <?php
            $newClientUrl = htmlspecialchars($username) . '/' . htmlspecialchars($newClient ?: '30min');
            echo '<code>' . $newClientUrl . '</code>';
            ?>
        </div>

        <div class="url">
            <strong>Follow-up URL:</strong><br>
            <?php
            $followupUrl = htmlspecialchars($username) . '/' . htmlspecialchars($followup ?: 'follow-up');
            echo '<code>' . $followupUrl . '</code>';
            ?>
        </div>

        <div class="url">
            <strong>Full Cal.com Links:</strong><br>
            <a href="https://cal.com/<?php echo $newClientUrl; ?>" target="_blank">https://cal.com/<?php echo $newClientUrl; ?></a><br>
            <a href="https://cal.com/<?php echo $followupUrl; ?>" target="_blank">https://cal.com/<?php echo $followupUrl; ?></a>
        </div>
    </div>

    <div class="card">
        <h2>✅ Action Items</h2>

        <div class="check-item">
            <strong>1. Verify Cal.com Account</strong><br>
            Click the "Full Cal.com Links" above to verify these event types exist in your Cal.com account.
        </div>

        <div class="check-item">
            <strong>2. Check Event Type Slugs</strong><br>
            In your Cal.com dashboard, check that the event type slugs match EXACTLY what's shown above (case-sensitive).
        </div>

        <div class="check-item">
            <strong>3. Test Individual Events</strong><br>
            Try booking each event type individually through the links above to ensure they work.
        </div>

        <div class="check-item">
            <strong>4. Check for Typos</strong><br>
            The follow-up event slug is: <strong><?php echo htmlspecialchars($followup ?: 'follow-up'); ?></strong><br>
            Verify this matches your Cal.com event type name (check for extra spaces, hyphens vs underscores, etc.)
        </div>
    </div>

    <div class="card">
        <h2>Raw Database Query</h2>
        <p>Direct database values (for debugging):</p>
        <div class="raw-data">
<?php
$allSettings = db()->fetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'calcom%' ORDER BY setting_key");
echo "Settings table entries:\n\n";
foreach ($allSettings as $setting) {
    printf("%-35s = %s\n", $setting['setting_key'], $setting['setting_value'] ?: '(empty)');
}
?>
        </div>
    </div>

    <div class="card">
        <p><a href="<?php echo BASE_URL; ?>/booking">← Back to Booking Page</a> | <a href="<?php echo BASE_URL; ?>/admin/settings.php">Go to Settings</a></p>
    </div>
</body>
</html>
