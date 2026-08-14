<?php
/**
 * Interactive Yangon weather and living-cost tools for the front page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_render_living_tools(): void {
	if ( ! is_front_page() && ! is_home() ) {
		return;
	}
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
		</div>
	</section>
	<?php
}

add_action( 'generate_before_main_content', 'livingtmw_render_living_tools', 5 );

