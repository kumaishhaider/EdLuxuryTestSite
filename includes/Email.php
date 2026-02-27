<?php
/**
 * Email Class - Edluxury
 * Handles email sending with SMTP support and beautiful templates
 * Uses PHPMailer if available, otherwise falls back to native PHP mail()
 */

class Email
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Send email (auto-detect best method)
     */
    public function send($to, $subject, $body, $fromEmail = null, $fromName = null)
    {
        $fromEmail = $fromEmail ?? FROM_EMAIL;
        $fromName = $fromName ?? FROM_NAME;
        $htmlBody = $this->wrapTemplate($body);

        if (SMTP_ENABLED) {
            $result = $this->sendSMTP($to, $subject, $htmlBody, $fromEmail, $fromName);
            if ($result)
                return true;
        }
        // Fallback to PHP mail()
        return $this->sendPHP($to, $subject, $htmlBody, $fromEmail, $fromName);
    }

    /**
     * Send via PHP mail() - basic fallback
     */
    private function sendPHP($to, $subject, $body, $fromEmail, $fromName)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        return @mail($to, $subject, $body, $headers);
    }

    /**
     * Send via SMTP using raw socket (no library dependency)
     * Supports Gmail TLS on port 587
     */
    private function sendSMTP($to, $subject, $body, $fromEmail, $fromName)
    {
        $smtp_host = SMTP_HOST;
        $smtp_port = SMTP_PORT;
        $smtp_user = SMTP_USERNAME;
        $smtp_pass = SMTP_PASSWORD;
        $smtp_enc = SMTP_ENCRYPTION; // 'tls'

        try {
            // Open socket
            $socket = @fsockopen("tcp://{$smtp_host}", $smtp_port, $errno, $errstr, 15);
            if (!$socket) {
                error_log("SMTP: Cannot connect to {$smtp_host}:{$smtp_port} - {$errstr}");
                return false;
            }
            stream_set_timeout($socket, 15);

            $read = fgets($socket, 512);
            if (substr($read, 0, 3) !== '220') {
                fclose($socket);
                return false;
            }

            // EHLO
            fwrite($socket, "EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n");
            $this->readResponse($socket);

            // STARTTLS
            if ($smtp_enc === 'tls') {
                fwrite($socket, "STARTTLS\r\n");
                $r = $this->readResponse($socket);
                if (substr($r, 0, 3) !== '220') {
                    fclose($socket);
                    return false;
                }

                // Enable TLS on the stream
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    error_log("SMTP: TLS handshake failed");
                    fclose($socket);
                    return false;
                }
                // Re-EHLO after TLS
                fwrite($socket, "EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n");
                $this->readResponse($socket);
            }

            // AUTH LOGIN
            fwrite($socket, "AUTH LOGIN\r\n");
            $this->readResponse($socket);
            fwrite($socket, base64_encode($smtp_user) . "\r\n");
            $this->readResponse($socket);
            fwrite($socket, base64_encode($smtp_pass) . "\r\n");
            $authResp = $this->readResponse($socket);
            if (substr($authResp, 0, 3) !== '235') {
                error_log("SMTP AUTH failed: " . $authResp);
                fwrite($socket, "QUIT\r\n");
                fclose($socket);
                return false;
            }

            // MAIL FROM
            fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
            $this->readResponse($socket);

            // RCPT TO
            fwrite($socket, "RCPT TO:<{$to}>\r\n");
            $this->readResponse($socket);

            // DATA
            fwrite($socket, "DATA\r\n");
            $this->readResponse($socket);

            // Build RFC 2822 message
            $boundary = md5(uniqid());
            $msg = "From: {$fromName} <{$fromEmail}>\r\n";
            $msg .= "To: {$to}\r\n";
            $msg .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $msg .= "MIME-Version: 1.0\r\n";
            $msg .= "Content-Type: text/html; charset=UTF-8\r\n";
            $msg .= "Content-Transfer-Encoding: base64\r\n";
            $msg .= "\r\n";
            $msg .= chunk_split(base64_encode($body));
            $msg .= "\r\n.\r\n";

            fwrite($socket, $msg);
            $dataResp = $this->readResponse($socket);

            fwrite($socket, "QUIT\r\n");
            fclose($socket);

            if (substr($dataResp, 0, 3) === '250') {
                return true;
            }
            error_log("SMTP DATA error: " . $dataResp);
            return false;

        } catch (Exception $e) {
            error_log("SMTP Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Read an SMTP response line
     */
    private function readResponse($socket)
    {
        $data = '';
        while ($line = fgets($socket, 512)) {
            $data .= $line;
            if ($line[3] === ' ')
                break; // End of multi-line response
        }
        return $data;
    }

    /**
     * Send email from template
     */
    public function sendFromTemplate($to, $templateKey, $variables = [])
    {
        $template = $this->db->fetchOne(
            "SELECT * FROM email_templates WHERE template_key = ?",
            [$templateKey]
        );

        if (!$template) {
            error_log("Email template not found: {$templateKey}");
            return false;
        }

        $subject = $this->replaceVariables($template['subject'], $variables);
        $body = $this->replaceVariables($template['body'], $variables);

        return $this->send($to, $subject, $body);
    }

    /**
     * Replace template variables
     */
    private function replaceVariables($content, $variables)
    {
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        return $content;
    }

    /**
     * Wrap email content in a beautiful HTML template
     */
    private function wrapTemplate($content)
    {
        $year = date('Y');
        $siteName = SITE_NAME;
        $siteUrl = SITE_URL;

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$siteName}</title>
    <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
    <style>
        body { margin:0; padding:0; background:#f5f5f5; font-family:'Segoe UI',Arial,sans-serif; }
        .email-wrapper { background:#f5f5f5; padding:30px 15px; }
        .email-card { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 30px rgba(0,0,0,0.08); }
        .email-header { background:linear-gradient(135deg,#0F3D3E 0%,#1a5f61 100%); padding:40px 30px; text-align:center; }
        .email-logo { font-size:32px; font-weight:900; color:#A69C63; letter-spacing:-1px; text-decoration:none; }
        .email-logo span { color:#ffffff; }
        .email-tagline { color:rgba(255,255,255,0.7); font-size:13px; margin-top:6px; letter-spacing:2px; text-transform:uppercase; }
        .email-body { padding:40px 35px; }
        .email-footer { background:#0a0a0a; padding:25px 30px; text-align:center; }
        .email-footer p { color:#666; font-size:12px; margin:4px 0; }
        .email-footer a { color:#A69C63; text-decoration:none; }
        .btn-email { display:inline-block; padding:14px 32px; background:linear-gradient(135deg,#0F3D3E,#1a5f61); color:#ffffff!important; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.5px; margin:16px 0; }
        .order-box { background:#f8f9fa; border:1px solid #e9ecef; border-radius:12px; padding:24px; margin:24px 0; }
        .order-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #e9ecef; font-size:14px; }
        .order-row:last-child { border-bottom:none; font-weight:700; font-size:16px; }
        .order-row .label { color:#666; }
        .order-row .value { color:#0F3D3E; font-weight:600; }
        .status-badge { display:inline-block; padding:6px 18px; border-radius:50px; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; }
        .badge-pending { background:#fff3cd; color:#856404; }
        .badge-success { background:#d1e7dd; color:#0a3622; }
        .divider { border:none; border-top:1px solid #e9ecef; margin:24px 0; }
        h2 { color:#0F3D3E; font-size:22px; font-weight:700; margin-bottom:8px; }
        p { color:#555; line-height:1.7; font-size:15px; margin:0 0 12px; }
        .highlight { color:#0F3D3E; font-weight:700; }
        .wa-btn { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:#25D366; color:#fff!important; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; margin:8px 0; }
        @media (max-width:480px) {
            .email-body { padding:24px 20px; }
            .email-header { padding:30px 20px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-card">
        <div class="email-header">
            <div class="email-logo"><span>Ed</span>luxury</div>
            <div class="email-tagline">Premium UAE Shopping</div>
        </div>
        <div class="email-body">
            {$content}
        </div>
        <div class="email-footer">
            <p>&copy; {$year} <strong style="color:#A69C63;">{$siteName}</strong>. All rights reserved.</p>
            <p>Dubai, United Arab Emirates</p>
            <p style="margin-top:10px;">
                <a href="{$siteUrl}">Shop Now</a>
                &nbsp;&bull;&nbsp;
                <a href="{$siteUrl}/track-order.php">Track Order</a>
                &nbsp;&bull;&nbsp;
                <a href="mailto:edluxury32@gmail.com">Contact Us</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
HTML;
    }

    /**
     * Send rich order confirmation email to customer + admin notification
     */
    public function sendOrderConfirmation($order)
    {
        $orderNum = htmlspecialchars($order['order_number']);
        $custEmail = htmlspecialchars($order['customer_email']);
        $trackUrl = SITE_URL . "/track-order.php?order={$orderNum}&email={$custEmail}";
        $custName = htmlspecialchars($order['customer_name']);
        $total = Helpers::formatPrice($order['total']);
        $method = strtoupper($order['payment_method'] ?? 'COD');
        $waLink = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode("Hi! I need help with order #{$order['order_number']}");
        $itemsHtml = '';

        if (!empty($order['items'])) {
            foreach ($order['items'] as $item) {
                $itemName = htmlspecialchars($item['product_name'] ?? $item['name'] ?? 'Product');
                $qty = (int) ($item['quantity'] ?? 1);
                $price = Helpers::formatPrice($item['total'] ?? $item['subtotal'] ?? 0);
                $itemsHtml .= "<div class='order-row'><span class='label'>{$itemName} &times; {$qty}</span><span class='value'>{$price}</span></div>";
            }
        }

        $customerBody = <<<HTML
<h2>🎉 Thank You for Your Order!</h2>
<p>Hi <span class="highlight">{$custName}</span>,</p>
<p>Your order has been successfully placed and is now being processed. We're excited to get it to you!</p>

<div class="order-box">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:1px;">Order Number</div>
            <div style="font-size:20px;font-weight:900;color:#0F3D3E;">#{$orderNum}</div>
        </div>
        <span class="status-badge badge-pending">Pending</span>
    </div>
    <hr class="divider" style="margin:8px 0 16px;">
    {$itemsHtml}
    <div class="order-row" style="margin-top:8px;">
        <span class="label" style="font-weight:700;">Total Amount</span>
        <span class="value" style="font-size:18px;color:#D88D5E;">{$total}</span>
    </div>
    <div class="order-row">
        <span class="label">Payment Method</span>
        <span class="value">{$method}</span>
    </div>
</div>

<p>📦 <strong>What happens next?</strong> Our team will process your order within 24 hours. You'll receive an update when it ships with tracking information.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{$trackUrl}" class="btn-email">Track My Order →</a>
</div>

<p>Need help? Chat with us on WhatsApp:</p>
<div style="text-align:center;">
    <a href="{$waLink}" class="wa-btn">
        💬 Chat on WhatsApp
    </a>
</div>
HTML;

        // Send to customer
        $this->send(
            $order['customer_email'],
            "Order Confirmed #{$orderNum} - Edluxury",
            $customerBody
        );

        // Also send admin notification email
        $this->sendAdminOrderNotification($order);

        // Send WhatsApp notification
        $this->sendWhatsAppNotification($order);

        return true;
    }

    /**
     * Send admin notification email when order is placed
     */
    public function sendAdminOrderNotification($order)
    {
        $orderNum = htmlspecialchars($order['order_number']);
        $custName = htmlspecialchars($order['customer_name']);
        $custEmail = htmlspecialchars($order['customer_email']);
        $custPhone = htmlspecialchars($order['customer_phone'] ?? 'N/A');
        $total = Helpers::formatPrice($order['total']);
        $method = strtoupper($order['payment_method'] ?? 'COD');
        $adminUrl = ADMIN_URL . '/order-details.php?id=' . ($order['id'] ?? '');
        $itemsHtml = '';

        if (!empty($order['items'])) {
            foreach ($order['items'] as $item) {
                $itemName = htmlspecialchars($item['product_name'] ?? $item['name'] ?? 'Product');
                $qty = (int) ($item['quantity'] ?? 1);
                $price = Helpers::formatPrice($item['total'] ?? $item['subtotal'] ?? 0);
                $itemsHtml .= "<div class='order-row'><span class='label'>{$itemName} &times; {$qty}</span><span class='value'>{$price}</span></div>";
            }
        }

        $shippingAddr = '';
        if (!empty($order['shipping_address'])) {
            $addr = json_decode($order['shipping_address'], true);
            if (is_array($addr)) {
                $shippingAddr = htmlspecialchars(
                    ($addr['address_line1'] ?? '') . ', ' .
                    ($addr['city'] ?? '') . ', ' .
                    ($addr['emirate'] ?? '') . ', ' .
                    ($addr['country'] ?? 'UAE')
                );
            } else {
                $shippingAddr = htmlspecialchars($order['shipping_address']);
            }
        }

        $adminBody = <<<HTML
<h2>🛒 New Order Received!</h2>
<p>A new order has been placed on Edluxury. Please review and process it.</p>

<div class="order-box">
    <div style="font-size:22px;font-weight:900;color:#0F3D3E;margin-bottom:16px;">Order #{$orderNum}</div>
    
    <div class="order-row"><span class="label">Customer</span><span class="value">{$custName}</span></div>
    <div class="order-row"><span class="label">Email</span><span class="value">{$custEmail}</span></div>
    <div class="order-row"><span class="label">Phone</span><span class="value">{$custPhone}</span></div>
    <div class="order-row"><span class="label">Address</span><span class="value">{$shippingAddr}</span></div>
    <hr class="divider" style="margin:8px 0;">
    {$itemsHtml}
    <div class="order-row" style="margin-top:8px;">
        <span class="label" style="font-weight:700;">Total Amount</span>
        <span class="value" style="font-size:18px;color:#D88D5E;">{$total}</span>
    </div>
    <div class="order-row">
        <span class="label">Payment Method</span>
        <span class="value">{$method}</span>
    </div>
</div>

<div style="text-align:center;margin:24px 0;">
    <a href="{$adminUrl}" class="btn-email">View in Admin Panel →</a>
</div>
HTML;

        return $this->send(
            ADMIN_EMAIL,
            "🛒 New Order #{$orderNum} - Action Required",
            $adminBody
        );
    }

    /**
     * Send order processing email
     */
    public function sendOrderProcessing($order)
    {
        $orderNum = htmlspecialchars($order['order_number']);
        $custName = htmlspecialchars($order['customer_name']);
        $custEmail = htmlspecialchars($order['customer_email']);
        $trackUrl = SITE_URL . "/track-order.php?order={$orderNum}&email={$custEmail}";

        $body = <<<HTML
<h2>⚙️ Your Order is Being Processed</h2>
<p>Hi <span class="highlight">{$custName}</span>,</p>
<p>Good news! Our team is now preparing your order <strong>#{$orderNum}</strong> for shipment.</p>

<div class="order-box" style="text-align:center;">
    <p class="mb-0">Current Status:</p>
    <span class="status-badge badge-success" style="background:#e3f2fd;color:#0d47a1;padding:10px 25px;font-size:16px;">PROCESSING</span>
</div>

<p>We are doing a final quality check and professional packaging to ensure everything is perfect. You will receive another update as soon as it is picked up by the courier.</p>

<div style="text-align:center;margin:24px 0;">
    <a href="{$trackUrl}" class="btn-email">Check Live Progress →</a>
</div>
HTML;

        return $this->send($order['customer_email'], "Order #{$orderNum} is now Processing! ⚙️", $body);
    }

    /**
     * Send WhatsApp order notification (logs URL, opens via redirect)
     * For server-side WhatsApp, we log the notification URL.
     * The actual message is sent via checkout redirect when method = whatsapp.
     * This creates a trackable log for admin reference.
     */
    public function sendWhatsAppNotification($order)
    {
        $orderNum = $order['order_number'];
        $custName = $order['customer_name'];
        $custPhone = $order['customer_phone'] ?? '';
        $total = Helpers::formatPrice($order['total']);
        $method = strtoupper($order['payment_method'] ?? 'COD');
        $itemsText = '';

        if (!empty($order['items'])) {
            foreach ($order['items'] as $item) {
                $name = $item['product_name'] ?? $item['name'] ?? 'Product';
                $qty = (int) ($item['quantity'] ?? 1);
                $price = Helpers::formatPrice($item['total'] ?? $item['subtotal'] ?? 0);
                $itemsText .= "• {$name} x{$qty} = {$price}\n";
            }
        }

        $addr = '';
        if (!empty($order['shipping_address'])) {
            $addrArr = json_decode($order['shipping_address'], true);
            if (is_array($addrArr)) {
                $addr = ($addrArr['address_line1'] ?? '') . ', ' .
                    ($addrArr['city'] ?? '') . ', ' .
                    ($addrArr['emirate'] ?? '') . ', UAE';
            } else {
                $addr = $order['shipping_address'];
            }
        }

        $message = "🛒 *NEW ORDER - #{$orderNum}*\n\n";
        $message .= "👤 *Customer:* {$custName}\n";
        $message .= "📱 *Phone:* {$custPhone}\n";
        $message .= "💳 *Payment:* {$method}\n\n";
        $message .= "📦 *Items:*\n{$itemsText}\n";
        $message .= "💰 *Total: {$total}*\n\n";
        $message .= "📍 *Ship To:* {$addr}\n\n";
        $message .= "👉 Manage: " . ADMIN_URL . "/order-details.php?id=" . ($order['id'] ?? '');

        $waUrl = "https://api.whatsapp.com/send/?phone=" . WHATSAPP_NUMBER . "&text=" . urlencode($message);

        // Log to file for reference (cannot auto-open URL server-side without integration)
        $logMsg = "[" . date('Y-m-d H:i:s') . "] New Order #{$orderNum} - WA Notification URL logged.\n";
        @file_put_contents(ROOT_PATH . '/wa_notifications.log', $logMsg, FILE_APPEND);

        // Store the WA URL in session for potential redirect
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['wa_notification_url'] = $waUrl;
        }

        return $waUrl;
    }

    /**
     * Send order shipped email
     */
    public function sendOrderShipped($order)
    {
        $orderNum = htmlspecialchars($order['order_number']);
        $custName = htmlspecialchars($order['customer_name']);
        $custEmail = htmlspecialchars($order['customer_email']);
        $trackingNum = htmlspecialchars($order['tracking_number'] ?? 'N/A');
        $trackUrl = SITE_URL . "/track-order.php?order={$orderNum}&email={$custEmail}";

        $body = <<<HTML
<h2>🚚 Your Order is On Its Way!</h2>
<p>Hi <span class="highlight">{$custName}</span>,</p>
<p>Great news! Your order <strong>#{$orderNum}</strong> has been shipped and is on its way to you.</p>

<div class="order-box" style="text-align:center;">
    <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Tracking Number</div>
    <div style="font-size:26px;font-weight:900;color:#0F3D3E;letter-spacing:2px;">{$trackingNum}</div>
    <div style="font-size:12px;color:#888;margin-top:6px;">Carrier: AJEX / Aramex</div>
</div>

<div style="text-align:center;margin:24px 0;">
    <a href="{$trackUrl}" class="btn-email">Track My Order →</a>
</div>

<p>Estimated delivery: <strong>2-5 business days</strong> depending on your location in UAE.</p>
HTML;

        return $this->send(
            $order['customer_email'],
            "Your Order #{$orderNum} Has Been Shipped! 🚚",
            $body
        );
    }

    /**
     * Send order delivered email
     */
    public function sendOrderDelivered($order)
    {
        $orderNum = htmlspecialchars($order['order_number']);
        $custName = htmlspecialchars($order['customer_name']);
        $custEmail = htmlspecialchars($order['customer_email']);
        $trackUrl = SITE_URL . "/track-order.php?order={$orderNum}&email={$custEmail}";
        $siteUrl = SITE_URL;

        $body = <<<HTML
<h2>✅ Order Delivered!</h2>
<p>Hi <span class="highlight">{$custName}</span>,</p>
<p>Your order <strong>#{$orderNum}</strong> has been successfully delivered. We hope you love your purchase!</p>

<p>🌟 <strong>Enjoying your order?</strong> Share your experience and help other shoppers discover Edluxury.</p>

<div style="text-align:center;margin:24px 0;">
    <a href="{$trackUrl}" class="btn-email">View Order History →</a>
    <div style="margin-top:10px;"><a href="{$siteUrl}/products.php" style="color:#0F3D3E;font-size:13px;text-decoration:none;">Shop New Collection →</a></div>
</div>
HTML;

        return $this->send(
            $order['customer_email'],
            "Your Edluxury Order #{$orderNum} Has Been Delivered! ✅",
            $body
        );
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset($email, $resetToken, $customerName)
    {
        $resetLink = SITE_URL . '/reset-password.php?token=' . $resetToken;
        $name = htmlspecialchars($customerName);

        $body = <<<HTML
<h2>🔐 Reset Your Password</h2>
<p>Hi <span class="highlight">{$name}</span>,</p>
<p>We received a request to reset your Edluxury account password. Click the button below to choose a new password:</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{$resetLink}" class="btn-email">Reset Password →</a>
</div>

<p style="font-size:13px;color:#888;">This link expires in 1 hour. If you didn't request a password reset, you can safely ignore this email.</p>
HTML;

        return $this->send($email, "Reset Your Edluxury Password", $body);
    }
}
