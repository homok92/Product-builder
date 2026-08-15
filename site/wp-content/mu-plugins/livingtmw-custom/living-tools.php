<?php
/**
 * Interactive Yangon weather and living-cost tools for the front page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_default_market_rates(): array {
	return array(
		'usd_buy'   => 4310,
		'usd_sell'  => 4420,
		'krw_buy'   => 3.05,
		'krw_sell'  => 3.13,
		'updated_at' => '2026-08-15',
	);
}

function livingtmw_sanitize_market_rates( $input ): array {
	$defaults = livingtmw_default_market_rates();
	$input    = is_array( $input ) ? $input : array();

	return array(
		'usd_buy'    => max( 0, (float) ( $input['usd_buy'] ?? $defaults['usd_buy'] ) ),
		'usd_sell'   => max( 0, (float) ( $input['usd_sell'] ?? $defaults['usd_sell'] ) ),
		'krw_buy'    => max( 0, (float) ( $input['krw_buy'] ?? $defaults['krw_buy'] ) ),
		'krw_sell'   => max( 0, (float) ( $input['krw_sell'] ?? $defaults['krw_sell'] ) ),
		'updated_at' => current_time( 'Y-m-d H:i' ),
	);
}

function livingtmw_parse_market_number( string $value ): float {
	return (float) str_replace( ',', '', trim( $value ) );
}

function livingtmw_fetch_live_market_rates( bool $force = false ): array {
	$cache_key = 'livingtmw_egcurrency_rates';
	if ( ! $force ) {
		$cached = get_transient( $cache_key );
		if ( is_array( $cached ) ) {
			return $cached;
		}
	}

	$fallback = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	$response = wp_remote_get(
		'https://egcurrency.com/en/currency/MMK/blackMarket',
		array(
			'timeout'    => 12,
			'redirection' => 3,
			'user-agent' => 'LivingTMW/1.0 (+https://livingtmw.com/; exchange-rate widget)',
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	$html = wp_remote_retrieve_body( $response );
	$text = preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $html ) );
	$usd  = array();
	$krw  = array();

	preg_match( '/US Dollar\s*\(USD\)\s*([0-9,.]+)\s*([0-9,.]+)/iu', $text, $usd );
	preg_match( '/1000\s*South Korean Won\s*\(KRW\)\s*([0-9,.]+)\s*([0-9,.]+)/iu', $text, $krw );

	if ( empty( $usd[1] ) || empty( $usd[2] ) || empty( $krw[1] ) || empty( $krw[2] ) ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	$rates = array(
		'usd_buy'    => livingtmw_parse_market_number( $usd[1] ),
		'usd_sell'   => livingtmw_parse_market_number( $usd[2] ),
		'krw_buy'    => livingtmw_parse_market_number( $krw[1] ) / 1000,
		'krw_sell'   => livingtmw_parse_market_number( $krw[2] ) / 1000,
		'updated_at' => current_time( 'Y-m-d H:i' ),
		'status'     => 'live',
	);

	if ( $rates['usd_buy'] < 1000 || $rates['usd_buy'] > 10000 || $rates['krw_buy'] < 0.5 || $rates['krw_buy'] > 10 ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	if ( preg_match( '/Live exchange rate for Myanmar Kyat at Black Market,\s*([^<]+)/iu', $html, $source_time ) ) {
		$rates['source_updated_at'] = sanitize_text_field( trim( wp_strip_all_tags( $source_time[1] ) ) );
	}

	update_option( 'livingtmw_market_rates', $rates, false );
	set_transient( $cache_key, $rates, 15 * MINUTE_IN_SECONDS );
	return $rates;
}

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'livingtmw/v1',
			'/market-rate',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'callback'            => static function (): WP_REST_Response {
					$response = new WP_REST_Response( livingtmw_fetch_live_market_rates() );
					$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
					return $response;
				},
			)
		);
	}
);

add_action(
	'admin_init',
	static function (): void {
		register_setting(
			'livingtmw_market_rates_group',
			'livingtmw_market_rates',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'livingtmw_sanitize_market_rates',
				'default'           => livingtmw_default_market_rates(),
			)
		);
	}
);

add_action(
	'admin_menu',
	static function (): void {
		add_options_page( '현지 환율', '현지 환율', 'manage_options', 'livingtmw-market-rates', 'livingtmw_render_market_rates_page' );
	}
);

function livingtmw_render_market_rates_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$rates = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	?>
	<div class="wrap">
		<h1>현지 바깥환율</h1>
		<p>Myanmar Market Price 앱에 표시된 BUY·SELL 값을 입력하세요. 저장 시 홈페이지의 업데이트 시각도 함께 갱신됩니다.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'livingtmw_market_rates_group' ); ?>
			<table class="form-table" role="presentation"><tbody>
				<tr><th scope="row">USD BUY</th><td><input type="number" min="0" step="1" name="livingtmw_market_rates[usd_buy]" value="<?php echo esc_attr( $rates['usd_buy'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">USD SELL</th><td><input type="number" min="0" step="1" name="livingtmw_market_rates[usd_sell]" value="<?php echo esc_attr( $rates['usd_sell'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">KRW BUY</th><td><input type="number" min="0" step="0.01" name="livingtmw_market_rates[krw_buy]" value="<?php echo esc_attr( $rates['krw_buy'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">KRW SELL</th><td><input type="number" min="0" step="0.01" name="livingtmw_market_rates[krw_sell]" value="<?php echo esc_attr( $rates['krw_sell'] ); ?>"> MMK</td></tr>
			</tbody></table>
			<?php submit_button( '환율 업데이트' ); ?>
		</form>
	</div>
	<?php
}

function livingtmw_render_living_tools(): void {
	if ( ! is_front_page() && ! is_home() ) {
		return;
	}
	$rates = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	?>
	<section class="livingtmw-tools" aria-labelledby="livingtmw-tools-title">
		<div class="livingtmw-tools__intro">
			<p class="livingtmw-tools__eyebrow">오늘의 양곤 생활 도구</p>
			<h2 id="livingtmw-tools-title">날씨를 확인하고, 내 생활비를 계산해 보세요</h2>
			<p>실시간 예보와 양곤 거주자의 실제 지출 사례를 한곳에 모았습니다.</p>
		</div>

		<div class="livingtmw-tools__grid">
			<article class="livingtmw-weather" aria-labelledby="livingtmw-weather-title" data-latitude="16.8409" data-longitude="96.1735">
				<div class="livingtmw-tool-card__heading">
					<div>
						<p class="livingtmw-tool-card__kicker">실시간 업데이트</p>
						<h3 id="livingtmw-weather-title">오늘의 양곤 생활 난이도</h3>
					</div>
					<span class="livingtmw-weather__icon" aria-hidden="true">⛅</span>
				</div>
				<div class="livingtmw-weather__loading" role="status">양곤 날씨와 대기질을 불러오는 중입니다…</div>
				<div class="livingtmw-weather__content" hidden>
					<div class="livingtmw-weather__score-row">
						<strong class="livingtmw-weather__score"><span data-weather-score>--</span><small>/100</small></strong>
						<div><span class="livingtmw-weather__level" data-weather-level>확인 중</span><p data-weather-summary></p></div>
					</div>
					<dl class="livingtmw-weather__metrics">
						<div><dt>현재</dt><dd data-weather-temperature>--</dd></div>
						<div><dt>체감</dt><dd data-weather-apparent>--</dd></div>
						<div><dt>6시간 비</dt><dd data-weather-rain>--</dd></div>
						<div><dt>대기질</dt><dd data-weather-aqi>--</dd></div>
					</dl>
					<ul class="livingtmw-weather__tips" data-weather-tips></ul>
					<p class="livingtmw-tool-card__meta">양곤 중심부 기준 · 예보 모델은 실제 골목별 침수를 측정하지 않습니다. <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a></p>
				</div>
				<div class="livingtmw-weather__error" hidden>
					<p>현재 날씨를 불러오지 못했습니다. 잠시 뒤 다시 시도해 주세요.</p>
					<button type="button" data-weather-retry>다시 불러오기</button>
				</div>
			</article>

			<article class="livingtmw-cost" aria-labelledby="livingtmw-cost-title">
				<div class="livingtmw-tool-card__heading">
					<div>
						<p class="livingtmw-tool-card__kicker">실제 사례 기반</p>
						<h3 id="livingtmw-cost-title">양곤 월 생활비 계산기</h3>
					</div>
					<span class="livingtmw-cost__icon" aria-hidden="true">🧮</span>
				</div>
				<form class="livingtmw-cost__form">
					<label>가족 수<select name="family"><option value="1">1인</option><option value="2">2인</option><option value="3">3인</option><option value="4">4인 이상</option></select></label>
					<label>주거비<select name="housing"><option value="0">월세 제외</option><option value="2200000">실속형 · 약 USD 500</option><option value="3080000">여유형 · 약 USD 700</option></select></label>
					<label>전기·냉방<select name="electricity"><option value="300000">절약형</option><option value="1000000" selected>일반 사용</option><option value="1800000">상시 냉방</option></select></label>
					<label>교통수단<select name="transport"><option value="400000">대중교통·가끔 Grab</option><option value="900000">Grab 중심</option><option value="1200000">자가용 단거리</option><option value="2000000">H-1 장거리 통근</option></select></label>
				</form>
				<div class="livingtmw-cost__result" aria-live="polite">
					<p>예상 월 생활비</p>
					<strong><span data-cost-low>--</span>~<span data-cost-high>--</span> MMK</strong>
					<ul data-cost-breakdown></ul>
				</div>
				<p class="livingtmw-tool-card__meta">작성자의 2026년 8월 체감값을 사용한 참고 계산입니다. 환율·가족의 소비 습관·건물 요금에 따라 달라집니다.</p>
			</article>

			<article class="livingtmw-market-rate" aria-labelledby="livingtmw-market-title">
				<div class="livingtmw-tool-card__heading">
					<div>
						<p class="livingtmw-tool-card__kicker">15분마다 자동 확인</p>
						<h3 id="livingtmw-market-title">오늘의 바깥환율</h3>
					</div>
					<span class="livingtmw-market-rate__icon" aria-hidden="true">💱</span>
				</div>
				<div class="livingtmw-market-rate__rows">
					<div><strong>1 USD</strong><span><small>BUY</small><b data-market-usd-buy><?php echo esc_html( number_format( (float) $rates['usd_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-usd-sell><?php echo esc_html( number_format( (float) $rates['usd_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
					<div><strong>1원</strong><span><small>BUY</small><b data-market-krw-buy><?php echo esc_html( number_format( (float) $rates['krw_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-krw-sell><?php echo esc_html( number_format( (float) $rates['krw_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
				</div>
				<p><span data-market-status>최신값 확인 중</span> · EGCurrency Black Market 기준 비공식 시장 참고값이며 실제 거래가는 달라질 수 있습니다. <a href="https://egcurrency.com/en/currency/MMK/blackMarket" target="_blank" rel="noopener noreferrer">출처</a></p>
			</article>
		</div>
	</section>
	<?php
}

add_action( 'generate_before_main_content', 'livingtmw_render_living_tools', 5 );
