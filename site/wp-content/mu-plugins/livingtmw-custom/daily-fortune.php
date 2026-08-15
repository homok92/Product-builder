<?php
/**
 * Privacy-friendly, entertainment-only daily fortune page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_ensure_fortune_page(): void {
	if ( get_page_by_path( 'today-fortune', OBJECT, 'page' ) ) {
		return;
	}

	wp_insert_post(
		array(
			'post_title'     => '오늘의 운세',
			'post_name'      => 'today-fortune',
			'post_content'   => '[livingtmw_daily_fortune]',
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'comment_status' => 'closed',
		)
	);
}
add_action( 'init', 'livingtmw_ensure_fortune_page', 25 );

function livingtmw_render_daily_fortune(): string {
	ob_start();
	?>
	<section class="livingtmw-fortune" data-daily-fortune>
		<header class="livingtmw-fortune__intro">
			<p class="livingtmw-fortune__eyebrow">매일 달라지는 가벼운 읽을거리</p>
			<h2>12띠로 보는 오늘의 흐름</h2>
			<p>띠와 오늘 가장 궁금한 분야를 선택하면 하루를 정리하는 데 도움이 되는 짧은 메시지를 보여드립니다. 생년월일이나 이름은 받지 않으며 선택 정보는 서버로 전송하거나 저장하지 않습니다.</p>
		</header>

		<div class="livingtmw-fortune__layout">
			<form class="livingtmw-fortune__form" data-fortune-form>
				<label for="livingtmw-zodiac">나의 띠</label>
				<select id="livingtmw-zodiac" name="zodiac" required>
					<option value="">띠를 선택하세요</option>
					<option value="rat">쥐띠</option><option value="ox">소띠</option><option value="tiger">호랑이띠</option><option value="rabbit">토끼띠</option>
					<option value="dragon">용띠</option><option value="snake">뱀띠</option><option value="horse">말띠</option><option value="goat">양띠</option>
					<option value="monkey">원숭이띠</option><option value="rooster">닭띠</option><option value="dog">개띠</option><option value="pig">돼지띠</option>
				</select>

				<fieldset>
					<legend>오늘의 관심 분야</legend>
					<div class="livingtmw-fortune__choices">
						<label><input type="radio" name="focus" value="balance" checked><span>종합</span></label>
						<label><input type="radio" name="focus" value="work"><span>일·학업</span></label>
						<label><input type="radio" name="focus" value="money"><span>금전</span></label>
						<label><input type="radio" name="focus" value="relationship"><span>관계</span></label>
					</div>
				</fieldset>

				<button type="submit">오늘의 운세 보기</button>
				<p class="livingtmw-fortune__privacy">🔒 선택값은 이 브라우저에서만 계산되며 저장되지 않습니다.</p>
			</form>

			<div class="livingtmw-fortune__result" data-fortune-result aria-live="polite">
				<div class="livingtmw-fortune__empty">
					<span aria-hidden="true">☀️</span>
					<strong>띠를 선택해 오늘의 메시지를 확인하세요</strong>
					<p>결과는 매일 자정 이후 달라집니다.</p>
				</div>
			</div>
		</div>

		<aside class="livingtmw-fortune__notice">
			<strong>운세는 오락과 자기 점검을 위한 참고 콘텐츠입니다.</strong>
			<p>미래를 예측하거나 결과를 보장하지 않습니다. 투자·대출·복권 구매, 진료·복약, 계약과 같은 중요한 결정은 운세가 아니라 검증된 정보와 전문가의 조언을 기준으로 판단하세요.</p>
		</aside>

		<div class="livingtmw-fortune__guide">
			<section>
				<h2>결과는 어떻게 만들어지나요?</h2>
				<p>선택한 띠, 관심 분야와 양곤 현지 날짜를 조합해 사이트가 직접 작성한 문장 목록에서 오늘의 메시지를 정합니다. 같은 날짜에 같은 항목을 선택하면 같은 결과가 나오며, 이용자를 추적하거나 실제 개인정보를 분석하는 방식이 아닙니다.</p>
				<p>점수는 하루의 좋고 나쁨을 객관적으로 측정한 수치가 아닙니다. 메시지를 읽기 쉽게 보여주기 위한 표현이므로 낮은 점수가 나오더라도 걱정하거나 중요한 일정을 변경할 필요가 없습니다.</p>
			</section>
			<section>
				<h2>오늘의 운세를 건강하게 활용하는 법</h2>
				<ul>
					<li>마음에 드는 조언 한 가지만 오늘의 작은 행동으로 옮겨보세요.</li>
					<li>불안감을 주는 문장은 예언으로 받아들이지 말고 가볍게 넘기세요.</li>
					<li>금전운은 소비 습관을 점검하는 계기로만 활용하세요.</li>
					<li>관계운보다 상대방과의 직접적인 대화와 동의를 우선하세요.</li>
				</ul>
			</section>
		</div>

		<section class="livingtmw-fortune__faq">
			<h2>자주 묻는 질문</h2>
			<details><summary>생년월일을 입력하지 않아도 되나요?</summary><p>네. 개인정보를 최소화하기 위해 띠만 직접 선택하도록 만들었습니다. 띠를 모르더라도 생년월일을 사이트에 입력할 필요 없이 외부 달력에서 확인한 뒤 선택할 수 있습니다.</p></details>
			<details><summary>왜 어제와 결과가 다른가요?</summary><p>오늘 날짜가 계산에 포함되므로 날짜가 바뀌면 결과도 바뀝니다. 같은 날에는 새로고침해도 같은 선택에 동일한 결과가 표시됩니다.</p></details>
			<details><summary>결과를 믿고 중요한 결정을 해도 되나요?</summary><p>아니요. 이 기능은 오락용입니다. 건강, 재산, 법률 및 신변 안전과 관련된 결정에는 공식 정보와 자격을 갖춘 전문가의 도움을 이용하세요.</p></details>
		</section>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'livingtmw_daily_fortune', 'livingtmw_render_daily_fortune' );
