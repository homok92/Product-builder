<?php
/**
 * Load the audited dashboard module before any stale host opcode can reuse the
 * previous public exchange-rate implementation.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$refresh_targets = array(
	__DIR__ . '/livingtmw-custom.php',
	__DIR__ . '/livingtmw-custom/living-tools-v2.php',
	__DIR__ . '/livingtmw-custom/living-tools-v3.php',
);

if ( function_exists( 'opcache_invalidate' ) ) {
	foreach ( $refresh_targets as $refresh_target ) {
		if ( is_file( $refresh_target ) ) {
			opcache_invalidate( $refresh_target, true );
		}
	}
}

require_once __DIR__ . '/livingtmw-custom/living-tools-v3.php';
