<?php

require __DIR__ . '/auth.php';

// Ensure user is logged in at all.
if (!current_user_id()) {
    header('Location: login.php');
    exit;
}

