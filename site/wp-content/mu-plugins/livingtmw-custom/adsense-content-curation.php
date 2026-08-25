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
		8  => 'myanmar-life-guide',
		13 => 11, // Rent overview repeats the stronger sale and housing record.
		23 => 21, // General exchange advice -> the first-hand KBZ/KBZPay guide.
		27 => 'myanmar-plaza-market-place-family-shopping',
		29 => 'myanmar-plaza-market-place-family-shopping',
		31 => 'myanmar-plaza-market-place-family-shopping',
		33 => 35, // Generic driving advice -> the measured H-1 commute costs.
		39 => 15, // Backup-power overview repeats the metered electricity guide.
		43 => 'myanmar-life-guide',
		45 => 'yangon-korean-family-medicine-kit-cll',
		47 => 'myanmar-life-guide',
	);
}

/**
 * Apply the curation once per revision. This is deliberately reversible: no
 * post content, comments, media, or metadata are deleted.
 */
function livingtmw_apply_adsense_content_curation(): void {
	$revision = '2026-08-24-first-hand-v3';
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

	$titles_and_excerpts = array(
		11 => array( '양곤 Inno City·Thiri Condo 거주와 매도 경험: 집 구하기 기준', 'Inno City와 Thiri Condo의 전력·수질·이동 조건, 약 1년이 걸린 실제 매도 경험과 월세를 선택한 이유를 비교합니다.' ),
		15 => array( '양곤 전기요금 실제 청구: Inno City 24시간 전력과 900 MMK/kWh', 'Inno City의 계량 사용량과 청구 경험을 2026년 공식 고시와 대조해 일반 가정용과 24시간 전용선 요금을 구분합니다.' ),
		17 => array( '양곤 ATOM eSIM 개통 후기: 외국인 여권 등록과 실제 비용', 'ATOM 대리점에서 외국인이 eSIM을 직접 발급받은 비용, 여권 등록 과정과 약 1년간 사용한 월 통신비를 정리합니다.' ),
		19 => array( '양곤 APN·MPT 인터넷 사용기: 월요금·속도와 정전 대비', '집에서 APN, 회사에서 MPT를 사용하며 확인한 월요금, 체감 속도, 건물별 설치 제한과 공유기 정전 대비 방법입니다.' ),
		21 => array( '양곤 KBZPay 실제 사용 후기: 외국인 계좌·QR 결제와 인터넷 주의사항', 'KBZ Bank 계좌 개설 준비서류와 KBZPay QR 결제, Grab 송금, 인터넷이 약한 매장에서 겪은 결제 실패를 기록했습니다.' ),
		25 => array( '양곤 Grab 택시 실제 이용: 20~30분 요금·현금 결제와 안전 확인', '양곤 Grab의 20~30분 거리 실제 요금, 시내와 외곽 차량 차이, 현금 결제와 탑승 전 안전 확인 순서를 설명합니다.' ),
		35 => array( '양곤 현대 H-1 출퇴근 유지비: 실제 월 연료비와 이동 경로', 'Inno City에서 Hlaing Tharyar까지 10년 된 현대 H-1으로 출퇴근하며 기록한 월 연료비와 정비 변동 요인입니다.' ),
		37 => array( '양곤 우기 침수·정전 실제 경험: 출근 중단과 가정 대비', '우기 침수로 출근하지 못했던 날과 2025년 시간제 정전 경험을 바탕으로 이동 전 확인사항과 가정 대비책을 정리합니다.' ),
		41 => array( '양곤 GrabFood 실제 이용: 저녁 7시 이후 배달과 KBZPay 결제', 'GrabFood의 실제 배달 가능 시간, 평균 배달비, 현금 주문 제한과 배달원에게 KBZPay로 결제한 사례를 기록했습니다.' ),
	);
	foreach ( $titles_and_excerpts as $post_id => $seo ) {
		$post = get_post( (int) $post_id );
		if ( ! ( $post instanceof WP_Post ) || 'publish' !== $post->post_status ) {
			continue;
		}
		wp_update_post( array( 'ID' => (int) $post_id, 'post_title' => $seo[0], 'post_excerpt' => $seo[1] ) );
	}

	update_option( 'livingtmw_adsense_curation_revision', $revision, false );
}
add_action( 'init', 'livingtmw_apply_adsense_content_curation', 45 );

/**
 * Replace the repetitive numbered legacy introductions with a concise scope
 * statement. The evidence sections are then added by content-enhancements.php.
 */
function livingtmw_curated_legacy_intros(): array {
	return array(
		11 => '<p class="livingtmw-field-lead">양곤에서 가족이 실제로 거주한 Inno City와 Thiri Condo를 기준으로 전력, 수질, 이동 동선과 매도 유동성을 비교합니다. 온라인 호가가 아니라 약 1년이 걸린 실제 매도 경험과 현재 월세를 선택한 이유를 기록했습니다.</p>',
		15 => '<p class="livingtmw-field-lead">Inno City에서 확인한 계량 사용량과 청구 경험을 2026년 미얀마 전력요금 고시와 대조했습니다. 일반 가정용과 24시간 전용선 요금을 구분하고, 최종 청구액에 어떤 비용이 더해질 수 있는지 계산합니다.</p>',
		17 => '<p class="livingtmw-field-lead">외국인 거주자가 양곤 ATOM 대리점에서 eSIM을 직접 발급받고 약 1년간 사용한 기록입니다. 실제 발급비와 월 사용액, 여권 등록, 재발급 전에 확인할 사항을 개인정보를 가린 앱 화면과 함께 정리했습니다.</p>',
		19 => '<p class="livingtmw-field-lead">양곤 집에서는 APN, 회사에서는 MPT 회선을 직접 사용했습니다. 월 납부액과 체감 속도뿐 아니라 건물별 설치 제한, 정전 때 공유기와 통신장비를 유지하는 방법까지 실제 사용 조건을 중심으로 설명합니다.</p>',
		21 => '<p class="livingtmw-field-lead">KBZ Bank 계좌를 개설하고 KBZPay로 마트와 Grab 배달을 결제해 온 실제 기록입니다. 외국인이 준비했던 서류, QR 결제 과정, 인터넷이 약한 지하 매장에서 겪은 실패와 비상 결제수단을 앱 화면과 함께 공개합니다.</p>',
		25 => '<p class="livingtmw-field-lead">양곤에서 Grab 택시를 반복 이용하며 기록한 20~30분 거리의 실제 비용과 탑승 절차입니다. 시내와 외곽의 차량 상태 차이, 현금 결제, 대기료와 안전 확인 과정을 위치정보를 가린 호출 화면과 함께 설명합니다.</p>',
		35 => '<p class="livingtmw-field-lead">South Okkalapa의 Inno City에서 Hlaing Tharyar 사업장까지 10년 된 현대 H-1으로 출퇴근한 실제 유지비 기록입니다. 출퇴근 연료비와 업무 이동을 포함한 비용을 분리하고 정체·침수·차량 상태에 따른 한계를 밝힙니다.</p>',
		37 => '<p class="livingtmw-field-lead">양곤 우기에 침수 때문에 회사에 출근하지 못했던 날과 2025년 시간제 정전을 겪은 생활 기록입니다. 고정된 안전 구간을 단정하지 않고 출발 전 확인할 정보와 가정에서 실제로 준비한 정전 대응 수단을 정리했습니다.</p>',
		41 => '<p class="livingtmw-field-lead">양곤에서 GrabFood로 햄버거·치킨·마라탕을 주문하며 확인한 배달 가능 시간, 평균 배달비와 현금 주문 제한 사례입니다. KBZPay로 배달원에게 결제한 금액과 늦은 시간 주문이 어려웠던 경험도 함께 기록했습니다.</p>',
	);
}

function livingtmw_replace_generic_legacy_copy( string $content ): string {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$intros = livingtmw_curated_legacy_intros();
	return $intros[ (int) get_the_ID() ] ?? $content;
}
add_filter( 'the_content', 'livingtmw_replace_generic_legacy_copy', 14 );

/** Make archive summaries describe the evidence readers will actually find. */
function livingtmw_curated_archive_excerpt( string $excerpt, $post = null ): string {
	$post_id = $post instanceof WP_Post ? (int) $post->ID : (int) get_the_ID();
	$intros  = livingtmw_curated_legacy_intros();
	if ( ! isset( $intros[ $post_id ] ) ) {
		return $excerpt;
	}
	return wp_html_excerpt( wp_strip_all_tags( $intros[ $post_id ] ), 180, '…' );
}
add_filter( 'get_the_excerpt', 'livingtmw_curated_archive_excerpt', 20, 2 );

/** Show readers exactly who gathered the site's first-hand evidence. */
function livingtmw_about_fieldwork_profile( string $content ): string {
	if ( ! is_page( 'about-livingtmw' ) || false !== strpos( $content, 'livingtmw-about-fieldwork' ) ) {
		return $content;
	}

	$profile = '<section id="livingtmw-about-fieldwork" class="livingtmw-about-fieldwork">'
		. '<h2>누가 어떻게 확인하나요?</h2>'
		. '<p>내일의 생활은 양곤 Inno City에서 약 1년간 생활한 한국인 운영자의 가족생활 기록을 바탕으로 합니다. 운영자는 ATOM eSIM, APN·MPT 인터넷, KBZ Bank·KBZPay, Grab 택시와 음식 배달, 현지 병원, 마트와 시장 배달을 직접 이용했습니다.</p>'
		. '<p>직접 경험한 금액과 시기는 본문에 구체적으로 표시하고, 다른 사람에게 들은 정보나 업체 설명은 직접 경험과 구분합니다. 금융·전력·통신·안전처럼 바뀔 수 있는 내용은 가능한 경우 관계기관이나 서비스 제공자의 공개 자료와 대조합니다.</p>'
		. '<ul><li>앱 화면과 현장 사진은 개인정보·잔액·정확한 위치를 가린 뒤 사용합니다.</li><li>AI 설명 이미지는 실제 사진으로 오해하지 않도록 캡션에 제작 방식을 표시합니다.</li><li>가격을 모든 이용자에게 적용되는 평균처럼 표현하지 않고 가족 구성, 이용 시점과 예외를 함께 밝힙니다.</li><li>오류와 변경 제보는 eft.ho.bang@gmail.com으로 받아 확인 후 수정합니다.</li></ul>'
		. '</section>';

	return $content . $profile;
}
add_filter( 'the_content', 'livingtmw_about_fieldwork_profile', 17 );

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

/** Point public bylines to the editorial profile instead of thin author archives. */
function livingtmw_public_author_link( string $url ): string {
	return is_admin() ? $url : home_url( '/about-livingtmw/' );
}
add_filter( 'author_link', 'livingtmw_public_author_link' );
