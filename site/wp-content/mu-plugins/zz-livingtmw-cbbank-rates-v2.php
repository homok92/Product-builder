<?php
/** Display CB Bank's official USD and EUR counter rates on public pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function livingtmw_get_cbbank_rates(): array {
	$cached = get_transient( 'livingtmw_cbbank_rates_v1' );
	if ( is_array( $cached ) ) { return $cached; }
	$last_good = get_option( 'livingtmw_cbbank_last_good', array(
		'usd_buy' => 3658, 'usd_sell' => 3668, 'eur_buy' => 4276, 'eur_sell' => 4279,
		'updated' => '25/08/2026 - 09:30 AM', 'status' => 'cached',
	) );
	$response = wp_remote_get( 'https://www.cbbank.com.mm/en', array(
		'timeout' => 12, 'redirection' => 3,
		'user-agent' => 'LivingTMW/1.0 (+https://livingtmw.com/; official-rate-widget)',
	) );
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$last_good['status'] = 'cached'; return $last_good;
	}
	$text = html_entity_decode( wp_strip_all_tags( wp_remote_retrieve_body( $response ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = preg_replace( '/\s+/u', ' ', $text );
	$usd = array(); $eur = array(); $updated = array();
	preg_match( '/\bUSD\s+([0-9,.]+)\s+([0-9,.]+)/iu', $text, $usd );
	preg_match( '/\bEUR\s+([0-9,.]+)\s+([0-9,.]+)/iu', $text, $eur );
	preg_match( '/Updated\s+on\s+(.+?)(?=\s+Online Trading Platform|$)/iu', $text, $updated );
	if ( empty( $usd[1] ) || empty( $usd[2] ) || empty( $eur[1] ) || empty( $eur[2] ) ) {
		$last_good['status'] = 'cached'; return $last_good;
	}
	$rates = array(
		'usd_buy' => (float) str_replace( ',', '', $usd[1] ), 'usd_sell' => (float) str_replace( ',', '', $usd[2] ),
		'eur_buy' => (float) str_replace( ',', '', $eur[1] ), 'eur_sell' => (float) str_replace( ',', '', $eur[2] ),
		'updated' => ! empty( $updated[1] ) ? sanitize_text_field( trim( $updated[1] ) ) : '', 'status' => 'live',
	);
	if ( $rates['usd_buy'] < 1000 || $rates['usd_buy'] > 10000 || $rates['eur_buy'] < 1000 || $rates['eur_buy'] > 15000 ) {
		$last_good['status'] = 'cached'; return $last_good;
	}
	update_option( 'livingtmw_cbbank_last_good', $rates, false );
	set_transient( 'livingtmw_cbbank_rates_v1', $rates, 30 * MINUTE_IN_SECONDS );
	return $rates;
}

add_action( 'template_redirect', static function (): void {
	if ( is_admin() || wp_doing_ajax() || is_feed() || is_robots() ) { return; }
	$rates = livingtmw_get_cbbank_rates();
	ob_start( static function ( string $html ) use ( $rates ): string {
		$updated = $rates['updated'] ? ' · 기준 ' . esc_html( $rates['updated'] ) : '';
		$status = 'cached' === $rates['status'] ? ' · 마지막 확인값' : '';
		$card = sprintf(
			'<article class="livingtmw-market-rate" aria-label="오늘의 바깥환율"><div class="livingtmw-tool-card__heading"><div><p class="livingtmw-tool-card__kicker">CB Bank 공식 고시</p><h3>오늘의 바깥환율</h3></div><span class="livingtmw-market-rate__icon" aria-hidden="true">💱</span></div><div class="livingtmw-market-rate__rows"><div><strong>1 USD</strong><span><small>BUY</small><b>%s</b><em>MMK</em></span><span><small>SELL</small><b>%s</b><em>MMK</em></span></div><div><strong>1 EUR</strong><span><small>BUY</small><b>%s</b><em>MMK</em></span><span><small>SELL</small><b>%s</b><em>MMK</em></span></div></div><p>Exchange Counter Rate%s%s · 실제 거래 전 은행 고시를 다시 확인하세요. <a href="https://www.cbbank.com.mm/en" target="_blank" rel="noopener noreferrer">CB Bank 공식 페이지</a></p></article>',
			number_format_i18n( $rates['usd_buy'], 0 ), number_format_i18n( $rates['usd_sell'], 0 ),
			number_format_i18n( $rates['eur_buy'], 0 ), number_format_i18n( $rates['eur_sell'], 0 ), $updated, $status
		);
		return (string) preg_replace( '~<article class="livingtmw-market-rate[^\"]*"[^>]*>.*?</article>~su', $card, $html );
	} );
}, 0 );
