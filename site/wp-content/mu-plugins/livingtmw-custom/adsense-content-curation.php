<?php
/**
 * Keep the public archive focused on first-hand, non-overlapping field guides.
 *
 * Retired posts are moved to draft instead of deleted, so the editorial team
 * can restore or rewrite them later. Their old public URLs redirect to the
 * strongest surviving guide for the same reader intent.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Legacy posts that duplicate a stronger guide or remain mainly generic. */
function livingtmw_retired_post_targets(): array {
	return array(
		23 => 21, // General exchange advice -> the first-hand KBZ/KBZPay guide.
		27 => 'myanmar-plaza-market-place-family-shopping',
		31 => 'myanmar-plaza-market-place-family-shopping',
		33 => 35, // Generic driving advice -> the measured H-1 commute costs.
		43 => 'myanmar-life-guide',
		47 => 8,  // Repetitive Korea comparison -> the itemised Yangon budget.
	);
}

/**
 * Apply the curation once per revision. This is deliberately reversible: no
 * post content, comments, media, or metadata are deleted.
 */
function livingtmw_apply_adsense_content_curation(): void {
	$revision = '2026-08-24-first-hand-v1';
	if ( $revision === get_option( 'livingtmw_adsense_curation_revision' ) ) {
		return;
	}

	foreach ( array_keys( livingtmw_retired_post_targets() ) as $post_id ) {
		$post = get_post( (int) $post_id );
		if ( ! ( $post instanceof WP_Post ) || 'post' !== $post->post_type ) {
			continue;
		}
		if ( in_array( $post->post_status, array( 'publish', 'future' ), true ) ) {
			wp_update_post(
				array(
					'ID'          => (int) $post_id,
					'post_status' => 'draft',
				)
			);
		}
	}

	update_option( 'livingtmw_adsense_curation_revision', $revision, false );
}
add_action( 'init', 'livingtmw_apply_adsense_content_curation', 45 );

/** Resolve a post ID or slug to a durable public destination. */
function livingtmw_curation_target_url( $target ): string {
	if ( is_int( $target ) ) {
		$url = get_permalink( $target );
		return $url ? $url : home_url( '/' );
	}

	$post = get_page_by_path( (string) $target, OBJECT, array( 'post', 'page' ) );
	if ( $post instanceof WP_Post && 'publish' === $post->post_status ) {
		$url = get_permalink( $post );
		return $url ? $url : home_url( '/' );
	}

	return home_url( '/' );
}

/** Preserve bookmarks and existing search results after a post is retired. */
function livingtmw_redirect_retired_posts(): void {
	if ( ! is_404() ) {
		return;
	}

	$path = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ), '/' );
	if ( '' === $path ) {
		return;
	}

	foreach ( livingtmw_retired_post_targets() as $post_id => $target ) {
		$slug = (string) get_post_field( 'post_name', (int) $post_id );
		if ( '' !== $slug && rawurldecode( $path ) === rawurldecode( $slug ) ) {
			wp_safe_redirect( livingtmw_curation_target_url( $target ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'livingtmw_redirect_retired_posts', 2 );

/** Use an editorial byline on the public site without changing admin accounts. */
function livingtmw_public_author_name( string $display_name ): string {
	return is_admin() ? $display_name : '내일의 생활 편집팀';
}
add_filter( 'the_author', 'livingtmw_public_author_name' );
add_filter( 'get_the_author_display_name', 'livingtmw_public_author_name' );

