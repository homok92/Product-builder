<?php
/**
 * Keep the editorial taxonomy focused on Korean residents using Yangon services.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const LIVINGTMW_PRIMARY_CATEGORY_NAME = '양곤 한국인의 현지생활 서비스';
const LIVINGTMW_PRIMARY_CATEGORY_SLUG = 'yangon-korean-local-services';

/** Consolidate published and scheduled posts into one durable category. */
function livingtmw_consolidate_categories(): void {
	$revision = '2026-08-yangon-services-v2';
	if ( $revision === get_option( 'livingtmw_category_revision' ) ) {
		return;
	}

	$term = term_exists( LIVINGTMW_PRIMARY_CATEGORY_SLUG, 'category' );
	if ( ! $term ) {
		$term = wp_insert_term(
			LIVINGTMW_PRIMARY_CATEGORY_NAME,
			'category',
			array(
				'slug'        => LIVINGTMW_PRIMARY_CATEGORY_SLUG,
				'description' => '양곤에서 약 1년간 생활한 한국인이 Grab, KBZPay, ATOM, APN·MPT, 주거·전기·병원 등 현지 서비스를 직접 이용하며 확인한 경험과 주의사항을 정리합니다.',
			)
		);
	}
	if ( is_wp_error( $term ) ) {
		return;
	}

	$term_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );
	$post_ids = get_posts(
		array(
			'post_type'        => 'post',
			'post_status'      => array( 'publish', 'future' ),
			'numberposts'      => -1,
			'fields'           => 'ids',
			'suppress_filters' => true,
		)
	);
	foreach ( $post_ids as $post_id ) {
		wp_set_post_terms( (int) $post_id, array( $term_id ), 'category', false );
	}

	update_option( 'default_category', $term_id );
	$categories = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
	if ( ! is_wp_error( $categories ) ) {
		foreach ( $categories as $category ) {
			if ( $term_id !== (int) $category->term_id ) {
				wp_delete_term( (int) $category->term_id, 'category' );
			}
		}
	}

	$focused_titles = array(
		21 => '양곤 KBZPay 실제 사용 후기: QR 결제와 인터넷 주의사항',
		39 => '양곤 24시간 전기 공급과 정전 대비: 실제 변화',
		41 => '양곤 GrabFood 이용 후기: 저녁 7시 이후 배달과 생활권',
		43 => '양곤 정착 첫 주: 꼭 연결해야 할 현지 서비스',
		45 => '양곤 병원 이용 후기: CLL·Aryu 진료비와 준비물',
		47 => '양곤 현지 서비스 생활비: 한국과 비교하기',
	);
	foreach ( $focused_titles as $post_id => $title ) {
		$post = get_post( $post_id );
		if ( $post instanceof WP_Post && in_array( $post->post_status, array( 'publish', 'future' ), true ) ) {
			wp_update_post( array( 'ID' => $post_id, 'post_title' => $title ) );
		}
	}

	update_option( 'livingtmw_category_revision', $revision, false );
}
add_action( 'init', 'livingtmw_consolidate_categories', 30 );

/** Preserve old category links as permanent redirects to the single archive. */
function livingtmw_redirect_legacy_categories(): void {
	if ( ! is_404() || ! str_starts_with( (string) wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), '/category/' ) ) {
		return;
	}
	$term = get_term_by( 'slug', LIVINGTMW_PRIMARY_CATEGORY_SLUG, 'category' );
	if ( $term instanceof WP_Term ) {
		wp_safe_redirect( get_term_link( $term ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'livingtmw_redirect_legacy_categories', 1 );

/** Retire obsolete tag feed URLs instead of serving duplicate 200 responses. */
function livingtmw_retire_tag_feeds(): void {
	if ( ! is_tag() || ! is_feed() ) {
		return;
	}
	status_header( 410 );
	nocache_headers();
	header( 'Content-Type: text/plain; charset=UTF-8' );
	echo '이 태그 피드는 더 이상 제공되지 않습니다.';
	exit;
}
add_action( 'template_redirect', 'livingtmw_retire_tag_feeds', 0 );

/** Consolidate obsolete tag archives into the single editorial category. */
function livingtmw_redirect_tag_archives(): void {
	if ( ! is_tag() || is_feed() ) {
		return;
	}
	$term = get_term_by( 'slug', LIVINGTMW_PRIMARY_CATEGORY_SLUG, 'category' );
	if ( $term instanceof WP_Term ) {
		wp_safe_redirect( get_term_link( $term ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'livingtmw_redirect_tag_archives', 1 );
