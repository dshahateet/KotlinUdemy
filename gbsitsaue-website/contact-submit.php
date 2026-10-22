<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('contact.php'));
}

$return = safe_return($_POST['return'] ?? '');

// Bots fill the hidden honeypot field: pretend success and drop the message.
if (!empty($_POST['website'])) {
    flash('contact_success', t('form_success'));
    redirect($return);
}

if (!csrf_valid()) {
    flash('contact_error', t('form_csrf'));
    redirect($return);
}

$data = [];
foreach (['first_name' => 100, 'last_name' => 100, 'company' => 150, 'email' => 150, 'phone' => 40, 'service' => 100, 'message' => 5000] as $k => $max) {
    $data[$k] = mb_substr(trim((string)($_POST[$k] ?? '')), 0, $max);
}

$valid = $data['first_name'] !== ''
    && $data['last_name'] !== ''
    && filter_var($data['email'], FILTER_VALIDATE_EMAIL)
    && preg_match('/^[0-9+()\-\s]{6,40}$/', $data['phone']);

if (!$valid) {
    $_SESSION['contact_old'] = $data;
    flash('contact_error', t('form_error'));
    redirect($return);
}

if (isset($_SESSION['last_contact']) && time() - $_SESSION['last_contact'] < 20) {
    $_SESSION['contact_old'] = $data;
    flash('contact_error', t('form_wait'));
    redirect($return);
}

q(
    'INSERT INTO messages (first_name, last_name, company, email, phone, service, message, lang, page, ip, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())',
    [
        $data['first_name'], $data['last_name'], $data['company'], $data['email'], $data['phone'],
        $data['service'], $data['message'], lang(), mb_substr($return, 0, 255), $_SERVER['REMOTE_ADDR'] ?? '',
    ]
);
$_SESSION['last_contact'] = time();

// Notify the info mailbox.
$clean   = fn(string $s): string => str_replace(["\r", "\n"], ' ', $s);
$subject = '=?UTF-8?B?' . base64_encode('New enquiry from ' . $clean($data['first_name'] . ' ' . $data['last_name']) . ' - ' . SITE_DOMAIN) . '?=';
$body    = "New contact form submission on " . SITE_DOMAIN . "\n\n"
    . "Name: {$data['first_name']} {$data['last_name']}\n"
    . "Company: {$data['company']}\n"
    . "Email: {$data['email']}\n"
    . "Phone: {$data['phone']}\n"
    . "Service: {$data['service']}\n"
    . "Language: " . lang() . "\n"
    . "Page: $return\n\n"
    . "Message:\n{$data['message']}\n";
$headers = implode("\r\n", [
    'From: ' . site_name() . ' <' . MAIL_FROM . '>',
    'Reply-To: ' . $clean($data['email']),
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
]);
@mail(INFO_EMAIL, $subject, $body, $headers);

flash('contact_success', t('form_success'));
redirect($return);
