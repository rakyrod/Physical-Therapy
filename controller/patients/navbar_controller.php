<?php
// Session handling code remains the same
if (!isset($_SESSION['session_already_started'])) {
    if (function_exists('session_status')) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    } else {
        if (session_id() === '') {
            session_start();
        }
    }
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>