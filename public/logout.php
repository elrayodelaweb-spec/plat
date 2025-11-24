<?php
// public/logout.php
require_once __DIR__ . '/../includes/init.php';
session_destroy();
header('Location: /public/login.php');
exit;