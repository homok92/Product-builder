<?php
/**
 * Load the current dashboard module before the legacy MU-plugin bootstrap.
 *
 * The hosting PHP opcode cache can retain the old bootstrap briefly after an
 * SFTP deploy. Loading the versioned module first makes the existing
 * function_exists() guard select the current implementation immediately.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/livingtmw-custom/living-tools-v2.php';
