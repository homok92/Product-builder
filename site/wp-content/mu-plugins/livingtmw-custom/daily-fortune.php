<?php
/**
 * Privacy-friendly, entertainment-only daily fortune page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_ensure_fortune_page(): void {
	$page = get_page_by_path( 'today-fortune', OBJECT, 'page' );
	if ( $page ) {
		if ( '2' !== get_option( 'livingtmw_fortune_page_version' ) ) {
			wp_update_post(
				array(
					'ID'           => (int) $page->ID,
					'post_content' => '[livingtmw_daily_fortune]',
				)
			);
			update_option( 'livingtmw_fortune_page_version', '2', false );
		}
		return;
	}

	$post_id = wp_insert_post(
		array(
			'post_title'     => '오늘의 운세',
			'post_name'      => 'today-fortune',
			'post_content'   => '[livingtmw_daily_fortune]',
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'comment_status' => 'closed',
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_option( 'livingtmw_fortune_page_version', '2', false );
	}
}
add_action( 'init', 'livingtmw_ensure_fortune_page', 25 );

function livingtmw_render_daily_fortune(): string {
	$language   = isset( $_GET['lang'] ) && 'my' === sanitize_key( wp_unslash( $_GET['lang'] ) ) ? 'my' : 'ko';
	$korean_url = remove_query_arg( 'lang' );
	$myanmar_url = add_query_arg( 'lang', 'my' );
	ob_start();
	?>
	<section class="livingtmw-fortune" data-daily-fortune data-language="<?php echo esc_attr( $language ); ?>">
		<nav class="livingtmw-fortune__language" aria-label="<?php echo 'my' === $language ? 'ဘာသာစကား ရွေးချယ်ရန်' : '운세 페이지 언어 선택'; ?>">
			<span><?php echo 'my' === $language ? 'ဘာသာစကား' : '언어'; ?></span>
			<a href="<?php echo esc_url( $korean_url ); ?>" lang="ko"<?php echo 'ko' === $language ? ' aria-current="page"' : ''; ?>>한국어</a>
			<a href="<?php echo esc_url( $myanmar_url ); ?>" lang="my"<?php echo 'my' === $language ? ' aria-current="page"' : ''; ?>>မြန်မာ</a>
		</nav>
		<header class="livingtmw-fortune__intro">
			<p class="livingtmw-fortune__eyebrow">오늘부터 이달까지 자세히 보는 운세</p>
			<h2>생년월일로 보는 나의 생활 운세</h2>
			<p>양력·음력 생년월일 또는 나의 띠를 선택하면 총운, 애정운, 금전운, 직장운, 학업·성적운, 건강운을 자세히 보여드립니다. 오늘·내일·이번 주·이번 달의 흐름을 자유롭게 바꿔 볼 수 있으며 입력 정보는 서버로 전송하거나 저장하지 않습니다.</p>
		</header>

		<div class="livingtmw-fortune__layout">
			<form class="livingtmw-fortune__form" data-fortune-form>
				<div class="livingtmw-fortune__settings">
					<div class="livingtmw-fortune__setting-row" role="group" aria-label="운세 기준">
						<span class="livingtmw-fortune__setting-label">운세 기준</span>
						<div class="livingtmw-fortune__choices livingtmw-fortune__choices--method">
							<label><input type="radio" name="method" value="birth" checked><span>생년월일</span></label>
							<label><input type="radio" name="method" value="zodiac"><span>띠 직접 선택</span></label>
						</div>
					</div>
					<div class="livingtmw-fortune__setting-row" role="group" aria-label="달력 구분" data-calendar-fields>
						<span class="livingtmw-fortune__setting-label">달력 구분</span>
						<div class="livingtmw-fortune__choices">
							<label><input type="radio" name="calendar" value="solar" checked><span>양력</span></label>
							<label><input type="radio" name="calendar" value="lunar"><span>음력</span></label>
						</div>
					</div>
				</div>

				<div class="livingtmw-fortune__birth" data-birth-fields>
					<div class="livingtmw-fortune__date-fields">
						<label>태어난 해<input type="number" name="birth_year" inputmode="numeric" min="1930" max="2026" placeholder="예: 1990" required></label>
						<label>월<input type="number" name="birth_month" inputmode="numeric" min="1" max="12" placeholder="1~12" required></label>
						<label>일<input type="number" name="birth_day" inputmode="numeric" min="1" max="31" placeholder="1~31" required></label>
					</div>
					<p class="livingtmw-fortune__field-note">양력 생일은 음력 새해 경계를 반영해 띠를 계산합니다. 음력은 음력 달력에 적힌 연·월·일을 입력하세요.</p>
				</div>

				<div class="livingtmw-fortune__zodiac" data-zodiac-field hidden>
					<label for="livingtmw-zodiac">나의 띠</label>
					<select id="livingtmw-zodiac" name="zodiac">
						<option value="">띠를 선택하세요</option>
						<option value="rat">쥐띠</option><option value="ox">소띠</option><option value="tiger">호랑이띠</option><option value="rabbit">토끼띠</option>
						<option value="dragon">용띠</option><option value="snake">뱀띠</option><option value="horse">말띠</option><option value="goat">양띠</option>
						<option value="monkey">원숭이띠</option><option value="rooster">닭띠</option><option value="dog">개띠</option><option value="pig">돼지띠</option>
					</select>
				</div>

				<button type="submit">내 운세 자세히 보기</button>
				<p class="livingtmw-fortune__privacy">🔒 생년월일과 선택값은 이 브라우저에서만 계산되며 저장·전송되지 않습니다.</p>
			</form>

			<div class="livingtmw-fortune__result" data-fortune-result aria-live="polite">
				<div class="livingtmw-fortune__empty">
					<span aria-hidden="true">☀️</span>
					<strong>생년월일 또는 띠를 선택해 자세한 운세를 확인하세요</strong>
					<p>오늘·내일·이번 주·이번 달의 여섯 가지 운세를 볼 수 있습니다.</p>
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
				<p>입력한 생년월일에서 계산한 띠 또는 직접 선택한 띠와 양곤 현지 날짜를 조합해 사이트가 직접 작성한 문장 목록에서 기간별·분야별 메시지를 정합니다. 생년월일은 결과의 조합값을 만드는 데만 사용되고 네트워크로 전송되지 않습니다.</p>
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
			<details><summary>생년월일을 입력하지 않아도 되나요?</summary><p>네. 운세 기준에서 ‘띠 직접 선택’을 고르면 생년월일 없이 이용할 수 있습니다.</p></details>
			<details><summary>양력과 음력은 어떻게 구분하나요?</summary><p>주민등록이나 일반 달력에 표시된 날짜를 사용하면 보통 양력입니다. 가족이 음력 생일을 따로 기념한다면 음력을 선택하고 음력 달력의 날짜를 입력하세요.</p></details>
			<details><summary>왜 어제와 결과가 다른가요?</summary><p>오늘 날짜가 계산에 포함되므로 날짜가 바뀌면 결과도 바뀝니다. 같은 날에는 새로고침해도 같은 선택에 동일한 결과가 표시됩니다.</p></details>
			<details><summary>결과를 믿고 중요한 결정을 해도 되나요?</summary><p>아니요. 이 기능은 오락용입니다. 건강, 재산, 법률 및 신변 안전과 관련된 결정에는 공식 정보와 자격을 갖춘 전문가의 도움을 이용하세요.</p></details>
		</section>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'livingtmw_daily_fortune', 'livingtmw_render_daily_fortune' );
