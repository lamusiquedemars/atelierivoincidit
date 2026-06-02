<?php
require_once dirname(__DIR__, 2) . '/core/bootstrap.php';

logout_admin_user();

header('Location: ' . admin_url('login'));
exit;
