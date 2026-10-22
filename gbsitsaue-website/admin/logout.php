<?php
require __DIR__ . '/_init.php';

$_SESSION = [];
session_destroy();
redirect(url('admin/index.php'));
