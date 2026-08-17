<?php
/**
 * First-hand Yangon field notes and source-backed article supplements.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_content_image_figure( string $directory, string $file, string $alt, string $caption ): string {
	$relative_path = 'livingtmw-custom/images/' . $directory . '/' . $file;
	$absolute_path = __DIR__ . '/images/' . $directory . '/' . $file;
	$dimensions    = '';
	if ( is_readable( $absolute_path ) ) {
		$size = getimagesize( $absolute_path );
		if ( is_array( $size ) ) {
			$dimensions = ' width="' . (int) $size[0] . '" height="' . (int) $size[1] . '"';
		}
	}
	$url = content_url( 'mu-plugins/' . $relative_path );
	return '<figure class="livingtmw-field-image"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"' . $dimensions . ' loading="lazy" decoding="async"><figcaption>' . esc_html( $caption ) . '</figcaption></figure>';
}

function livingtmw_article_image( string $file, string $alt ): string {
	return livingtmw_content_image_figure( 'articles', $file, $alt, 'AI로 제작한 주제 설명 이미지입니다. 실제 장소·상품의 모습과 다를 수 있습니다.' );
}

function livingtmw_field_photo( string $file, string $alt ): string {
	return livingtmw_content_image_figure( 'field-photos', $file, $alt, '작성자가 직접 사용 중 촬영한 화면입니다. 개인정보와 금융·위치 정보는 보호를 위해 가렸습니다.' );
}

function livingtmw_sources( array $sources ): string {
	$html = '<div class="livingtmw-field-sources"><h3>공식 자료와 확인일</h3><ul>';
	foreach ( $sources as $label => $url ) {
		$html .= '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . '</a> <span>(확인: 2026-08-14)</span></li>';
	}
	return $html . '</ul></div>';
}

function livingtmw_field_update( int $post_id ): string {
	$case = '<p class="livingtmw-field-badge">2026년 8월 · 양곤 거주자가 직접 제공한 실제 사례</p>';
	$note = '<div class="livingtmw-field-note"><strong>적용 범위</strong> 금액과 조건은 작성자의 실제 경험이며 환율, 건물, 가족 구성, 사용량, 지점 심사에 따라 달라집니다. 계약·금융·환전은 실행 전에 해당 기관과 전문가에게 다시 확인하세요.</div>';

	switch ( $post_id ) {
		case 8:
			return livingtmw_article_image( 'yangon-monthly-budget.png', '양곤 월 생활비를 계산하는 참고 이미지' ) . $case . '<h2>실제 거주비를 숫자로 다시 계산해 보기</h2><p>작성자는 양곤 1인 생활비의 현실적인 출발점을 월 <strong>1,500,000 MMK</strong>로 잡습니다. 한식당 한 끼는 1인 약 20,000 MMK, ATOM 통신비는 월 약 25,000 MMK를 사용했습니다. 이 예산은 생활 방식에 따른 참고값이며 월세는 별도로 계산하는 편이 안전합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>실제 사례</th><th>예외</th></tr></thead><tbody><tr><td>1인 생활비</td><td>월 약 1,500,000 MMK</td><td>월세 포함 여부와 외식 빈도에 따라 크게 변동</td></tr><tr><td>한식당</td><td>1인 약 20,000 MMK</td><td>메뉴·매장별 차이</td></tr><tr><td>통신</td><td>ATOM 월 약 25,000 MMK</td><td>데이터 사용량별 차이</td></tr><tr><td>아파트 월세</td><td>보통 USD 500~700</td><td>위치·면적·가구·전력 조건별 차이</td></tr></tbody></table></div><p>작성자가 계산할 때 사용한 체감 환율은 KRW 1,000 ≈ 3,000 MMK, USD 1 ≈ 4,400 MMK였습니다. 이는 고정 환율이나 공식 환율이 아니므로 예산표를 만들 때는 당일 합법적인 환전 창구의 실제 수령액으로 다시 계산해야 합니다.</p>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', 'City Properties의 City Mart 매장 안내' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 11:
			return livingtmw_article_image( 'yangon-condo-water-filter.png', '양곤 아파트 수질과 정수 필터 점검 참고 이미지' ) . $case . '<h2>Inno City와 Thiri Condo에서 배운 집 구하기 기준</h2><p>작성자가 거주 중인 Inno City는 한국식 아파트에 가깝고 한국인 거주자가 많은 편입니다. 전기와 발전기 환경이 안정적인 반면, 장을 보려면 차를 이용해야 하는 점은 단점입니다. 부모님이 거주했던 오래된 Thiri Condo에서는 물 위생이 만족스럽지 않았습니다. 그래서 오래된 건물뿐 아니라 새 아파트도 입주 전에 수질과 물탱크 관리 상태를 확인하고 <strong>주방·샤워 필터</strong>를 준비하는 것을 권합니다.</p><div class="livingtmw-checklist"><h3>계약 전 현장에서 확인할 것</h3><ul><li>정전 시간, 발전기 가동 범위, kWh 단가와 별도 연료비</li><li>수돗물 색·냄새·수압, 물탱크 청소 주기와 필터 설치 가능 여부</li><li>관리비·주차비·인터넷·퇴실 청소비를 누가 부담하는지</li><li>입주 취소·중도 퇴실 시 보증금 반환 조건과 반환 기한</li><li>마트·병원까지 실제 차량 이동 시간</li></ul></div><p>부동산을 ‘50~70년 사용 계약’으로 이해했다는 현장 경험은 개별 프로젝트의 토지권리와 계약 구조에 관한 설명일 수 있으며, 미얀마의 모든 아파트에 일괄 적용되는 법칙으로 보면 안 됩니다. 외국인의 콘도미니엄 권리에도 등록 요건과 제한이 있으므로 매수 전 토지 등기, 잔여기간, 외국인 취득 가능 여부, 재판매 조건을 독립 변호사에게 확인하세요. 작성자는 매수 후 재판매가 어려운 사례를 보아 장기 체류가 확정되지 않았다면 월세를 선호합니다.</p>' . $note . livingtmw_sources( array( 'DICA/OECD 미얀마 투자정책 검토 자료' => 'https://www.dica.gov.mm/sites/dica.gov.mm/files/news-files/d7984f44-en.pdf' ) );

		case 13:
			return livingtmw_article_image( 'yangon-rent-contract.png', '양곤 아파트 월세 계약과 공과금 참고 이미지' ) . $case . '<h2>월세·보증금·공과금 실제 범위</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 경험</th><th>계약서 확인</th></tr></thead><tbody><tr><td>월세</td><td>USD 500~700가 흔한 출발점</td><td>달러·MMK 중 결제 통화와 적용 환율</td></tr><tr><td>보증금</td><td>월세 1개월분 사례</td><td>공제 사유, 반환일, 중도해지</td></tr><tr><td>관리·주차</td><td>아파트별 상이</td><td>월세 포함 여부</td></tr><tr><td>전기·발전기</td><td>공급 방식에 따라 큰 차이</td><td>계량기 사진과 단가를 매월 확인</td></tr></tbody></table></div><p>Inno City에서는 신생아가 있는 3인 가족이 에어컨을 상시 사용해 전기·냉방 관련 월 청구가 약 1,800,000 MMK였고, 작성자가 접한 2인 가구 사례는 약 1,000,000 MMK였습니다. 이는 관리비 전체나 모든 세대의 평균이 아니라 사용량과 건물 요금 체계가 반영된 개별 사례입니다.</p><p>계약금 송금 전에는 ‘입주가 취소되면 언제, 어떤 통화로, 얼마를 돌려받는지’를 문장으로 넣으세요. 가구·가전 고장은 사진과 인수인계표로 남기고, 영문 계약서와 현지어 계약서가 다를 때 어느 문서가 우선하는지도 정하는 것이 좋습니다.</p>' . $note . livingtmw_sources( array( 'DICA/OECD 미얀마 투자정책 검토 자료' => 'https://www.dica.gov.mm/sites/dica.gov.mm/files/news-files/d7984f44-en.pdf' ) );

		case 15:
			return livingtmw_article_image( 'yangon-electricity-bill.png', '양곤 아파트 전기요금과 냉방 참고 이미지' ) . $case . '<h2>일반 가정용 요금과 24시간 공급 요금은 다릅니다</h2><p>2026년 2월 1일부터 적용된 공식 고시에 따르면 일반 가정용은 사용 구간별로 1kWh당 50·100·150·300 MMK이고, 24시간 중단 없는 전용선 공급은 1kWh당 <strong>900 MMK</strong>입니다. 작성자가 Inno City에서 경험한 900 MMK/kWh는 후자와 일치합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>공급 유형</th><th>2026 공식 단가</th><th>주의</th></tr></thead><tbody><tr><td>가정용 1~50kWh</td><td>50 MMK/kWh</td><td>누진 구간별 계산</td></tr><tr><td>51~100 / 101~200</td><td>100 / 150 MMK/kWh</td><td>각 구간에 적용</td></tr><tr><td>201kWh 초과</td><td>300 MMK/kWh</td><td>일반 가정용</td></tr><tr><td>24시간 전용선</td><td>900 MMK/kWh</td><td>일반 가정용과 별도</td></tr></tbody></table></div><p>실사용량 670.9kWh에 900 MMK를 단순 곱하면 약 <strong>603,810 MMK</strong>입니다. 관리비, 발전기, 연료, 공용전기, 세금이나 다른 청구가 더해지면 최종 금액은 달라집니다. Inno City의 3인 가족 상시 냉방 사례는 월 약 1,800,000 MMK, 2인 가구 사례는 약 1,000,000 MMK였습니다. Thiri Condo 월 200,000~300,000 MMK는 작성자가 다른 거주자에게 들은 값이므로 참고만 하세요.</p>' . $note . livingtmw_sources( array( '미얀마 전력요금 고시 89/2026 (정부신문 PDF)' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );

		case 17:
			return livingtmw_field_photo( 'atom-app-redacted.png', '개인정보와 잔액을 가린 ATOM 앱 실제 사용 화면' ) . $case . '<h2>ATOM eSIM 개통 실제 순서</h2><ol><li>eSIM 지원 휴대폰과 여권 원본을 준비합니다.</li><li>공식 대리점에서 본인 명의 등록과 eSIM 발급을 요청합니다.</li><li>통화·문자·데이터가 모두 되는지 매장 안에서 확인합니다.</li><li>명의 등록 상태와 잔액 확인 방법을 저장합니다.</li></ol><p>작성자는 eSIM 발급에 5,000 MMK를 지불했고 대리점 방문 후 바로 등록했습니다. 월 약 25,000 MMK 사용으로 일상 통신에는 충분했습니다. ATOM 공식 안내의 <strong>SIM 등록 자체는 무료</strong>이므로, 5,000 MMK는 작성자가 경험한 eSIM 발급 비용이며 등록 수수료와 구분해야 합니다.</p><p>외국인은 여권 정보를 정확히 등록해야 하며, 명의 불일치는 통화·데이터·은행 OTP 사용에 영향을 줄 수 있습니다. 기기 호환성, eSIM 재발급 비용, 요금제는 방문 당일 확인하세요.</p>' . $note . livingtmw_sources( array( 'ATOM SIM 등록 안내' => 'https://arena.atom.com.mm/en/personal/simregistration', 'MPT SIM 등록 안내' => 'https://mpt.com.mm/en/sim-register/' ) );

		case 19:
			return livingtmw_article_image( 'yangon-home-internet.png', '양곤 가정 인터넷과 공유기 백업 전원 참고 이미지' ) . $case . '<h2>APN·MPT 실제 사용과 다른 회선 비교</h2><p>작성자는 집에서 APN, 회사에서 MPT를 사용합니다. 월 80,000~100,000 MMK, 체감 속도 60~80Mbps였고 해당 설치에서는 별도 설치비 없이 빠르게 개통됐습니다. 최고 속도보다 중요한 것은 <strong>내 건물에 실제로 들어오는 회선</strong>과 정전 때 공유기·통신장비가 얼마나 유지되는지입니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>선택지</th><th>확인된 정보</th><th>계약 전 질문</th></tr></thead><tbody><tr><td>APN</td><td>작성자 집: 60~80Mbps, 월 8~10만 MMK</td><td>해당 동·호수 설치 가능 여부</td></tr><tr><td>MPT</td><td>작성자 회사에서 사용</td><td>기업·가정 요금과 장애 대응</td></tr><tr><td>5BB</td><td>공식 선불 FTTH 상품 공개</td><td>설치비·장비·약정</td></tr><tr><td>Myanmar Net</td><td>공식 사이트에 2026 가격·설치비 공지</td><td>지역·건물별 실제 속도</td></tr></tbody></table></div><p>영상회의는 대체로 가능했지만 한국보다 느리게 느껴질 수 있었습니다. 계약 전 휴대폰으로 와이파이 속도뿐 아니라 업로드, 지연시간, 저녁 혼잡 시간도 측정하고 공유기용 UPS를 검토하세요.</p>' . $note . livingtmw_sources( array( 'Myanmar APN 공식 사이트' => 'https://myanmarapn.com/about', '5BB FTTH 선불 요금' => 'https://www.5bb.com.mm/ShowInformation/FTTHPrepaid', 'Myanmar Net 공식 사이트' => 'https://www.myanmarnet.com/' ) );

		case 21:
			return livingtmw_field_photo( 'kbzpay-app-redacted.png', '금융정보를 가린 KBZPay 앱 실제 사용 화면' ) . $case . '<h2>KBZ 계좌 개설 실제 경험</h2><p>작성자는 미얀마 거주자로 KBZ Bank 계좌를 개설했고 일상 결제에 KBZPay를 자주 사용합니다. KBZ가 공개한 외국인 고객 안내에는 거주 외국인의 여권, 유효한 비자, 서명, 소득원 관련 서류 등이 언급됩니다.</p><div class="livingtmw-checklist"><h3>방문 전 준비 체크</h3><ul><li>여권 원본과 유효한 비자·체류 증빙</li><li>현지 주소와 연락 가능한 미얀마 전화번호</li><li>재직·사업·소득원 증빙</li><li>계좌 목적과 입출금 예정 통화</li><li>모바일뱅킹·KBZPay·OTP 활성화 가능 여부</li></ul></div><p>공식 FAQ는 2022년 문서라 현재 지점별 심사와 요구 서류가 달라질 수 있습니다. ‘거주자면 무조건 개설된다’고 단정하지 말고 방문할 지점에 먼저 확인하세요. 개설 직후 소액 이체로 계좌명, 수수료, 일일 한도와 분실 시 복구 절차를 시험하는 편이 안전합니다.</p>' . $note . livingtmw_sources( array( 'KBZ 외국인 고객 계좌 FAQ (PDF)' => 'https://www.kbzbank.com/wp-content/uploads/2022/08/Eng-General-FAQs-for-Customers_-Self-Service-BankingJuly-2022.pdf' ) );

		case 23:
			return livingtmw_article_image( 'yangon-currency-exchange.png', '양곤의 은행 환전과 현금 확인 참고 이미지' ) . $case . '<h2>공식 환율과 체감 환율을 구분해야 합니다</h2><p>작성자가 같은 시기에 확인한 값은 은행 약 USD 1 = 3,663 MMK, 외부 시장 약 USD 1 = 4,400 MMK였습니다. 이 차이는 실제 생활비 계산에는 중요하지만, 외부 환율이 곧 합법적이거나 안전하다는 뜻은 아닙니다. <strong>중앙은행이 허가한 은행·환전소</strong>의 당일 고시, 수수료와 실제 수령액을 확인하세요.</p><p>달러 현찰은 새 지폐를 준비하는 것이 좋습니다. 작성자는 작은 흠집이나 오염이 있는 지폐가 거절되는 일을 경험했습니다. 지폐를 접거나 도장을 찍지 말고, 환전 전후 금액을 창구에서 세며 영수증을 보관하세요.</p><div class="livingtmw-field-warning"><strong>안전 주의</strong> 비공식 개인 거래는 위조지폐, 강도, 사기, 환율 규정 위반 위험이 있습니다. 이 글은 비공식 환전을 권하지 않습니다. 큰 금액은 반드시 현재 규정과 허가 여부를 확인하세요.</div>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', '미얀마 중앙은행 환율 페이지' => 'https://forex.cbm.gov.mm/' ) );

		case 25:
			return livingtmw_field_photo( 'grab-taxi-redacted.png', '출발지와 목적지를 익명 처리한 양곤 Grab 택시 실제 호출 화면' ) . $case . '<h2>Grab 실제 비용과 탑승 요령</h2><p>작성자는 Grab에서 현금 결제를 가장 편하게 사용하고 있으며, 교통 상황이 보통일 때 20~30분 거리의 체감 비용은 약 20,000 MMK였습니다. 그러나 공식 안내처럼 호출 가격은 거리뿐 아니라 수요·공급과 혼잡도에 따라 바뀌므로 이 값을 정액으로 보면 안 됩니다.</p><ol><li>호출 전에 승차 위치를 큰 건물 입구처럼 찾기 쉬운 곳으로 지정합니다.</li><li>차량 번호와 기사 정보를 앱에서 대조합니다.</li><li>앱에 표시된 예상 요금과 결제수단을 확인합니다.</li><li>현금은 잔돈을 준비하고, 도착 후 앱의 완료 금액을 확인합니다.</li><li>차량 정보와 이동 경로를 동행자에게 공유합니다.</li></ol><p>Grab 공식 페이지는 대기 유예시간 이후 추가 요금이 생길 수 있다고 안내합니다. 공항, 비, 출퇴근 시간에는 20,000 MMK 사례보다 훨씬 높아질 수 있습니다.</p>' . $note . livingtmw_sources( array( 'Grab Myanmar 택시 안내' => 'https://www.grab.com/mm/en/transport/taxi/', 'Grab Myanmar 운송 약관' => 'https://www.grab.com/mm/terms-policies/transport-delivery-logistics/' ) );

		case 27:
			return livingtmw_article_image( 'yangon-grocery-split.png', '양곤 슈퍼마켓 상품과 현지 시장 식재료 참고 이미지' ) . $case . '<h2>품목별로 구매처를 나누면 편합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>품목</th><th>주로 이용한 곳</th><th>이유·주의</th></tr></thead><tbody><tr><td>고기·음료</td><td>City Mart·Market Place</td><td>포장·냉장 상품 비교가 편함</td></tr><tr><td>채소·과일</td><td>현지 시장·배달</td><td>신선도와 당일 가격 확인</td></tr><tr><td>한국 수입품</td><td>대형마트·전문점</td><td>수입비용 때문에 비쌀 수 있음</td></tr><tr><td>생수·무거운 물품</td><td>배달</td><td>배송 시간·최소 주문 확인</td></tr></tbody></table></div><p>작성자 체감으로 한국에서 300원인 수입 제품이 미얀마에서는 한국 돈 환산 900~1,200원 수준이 되는 경우가 있었습니다. 모든 상품이 정확히 3~4배라는 뜻은 아니며 환율, 관세, 유통과 재고에 따라 다릅니다. 현지 채소와 과일은 시장 배달을 이용하면 이동 시간을 줄일 수 있습니다.</p><p>냉장·냉동 식품은 정전 이력과 포장 상태를 보고, 과일·채소는 수령 즉시 세척·선별하세요. 배달 주문은 대체 상품 허용 여부와 환불 조건을 먼저 확인하는 편이 좋습니다.</p>' . $note . livingtmw_sources( array( 'City Properties의 Yangon City Mart 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 29:
			return livingtmw_article_image( 'yangon-market-comparison.png', '양곤 대형마트와 현지 시장 비교 참고 이미지' ) . $case . '<h2>City Mart·Market Place·시장 비교</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>구매처</th><th>잘 맞는 용도</th><th>단점</th></tr></thead><tbody><tr><td>City Mart</td><td>일상 식료품·음료·생활용품</td><td>수입품은 가격 부담</td></tr><tr><td>Market Place</td><td>수입 식재료와 비교 구매</td><td>지점·재고별 차이</td></tr><tr><td>현지 시장</td><td>채소·과일</td><td>가격·위생·품질을 직접 확인</td></tr><tr><td>배달 주문</td><td>시장 장보기와 무거운 상품</td><td>대체품·배송비·수령시간</td></tr></tbody></table></div><p>Inno City에서는 마트 이동에 차가 필요한 점이 실제 생활 동선의 단점이었습니다. 집을 고를 때 월세만 보지 말고 자주 가는 마트까지의 Grab 비용과 시간을 생활비에 포함하세요. 한국인 가정은 고기와 음료는 City Mart나 Market Place, 채소와 과일은 시장에서 나누어 사는 경우가 많다는 것이 작성자의 관찰입니다.</p><p>공식 매장 목록은 지점 위치를 찾는 출발점일 뿐 영업시간과 재고를 보장하지 않습니다. 방문 당일 지도와 매장 채널로 다시 확인하세요.</p>' . $note . livingtmw_sources( array( 'City Properties의 Yangon City Mart 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/', 'City Mart 공식 센터 안내' => 'https://www.citypropertiesmm.com/center-group/city-mart/' ) );

		case 31:
			return livingtmw_article_image( 'yangon-korean-groceries.png', '양곤에서 한국 식재료와 현지 채소를 함께 구매하는 참고 이미지' ) . $case . '<h2>실제 장보기 기준: 수입 양념과 현지 신선식품을 나눕니다</h2><p>작성자는 고기와 음료는 City Mart·Market Place, 채소와 과일은 현지 시장이나 배달을 이용합니다. 고추장·된장·김처럼 맛을 좌우하고 오래 보관되는 품목만 수입품으로 사고, 고기·달걀·채소는 현지 제품을 비교하면 비용을 줄이기 쉽습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>품목</th><th>추천 구매처</th><th>확인사항</th></tr></thead><tbody><tr><td>한국 양념·라면·김</td><td>Market Place·City Mart 수입 코너</td><td>유통기한·한글 라벨·포장</td></tr><tr><td>고기·음료</td><td>대형마트</td><td>냉장 상태와 소비기한</td></tr><tr><td>채소·과일</td><td>시장·배달</td><td>수령 즉시 선별·세척</td></tr><tr><td>대량 수입품</td><td>가격 비교 후 구매</td><td>품절 대비와 보관 공간</td></tr></tbody></table></div><p>한국에서 300원인 수입 제품이 현지에서 한국 돈 환산 900~1,200원 수준이 된 사례도 있었습니다. 모든 상품의 고정 배율은 아니며 환율과 재고에 따라 달라집니다.</p>' . $note . livingtmw_sources( array( 'Yangon City Mart 공식 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/', 'GrabMart Myanmar 공식 안내' => 'https://www.grab.com/mm/en/mart/' ) );

		case 33:
			return livingtmw_article_image( 'yangon-driving-safety.png', '양곤에서 운전 전 차량을 점검하는 참고 이미지' ) . '<p class="livingtmw-field-badge">2026년 8월 · 공식 법령과 현지 환경 확인</p><h2>운전 전 반드시 확인할 법적·안전 항목</h2><p>미얀마 도로안전 및 자동차관리법은 유효한 운전면허, 차량 등록과 안전 의무를 다룹니다. 한국 면허나 국제운전면허증만으로 운전 가능한지는 체류 자격과 현재 행정 절차에 따라 달라질 수 있으므로 Road Transport Administration Department 또는 현지 보험사에 직접 확인해야 합니다.</p><div class="livingtmw-checklist"><h3>출발 전 1분 점검</h3><ul><li>면허 유효성, 차량 등록증과 보험 보장 범위</li><li>타이어 공기압·마모, 브레이크, 전조등과 와이퍼</li><li>냉각수·배터리·에어컨과 비상 삼각대</li><li>침수 구간을 피할 대체 경로와 연락처</li><li>사고 시 차량을 무리하게 이동하기 전 사진과 위치 기록</li></ul></div><p>양곤은 교통 흐름과 차선 사용이 한국과 다르게 느껴질 수 있습니다. 도착 직후에는 기사나 Grab을 이용해 동선을 익힌 뒤 직접 운전을 판단하는 것이 안전합니다.</p>' . $note . livingtmw_sources( array( '미얀마 도로안전 및 자동차관리법 2020 (MOTC PDF)' => 'https://www.motc.gov.mm/sites/default/files/rdad/Road%20Safety%20and%20Motor%20Vehicle%20Management%20Law%20%282020%29.pdf' ) );

		case 35:
			return livingtmw_article_image( 'yangon-h1-commute.png', '양곤에서 현대 H-1으로 장거리 통근하는 참고 이미지' ) . $case . '<h2>현대 H-1 실제 통근비: 연료비 월 약 2,000,000 MMK</h2><p>작성자는 한국의 현대 스타렉스에 해당하는 <strong>현대 H-1</strong>을 이용합니다. Inno City가 있는 South Okkalapa에서 Hlaing Tharyar의 Ewha Knit까지 회사 출퇴근을 왕복해 연료비가 월 약 <strong>2,000,000 MMK</strong> 듭니다. 교통 정체와 우회 경로에 따라 거리와 시간은 달라지므로 출발 전 지도 앱에서 당일 경로를 확인해야 합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 실제 기준</th><th>변동 요인</th></tr></thead><tbody><tr><td>차량</td><td>현대 H-1(한국명 스타렉스)</td><td>연식·상태·실연비</td></tr><tr><td>주요 동선</td><td>Inno City ↔ Ewha Knit</td><td>정체·침수·우회 경로</td></tr><tr><td>월 연료비</td><td>약 2,000,000 MMK</td><td>출근일·유가·에어컨 사용</td></tr><tr><td>정비비</td><td>10년 된 차량이라 고장과 교체 항목에 따라 변동</td><td>부품 수급·정비 이력</td></tr></tbody></table></div><p>정비비는 일정한 월평균보다 엔진오일·필터 같은 정기 교환비와 타이어·배터리·고장 수리비를 나눠 기록하는 편이 정확합니다. 이 사례는 긴 통근 동선과 10년 된 차량을 기준으로 하므로 모든 H-1의 평균 유지비로 일반화할 수 없습니다.</p>' . $note . livingtmw_sources( array( 'Inno City 공식 위치 안내' => 'https://www.inno-group.kr/?lang=en&page_id=3476', 'MyanTrade의 Ewha Knit 사업장 주소' => 'https://myantrade.gov.mm/exporter-directory/175/detail', '미얀마 도로안전 및 자동차관리법 2020 (MOTC PDF)' => 'https://www.motc.gov.mm/sites/default/files/rdad/Road%20Safety%20and%20Motor%20Vehicle%20Management%20Law%20%282020%29.pdf' ) );

		case 37:
			return livingtmw_article_image( 'yangon-monsoon-flood.png', '양곤 우기 도로 침수와 통근 차질 참고 이미지' ) . $case . '<h2>우기에는 침수로 출근하지 못하는 날도 있습니다</h2><p>작성자 경험으로 양곤은 우기 침수가 잦고, 일부 구간은 차량 통행이 어려워 회사에 출근하지 못할 정도로 물이 차기도 했습니다. 침수 위치와 시간은 강우량과 배수 상태에 따라 달라 고정된 안전 구간으로 단정할 수 없습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>상황</th><th>실제 영향</th><th>준비</th></tr></thead><tbody><tr><td>강한 비·침수</td><td>출근길 통행 불가 가능</td><td>출발 전 예보·도로 상황·우회로 확인</td></tr><tr><td>일반 건물 정전</td><td>발전기가 없으면 4~5시간 전기 없이 지낸 경험</td><td>조명·보조배터리·공유기 UPS 준비</td></tr><tr><td>주거 선택</td><td>24시간 전력 또는 발전기 필요</td><td>공급 범위와 가동시간을 계약 전에 확인</td></tr></tbody></table></div><p>수심을 모르는 도로에는 차량이나 도보로 진입하지 마세요. 침수된 물에는 열린 맨홀, 전선과 오염 위험이 있을 수 있습니다. 발전기는 환기되지 않는 실내에서 사용하면 안 됩니다.</p>' . $note . livingtmw_sources( array( '미얀마 기상수문국 공식 사이트' => 'https://www.dmh.gov.mm/' ) );

		case 39:
			return livingtmw_article_image( 'yangon-backup-generator.png', '양곤 아파트 정전과 발전기 백업 전원 참고 이미지' ) . $case . '<h2>발전기가 없는 집은 정전 때 4~5시간을 버텨야 할 수 있습니다</h2><p>작성자가 거주하는 Inno City는 24시간 전력 공급과 발전기 환경을 갖췄습니다. 반면 일반 건물에서는 정전이 자주 발생했고, 발전기가 없을 때 <strong>4~5시간가량 전기 없이 지낸 경험</strong>도 있습니다. 따라서 집을 구할 때 발전기나 24시간 전력 공급 여부를 핵심 조건으로 봅니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>확인 항목</th><th>계약 전 질문</th></tr></thead><tbody><tr><td>전환 시간</td><td>정전 후 발전기 전환까지 몇 분인가?</td></tr><tr><td>공급 범위</td><td>조명뿐 아니라 에어컨·엘리베이터·인터넷도 되는가?</td></tr><tr><td>가동 시간</td><td>4~5시간 이상 정전에도 계속 가동하는가?</td></tr><tr><td>요금</td><td>계량 단가, 연료비와 공용전기 비용은 별도인가?</td></tr></tbody></table></div><p>‘발전기 있음’이라는 설명만 믿지 말고 실제 전환 시간과 세대 내 공급 콘센트를 확인하세요. 2026년 공식 고시에서 일반 가정용과 24시간 전용선 요금은 다르며, Inno City 사례인 900 MMK/kWh를 일반 가정용 단가로 일반화하면 안 됩니다.</p>' . $note . livingtmw_sources( array( '전력요금 고시 89/2026 (정부신문 PDF)' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );

		case 41:
			return livingtmw_article_image( 'yangon-food-delivery.png', '양곤의 자전거 음식 배달과 현금 결제 참고 이미지' ) . $case . '<h2>저녁 7시 이후에는 주문 선택지가 크게 줄어듭니다</h2><p>작성자는 주로 롯데리아의 햄버거·치킨이나 마라탕을 배달해 먹었습니다. 한국처럼 24시간 배달되는 환경은 아니며, 체감상 <strong>오후 7시가 지나면 배달이 어려워지는 경우</strong>가 많았습니다. 평균 배달비는 약 <strong>2,000 MMK</strong>로 비교적 저렴했고 자전거 배달도 이용됩니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 경험</th><th>주문 전 확인</th></tr></thead><tbody><tr><td>주요 메뉴</td><td>햄버거·치킨·마라탕</td><td>식당 영업 여부와 예상시간</td></tr><tr><td>배달 가능 시간</td><td>오후 7시 이후 어려운 경우가 많음</td><td>당일 앱 표시</td></tr><tr><td>배달비</td><td>평균 약 2,000 MMK</td><td>거리·날씨·수요에 따라 변동</td></tr><tr><td>현금 주문 한도</td><td>60,000 MMK를 넘으면 한 번에 주문하지 못한 경험</td><td>결제화면의 현재 한도</td></tr></tbody></table></div><p>작성자는 현금 결제 주문이 60,000 MMK를 넘을 때 같은 식당에 두 번 나눠 주문해야 했습니다. 이는 모든 식당과 계정에 적용되는 고정 규칙이라고 단정할 수 없으므로, 주문을 나누기 전에 추가 배달비와 식당의 중복 주문 수락 여부를 확인하세요.</p>' . $note . livingtmw_sources( array( 'GrabFood Myanmar 공식 도움말' => 'https://help.grab.com/passenger/en-mm/360001025888', 'GrabFood Myanmar 공식 주문 페이지' => 'https://food.grab.com/mm/en/' ) );

		case 43:
			return livingtmw_article_image( 'yangon-arrival-essentials.png', '양곤 입주 첫 주 생활용품 준비 참고 이미지' ) . $case . '<h2>도착 첫 주에 바로 필요한 것부터 준비합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>한국에서 우선 준비</th><th>현지 구매 가능</th><th>입주 즉시 확인</th></tr></thead><tbody><tr><td>개인 처방약·영문 처방전, 선호 위생용품</td><td>기본 식료품·생수·청소도구</td><td>주방·샤워 필터 규격</td></tr><tr><td>보조배터리·중요 자료 백업</td><td>우산·제습제·현지 SIM</td><td>정전·발전기·콘센트</td></tr><tr><td>구하기 어려운 한국 양념 소량</td><td>채소·과일·고기</td><td>마트·병원·배달 동선</td></tr></tbody></table></div><p>부모님이 살았던 오래된 Thiri Condo에서는 물 위생이 좋지 않았던 경험이 있어 작성자는 미얀마 아파트 생활에 필터를 권합니다. Inno City처럼 마트까지 차량이 필요한 곳도 있으므로 무거운 물품은 첫날 배달로 준비하세요. 의약품은 성분·반입 규정을 확인하고 원래 포장과 처방전을 함께 보관해야 합니다.</p>' . $note . livingtmw_sources( array( 'ATOM 외국인 SIM 등록 안내' => 'https://arena.atom.com.mm/en/personal/simregistration', 'City Mart Yangon 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 45:
			return livingtmw_article_image( 'yangon-hospital-visit.png', '양곤 병원 외래 진료와 본인 부담 결제 참고 이미지' ) . $case . '<h2>CLL과 Aryu에서 받은 진료의 실제 비용</h2><p>작성자는 별도의 의료보험 없이 CLL과 Aryu International Hospital을 이용했습니다. CLL 진료비는 보통 <strong>100,000~200,000 MMK</strong>, Aryu는 <strong>USD 50~100</strong> 정도였습니다. 검사·약·전문과에 따라 달라질 수 있는 개인 경험값이며 방문 전 예상 비용과 결제 통화를 확인해야 합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>병원</th><th>작성자 경험</th><th>주의</th></tr></thead><tbody><tr><td>CLL</td><td>보통 10만~20만 MMK, 프랑스인 의사 원장이 친절했음</td><td>전문성에 대한 평가는 확인하지 못함</td></tr><tr><td>Aryu</td><td>USD 50~100, 미얀마 의사에게 진료</td><td>한 차례 진료 방식이 신뢰감을 주지 못했다는 개인 경험</td></tr><tr><td>보험</td><td>별도 보험 없이 본인 부담</td><td>영수증·진단서 보관</td></tr></tbody></table></div><div class="livingtmw-field-warning"><strong>개인 경험의 한계</strong> 특정 의료진이나 병원의 전체 진료 수준을 평가한 내용이 아닙니다. 담당 의사와 진료과에 따라 경험이 달라질 수 있으며, 중대한 증상은 한 번의 인상보다 자격·전문과·검사 결과를 확인하고 필요하면 다른 병원에서 2차 소견을 받으세요.</div><p>보험이 없어도 진료 후 영문 진단서, 처방전, 검사결과와 항목별 영수증을 받아 두는 것이 좋습니다. 응급상황은 온라인 글로 판단하지 말고 즉시 현지 응급기관에 연락하세요.</p>' . $note . livingtmw_sources( array( 'WHO Myanmar 국가·보건 정보' => 'https://www.who.int/countries/mmr/' ) );

		case 47:
			return livingtmw_article_image( 'yangon-korea-cost-comparison.png', '양곤과 한국의 생활비 비교 참고 이미지' ) . $case . '<h2>한국과 비교하기 전에 실제 양곤 예산을 먼저 고정합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 실제 기준</th><th>한국 대비 체감</th></tr></thead><tbody><tr><td>1인 월 생활비</td><td>약 1,500,000 MMK</td><td>생활 방식에 따라 달라짐</td></tr><tr><td>한식당 한 끼</td><td>1인 약 20,000 MMK</td><td>현지식보다 부담</td></tr><tr><td>아파트 월세</td><td>보통 USD 500~700</td><td>외국인 선호 주거는 저렴하지 않을 수 있음</td></tr><tr><td>ATOM 통신</td><td>월 약 25,000 MMK</td><td>데이터 사용량별 차이</td></tr><tr><td>수입제품</td><td>한국 가격의 체감 3~4배 사례</td><td>현지 대체품 사용 시 절감</td></tr></tbody></table></div><p>작성자가 당시 계산에 사용한 체감 환율은 KRW 1,000 ≈ 3,000 MMK, USD 1 ≈ 4,400 MMK입니다. 공식 고정 환율이 아니므로 비교일의 합법적인 환전 창구 실수령액으로 다시 계산해야 합니다. 에어컨을 상시 사용하는 Inno City 3인 가족의 전기·냉방 관련 청구 사례는 월 약 1,800,000 MMK였으므로 ‘미얀마는 무조건 싸다’는 결론은 맞지 않습니다.</p>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', '전력요금 고시 89/2026' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );
	}

	return '';
}

/** Apply the resident's latest first-hand corrections without inventing details. */
function livingtmw_verified_experience_update( int $post_id, string $html ): string {
	$insert_before_sources = static function ( string $source_html, string $addition ): string {
		$position = strpos( $source_html, '<div class="livingtmw-field-sources">' );
		if ( false === $position ) {
			return $source_html . $addition;
		}
		return substr( $source_html, 0, $position ) . $addition . substr( $source_html, $position );
	};

	if ( 11 === $post_id ) {
		$html = $insert_before_sources(
			$html,
			'<h2>Thiri Condo를 실제로 매도하며 느낀 환금성</h2><p>부모님이 살던 Thiri Condo를 매물로 내놓은 뒤 실제 매도까지 약 1년이 걸렸습니다. 온라인 매물가는 약 550,000,000 MMK로 제시했지만 자금이 급해 최종적으로는 약 400,000,000 MMK에 매도했습니다. 이는 특정 시점의 한 건에 대한 개인 경험이며 모든 콘도의 현재 시세나 미래 가격을 뜻하지 않습니다.</p><p>이 경험 이후 작성자는 양곤 아파트를 매입하기보다 월세로 거주하는 편을 선호합니다. 집값이 앞으로 반드시 떨어진다고 단정할 수는 없지만, 원하는 시기에 원하는 가격으로 팔기 어려울 수 있다는 유동성 위험을 직접 겪었기 때문입니다. 매수를 검토한다면 온라인 호가가 아니라 최근 실거래 가능 가격, 예상 매도 기간, 중개 수수료와 급매 시 가격 차이를 함께 확인해야 합니다.</p>'
		);
	}
	if ( 13 === $post_id ) {
		$html = $insert_before_sources(
			$html,
			'<h2>월세를 선택한 이유는 매달 비용만이 아닙니다</h2><p>작성자는 가족이 Thiri Condo를 매도하는 데 약 1년이 걸린 경험을 한 뒤 Inno City에서 월세 생활을 선택했습니다. 매입은 매달 월세가 나가지 않는 장점이 있을 수 있지만, 급하게 현금이 필요할 때 매수자를 찾는 시간과 가격 인하 폭까지 고려해야 합니다. 체류 기간이 확정되지 않았거나 자금 유동성이 중요하다면 월세가 심리적으로도 편한 선택일 수 있습니다.</p>'
		);
	}
	if ( 21 === $post_id ) {
		$html = str_replace(
			'작성자는 미얀마 거주자로 KBZ Bank 계좌를 개설했고 일상 결제에 KBZPay를 자주 사용합니다.',
			'작성자는 KBZ Bank(깐부자은행) 계좌와 KBZPay를 직접 사용합니다. 매장에서 QR을 스캔하면 결제가 바로 끝나 현금보다 편리하다는 것이 약 1년간 양곤에서 생활하며 느낀 가장 큰 장점입니다.',
			$html
		);
		$html = $insert_before_sources(
			$html,
			'<h2>KBZPay를 실제 생활에서 사용하는 방식</h2><p>작성자는 미얀마에 온 직후부터 KBZPay를 사용했고, 개인적으로 한 번 또는 일상 결제에서 주로 1,000,000 MMK 미만을 결제해 왔습니다. 이 금액은 KBZPay의 공식 거래 한도가 아니라 작성자의 사용 범위입니다. 인터넷만 연결되면 대형 쇼핑몰, 마켓과 Grab 배달원에게 QR 또는 송금 방식으로 결제할 수 있어 현금을 만지는 횟수가 크게 줄었습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>상황</th><th>실제 결제 방식</th><th>확인할 점</th></tr></thead><tbody><tr><td>Grab 음식 배달</td><td>음식 50,000 + 배달비 2,000 = 배달원에게 52,000 MMK</td><td>앱 총액과 수취 계정명 확인</td></tr><tr><td>쇼핑몰·마켓</td><td>매장 QR 스캔 후 즉시 결제</td><td>완료 화면을 직원과 함께 확인</td></tr><tr><td>지하 마켓</td><td>인터넷이 약하면 결제 실패 가능</td><td>직원 Wi-Fi 또는 현금 예비수단</td></tr></tbody></table></div><p>미얀마에서 자주 접한 현금은 10,000·5,000·1,000·500·200·100짯권입니다. 신권을 구하기 어렵고 지폐 상태가 좋지 않은 경우가 있어 작성자는 KBZPay를 선호합니다. 다만 건물 지하 마켓에서는 데이터 연결이 약해 결제가 어려웠고, 직원이 제공한 Wi-Fi에 연결해 결제한 적도 있습니다. 휴대전화 배터리와 인터넷이 모두 필요하므로 소액 현금은 비상용으로 따로 준비하는 편이 안전합니다.</p>'
		);
	}
	if ( 25 === $post_id ) {
		$html = str_replace(
			'그러나 공식 안내처럼 호출 가격은 거리뿐 아니라 수요·공급과 혼잡도에 따라 바뀌므로 이 값을 정액으로 보면 안 됩니다.',
			'그러나 공식 안내처럼 호출 가격은 거리뿐 아니라 수요·공급과 혼잡도에 따라 바뀌므로 이 값을 정액으로 보면 안 됩니다. 시내에서는 비교적 새 전기차나 깔끔한 차량을 선택해 부를 수 있었지만, 외곽으로 갈수록 연식이 오래되고 상태가 좋지 않은 차량이 배정되는 경험이 많았습니다. 호출 화면에서 제공되는 차량 종류와 번호를 확인하세요.',
			$html
		);
		$html = $insert_before_sources(
			$html,
			'<h2>시내와 외곽에서 달랐던 배정 차량</h2><p>양곤 시내에서는 비교적 새 전기차가 배정되어 실내가 깔끔한 경우가 많았습니다. 반면 외곽에서는 Toyota Fielder 계열의 연식이 오래된 차량이 자주 잡혔고, 차량 내부의 청결 상태가 만족스럽지 않은 경험도 있었습니다. 이는 모든 기사와 지역에 적용되는 평가가 아니라 작성자의 반복 이용 경험입니다. 탑승 전에 차량 번호와 차종을 확인하고 상태가 불안하면 앱에서 가능한 취소·재호출 조건을 살펴보세요.</p>'
		);
	}
	if ( 35 === $post_id ) {
		$html = str_replace( '현대 H-1 실제 통근비: 연료비 월 약 2,000,000 MMK', '현대 H-1 실제 통근비: 출퇴근 연료비 월 약 1,500,000 MMK', $html );
		$html = str_replace( '회사 출퇴근을 왕복해 연료비가 월 약 <strong>2,000,000 MMK</strong> 듭니다.', '회사 출퇴근만 계산하면 연료비가 월 약 <strong>1,500,000 MMK</strong> 듭니다. 업무 중 추가 이동까지 포함하면 총연료비는 약 2,000,000 MMK 수준까지 늘 수 있습니다.', $html );
		$html = str_replace( '<td>월 연료비</td><td>약 2,000,000 MMK</td><td>출근일·유가·에어컨 사용</td>', '<td>월 연료비</td><td>출퇴근 약 1,500,000 MMK, 추가 이동 포함 시 약 2,000,000 MMK</td><td>출근일·업무 이동·유가·에어컨 사용</td>', $html );
	}
	if ( 37 === $post_id ) {
		$html = str_replace( '발전기가 없으면 4~5시간 전기 없이 지낸 경험', '2025년 시간제 공급 때 4~5시간 정전을 겪은 경험', $html );
	}
	if ( 39 === $post_id ) {
		$html = str_replace( '발전기가 없는 집은 정전 때 4~5시간을 버텨야 할 수 있습니다', '2025년 시간제 정전에서 2026년 24시간 공급으로 바뀐 경험', $html );
		$html = str_replace(
			'작성자가 거주하는 Inno City는 24시간 전력 공급과 발전기 환경을 갖췄습니다. 반면 일반 건물에서는 정전이 자주 발생했고, 발전기가 없을 때 <strong>4~5시간가량 전기 없이 지낸 경험</strong>도 있습니다. 따라서 집을 구할 때 발전기나 24시간 전력 공급 여부를 핵심 조건으로 봅니다.',
			'작성자가 양곤에서 생활한 2025년에는 오전 9시~정오, 오후 5시~9시처럼 정해진 시간에 전력 공급이 중단되는 날이 있었고 한 번에 4~5시간가량 전기 없이 지내기도 했습니다. 2026년에는 정부가 요금을 인상하는 대신 24시간 공급 체계로 전환해 생활 여건이 달라졌습니다. Inno City는 24시간 전력과 발전기 환경을 갖추고 있지만 건물별 계약과 실제 공급 상태는 입주 전에 다시 확인해야 합니다.',
			$html
		);
		$html = str_replace( '<td>4~5시간 이상 정전에도 계속 가동하는가?</td>', '<td>과거 시간제 정전과 현재 24시간 공급에서 각각 어떻게 운영하는가?</td>', $html );
	}
	if ( 41 === $post_id ) {
		$html = str_replace(
			'평균 배달비는 약 <strong>2,000 MMK</strong>로 비교적 저렴했고 자전거 배달도 이용됩니다.',
			'평균 배달비는 약 <strong>2,000 MMK</strong>로 비교적 저렴했고 자전거 배달도 이용됩니다. 저녁 7시 이후에는 이용 가능한 식당과 배달이 사실상 크게 줄어들기 때문에, 지하나 단지 안에 마트가 있는 아파트는 늦은 시간에도 식료품을 해결할 수 있어 생활권의 장점이 컸습니다.',
			$html
		);
		$html = $insert_before_sources(
			$html,
			'<h2>Grab 배달원에게 KBZPay로 결제한 실제 사례</h2><p>아파트에서 음식 배달을 자주 이용하며, 음식값이 50,000 MMK이고 배달비가 2,000 MMK라면 앱에 표시된 총 52,000 MMK를 배달원에게 KBZPay로 보냈습니다. 송금 전 배달원이 보여주는 계정명과 금액을 확인하고 완료 화면이 뜰 때까지 기다리는 편이 안전합니다. 저녁 7시가 지나면 주문 가능한 식당과 배달원이 크게 줄어 밤늦게 야식을 구하기 어려웠습니다.</p><p>이 때문에 지하나 단지 안에 마트가 있는 아파트는 생활 편의가 확실히 높았습니다. 배달이 끊긴 시간에도 식재료와 간단한 먹거리를 살 수 있기 때문입니다. 다만 지하 마트에서는 인터넷 수신이 약해 KBZPay 결제가 되지 않을 수 있으므로 직원 Wi-Fi 사용 가능 여부를 묻거나 소액 현금을 준비하세요.</p>'
		);
	}

	return $html;
}

/**
 * Add a second, topic-specific practical layer to every published guide.
 * These sections answer follow-up questions instead of repeating the article.
 */
function livingtmw_article_deep_dive( int $post_id ): string {
	switch ( $post_id ) {
		case 8:
			return '<section class="livingtmw-deep-dive"><h2>양곤 생활비 예산표를 실제로 만드는 방법</h2><p>월 생활비를 한 숫자로 정하면 어떤 항목에서 차이가 생기는지 알기 어렵습니다. 먼저 월세처럼 매달 비슷하게 나가는 고정비와 식비·교통비처럼 생활 방식에 따라 변하는 변동비를 나누세요. 여기에 병원, 비자 연장, 가전 고장, 우기 이동처럼 매달 발생하지 않지만 한 번 생기면 부담이 큰 비정기 비용을 별도로 적어야 합니다. 세 항목을 분리하면 다른 사람의 생활비 사례를 그대로 따라 하지 않고 자신의 가족 구성과 이동 거리에 맞게 조정할 수 있습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>예산 구분</th><th>포함할 항목</th><th>확인 주기</th></tr></thead><tbody><tr><td>고정비</td><td>월세, 관리비, 통신, 인터넷, 보험</td><td>계약 갱신 전</td></tr><tr><td>변동비</td><td>식비, Grab, 연료, 전기, 배달</td><td>매주 기록</td></tr><tr><td>비정기비</td><td>병원, 수리, 비자, 항공권, 이사</td><td>월별 적립</td></tr><tr><td>비상자금</td><td>환율 급변, 정전·침수 대응, 긴급 귀국</td><td>분기별 점검</td></tr></tbody></table></div><h2>첫 달과 정착 후 예산을 따로 계산하세요</h2><p>입국 첫 달에는 보증금, 선불 월세, SIM 발급, 인터넷 설치, 필터와 침구 구입처럼 반복되지 않는 비용이 몰립니다. 따라서 첫 달 지출을 평상시 월 생활비로 오해하면 장기 예산이 과도하게 보이고, 반대로 초기 비용을 빼놓으면 도착 직후 현금이 부족해질 수 있습니다. 정착비는 일회성 항목으로 분리하고 최소 두 달치 생활비와 함께 준비하는 편이 안전합니다.</p><p>정착 후에는 4주 동안 실제 사용한 MMK 금액을 기록하세요. 환율이 다른 날의 원화 환산액만 모으면 소비량과 환율 변동이 섞이므로 먼저 현지 통화로 합산한 뒤 월말의 동일한 기준 환율로 비교하는 것이 좋습니다. 카드, 계좌이체, 현금 지출을 한 표에 모으고 영수증이 없는 시장·택시 지출은 결제 직후 휴대폰 메모에 남기면 누락을 줄일 수 있습니다.</p><h2>가구 형태에 따라 달라지는 지점을 조정합니다</h2><p>1인 가구는 월세와 인터넷을 혼자 부담하지만 작은 집과 적은 식재료로 조정하기 쉽습니다. 부부나 가족은 주거 면적과 냉방 시간이 늘고 병원·학교·차량 같은 항목이 추가될 수 있습니다. 아이가 있다면 분유와 기저귀, 예방접종, 통학 차량, 한국 식품 구매 빈도를 별도 항목으로 두세요. 회사가 집이나 차량을 제공한다면 지원 종료 시점과 개인 부담 범위를 확인해야 실제 급여와 생활비를 비교할 수 있습니다.</p><div class="livingtmw-checklist"><h3>월말에 확인할 다섯 가지</h3><ul><li>예산보다 10% 이상 초과한 항목과 원인</li><li>다음 달 환율이 변해도 유지해야 하는 필수 지출</li><li>사용하지 않는 구독·통신 요금제·배달 서비스</li><li>현금으로 쓴 뒤 기록하지 않은 시장·택시 비용</li><li>병원·수리·귀국을 위한 비상자금 잔액</li></ul></div><p>생활비가 예상보다 높다고 무조건 식비부터 줄일 필요는 없습니다. 월세와 이동 동선, 에어컨 사용 시간, 수입 식품 비중처럼 반복 비용이 큰 항목을 먼저 살피는 편이 효과적입니다. 특히 저렴한 집으로 옮겼지만 출퇴근 Grab과 발전기 비용이 늘면 전체 비용은 오히려 높아질 수 있으므로 항목을 서로 연결해 판단하세요.</p></section>';

		case 11:
			return '<section class="livingtmw-deep-dive"><h2>집을 보러 갈 때 같은 조건으로 비교하는 방법</h2><p>여러 집을 하루에 보면 첫인상만 남고 실제 조건을 놓치기 쉽습니다. 방문할 때마다 같은 체크리스트를 사용해 월세, 보증금, 관리비, 전력 방식, 수질, 인터넷, 주차와 출퇴근 시간을 기록하세요. 낮에는 밝고 조용해도 퇴근 시간에는 도로가 막히거나 밤에 발전기 소음이 커질 수 있으므로 최종 후보는 다른 시간대에 한 번 더 방문하는 것이 좋습니다.</p><p>수돗물은 수도꼭지를 잠깐 여는 것만으로 판단하지 말고 몇 분간 흘려 색과 냄새, 수압 변화를 확인하세요. 샤워기와 주방 필터 규격, 온수기 작동, 배수 속도와 천장 누수 흔적도 살펴야 합니다. 정전 상황을 확인하려면 관리사무소와 기존 거주자에게 하루 평균 정전 시간, 발전기 전환 시간, 엘리베이터와 에어컨 공급 여부를 각각 물어보세요.</p><h2>계약서에는 금액보다 종료 조건이 중요합니다</h2><p>월세와 보증금이 맞더라도 중도 퇴실, 계약 갱신, 집주인의 매각, 시설 고장 책임이 불분명하면 분쟁이 생길 수 있습니다. 계약 시작일과 열쇠 인도일, 보증금 반환 기한, 공제 가능한 항목, 수리 요청에 응답할 주체를 문장으로 남기세요. 영문과 미얀마어 계약서를 함께 사용한다면 해석이 다를 때 어느 문서를 우선할지도 정해야 합니다.</p><div class="livingtmw-checklist"><h3>입주 직전 기록할 증거</h3><ul><li>가구·가전·벽·바닥 상태를 날짜가 보이게 촬영</li><li>전기와 수도 계량기 숫자를 집주인과 함께 확인</li><li>열쇠·출입카드·주차 스티커 인수 수량 기록</li><li>기존 고장과 수리 약속을 메시지와 인수인계표에 표시</li><li>월세·보증금 송금 영수증과 계약서 사본을 별도 보관</li></ul></div><p>외국인에게 익숙한 중개인이라도 집주인과의 법적 관계, 중개 수수료, 수리 권한을 확인해야 합니다. 중요한 조건은 통화 기록보다 이메일이나 메시지로 다시 정리하고, 큰 금액을 보내기 전에는 소유권과 계약 상대의 신원을 독립적으로 확인하세요. 장기 거주가 확정되지 않았다면 재판매가 필요한 매수보다 종료 조건이 명확한 임대가 위험을 줄일 수 있습니다.</p></section>';

		case 13:
			return '<section class="livingtmw-deep-dive"><h2>표시 월세 외에 추가되는 비용을 계산하세요</h2><p>매물에 적힌 월세가 같아도 실제 부담은 관리비와 전력 공급 방식에 따라 크게 달라집니다. 공용전기, 발전기 연료, 주차, 인터넷, 쓰레기 처리, 정수 필터와 에어컨 청소비가 월세에 포함되는지 항목별로 확인하세요. 달러 기준 계약은 결제일의 환율과 지폐 상태에 따라 필요한 MMK 또는 현금 준비량이 달라질 수 있으므로 적용 환율과 결제 통화도 계약서에 적는 편이 좋습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>확인 시점</th><th>해야 할 일</th></tr></thead><tbody><tr><td>계약 전</td><td>최근 3개월 전기·발전기·관리비 청구서 확인</td></tr><tr><td>입주일</td><td>계량기, 가구 상태와 열쇠 수량 기록</td></tr><tr><td>매월</td><td>청구 단가, 사용량과 납부 영수증 대조</td></tr><tr><td>퇴실 전</td><td>수리 범위와 보증금 반환일 서면 합의</td></tr></tbody></table></div><h2>선불 계약은 현금 흐름과 반환 위험을 함께 봅니다</h2><p>여러 달 월세를 선불로 요구받는 경우 월 환산액만 비교하면 안 됩니다. 계약이 중간에 끝날 때 남은 선불금이 반환되는지, 집을 사용할 수 없는 중대한 고장이 생기면 어떤 조치를 받을 수 있는지 확인하세요. 큰 금액을 한 번에 지급할수록 영수증, 계약 상대의 신원과 계좌 명의, 반환 조항이 중요합니다.</p><p>가구가 포함된 집은 편리하지만 냉장고, 세탁기, 에어컨의 노후 상태에 따라 수리비가 생길 수 있습니다. 정상 작동 여부를 입주 전에 시험하고 소모품과 고장 수리 비용을 누가 부담하는지 정하세요. 에어컨 필터 청소와 가스 충전, 정수 필터 교체처럼 주기적인 관리 항목도 임차인 부담인지 확인해야 월 지출을 정확히 예상할 수 있습니다.</p><div class="livingtmw-checklist"><h3>보증금을 지키는 퇴실 준비</h3><ul><li>퇴실 통보 기한과 방법을 계약서대로 지키기</li><li>입주 사진과 현재 상태를 같은 각도에서 비교하기</li><li>미납 공과금과 수리 공제 내역을 문서로 받기</li><li>열쇠 반환과 최종 점검 완료를 서명으로 남기기</li><li>반환 금액·통화·날짜와 송금 수수료 확인하기</li></ul></div></section>';

		case 15:
			return '<section class="livingtmw-deep-dive"><h2>전기요금 청구서를 직접 검산하는 순서</h2><p>청구서의 최종 금액만 보지 말고 이전 검침값과 현재 검침값의 차이가 실제 사용량과 맞는지 먼저 확인하세요. 계량기 번호가 자신의 세대와 일치하는지, 일반 가정용인지 24시간 전용선인지, 발전기 전력과 공용전기가 별도인지도 구분해야 합니다. 입주일과 매월 같은 날짜에 계량기를 촬영하면 사용량 급증이 에어컨 때문인지 청구 오류인지 비교하기 쉽습니다.</p><p>에어컨 소비전력은 설정 온도뿐 아니라 방 크기, 단열, 실외기 상태와 필터 청소에 영향을 받습니다. 같은 시간 동안 사용해도 문을 자주 열거나 햇빛이 강한 방은 전력 사용량이 늘 수 있습니다. 모든 냉방을 끄는 방식보다 사용하지 않는 방을 닫고 필터를 청소하며 선풍기를 함께 사용하는 편이 현실적입니다.</p><h2>정전 대비 비용도 전기 예산에 포함합니다</h2><p>정전 때 발전기가 공급하는 전기에는 별도 단가나 연료비가 붙을 수 있습니다. 발전기 전환 후 사용할 수 있는 콘센트와 에어컨 수, 엘리베이터 운영 시간, 야간 중단 여부를 관리사무소에 확인하세요. 공유기 UPS, 보조배터리와 충전식 조명은 초기 비용이 들지만 업무 중단과 통신 장애를 줄이는 데 도움이 됩니다.</p><div class="livingtmw-checklist"><h3>사용량이 갑자기 늘었을 때</h3><ul><li>계량기 번호와 검침 기간이 정확한지 확인</li><li>에어컨 필터·실외기·냉장고 문 상태 점검</li><li>발전기와 공용전기 비용이 중복 청구됐는지 질문</li><li>이전 달과 거주 인원·냉방 시간을 같은 기준으로 비교</li><li>관리사무소 답변과 수정 청구서를 문서로 보관</li></ul></div><p>전력 단가는 정책에 따라 바뀔 수 있으므로 과거 블로그 글이나 이웃의 청구서만으로 판단하지 마세요. 공식 고시와 자신의 건물 계약을 함께 보고, 이 글의 실제 사례는 사용량을 추정하는 참고값으로만 활용해야 합니다.</p></section>';

		case 17:
			return '<section class="livingtmw-deep-dive"><h2>개통 전에 휴대폰과 본인 인증 환경을 확인하세요</h2><p>eSIM은 기기가 기능을 지원하는 것만으로 충분하지 않습니다. 해외판과 통신사 잠금 여부, 동시에 사용할 수 있는 회선 수, QR 코드를 다시 설치할 수 있는지 확인하세요. 기존 한국 번호를 유지한다면 데이터 기본 회선과 문자 수신 회선을 구분하고 로밍 데이터가 실수로 사용되지 않도록 설정해야 합니다.</p><p>매장에서 개통한 직후 직원이 보는 앞에서 발신·수신, 문자, 모바일 데이터와 핫스팟을 시험하세요. 잔액 조회 코드, 데이터 패키지 만료일, 충전 방법과 고객센터 연락처를 화면 캡처로 저장하면 언어가 익숙하지 않아도 다시 확인하기 쉽습니다. 은행 계좌를 사용할 계획이면 OTP 문자가 안정적으로 도착하는지도 중요합니다.</p><h2>분실과 기기 변경에 대비합니다</h2><p>휴대폰을 잃어버리거나 초기화하면 eSIM 재발급 과정에서 여권과 본인 명의 확인이 필요할 수 있습니다. SIM 등록 명의, 전화번호, 가입한 매장과 발급 영수증을 안전한 곳에 보관하세요. 한국 번호와 미얀마 번호를 각각 어떤 계정의 복구 수단으로 사용하는지도 목록으로 만들면 메신저와 금융 계정을 잃을 위험을 줄일 수 있습니다.</p><div class="livingtmw-checklist"><h3>출국·장기 미사용 전 확인</h3><ul><li>번호 유지에 필요한 최소 충전과 유효기간</li><li>해외에서 문자 수신이 가능한지</li><li>자동 갱신되는 데이터 패키지 해지 방법</li><li>명의 변경·해지·eSIM 재발급에 필요한 서류</li><li>은행과 메신저에 등록된 번호 변경 여부</li></ul></div><p>요금이 저렴해 보여도 데이터 제공량, 속도 제한, 유효기간과 자동 갱신 조건이 다릅니다. 짧은 체류는 소량 패키지부터 사용량을 확인하고, 장기 체류는 집과 직장의 와이파이 환경을 고려해 월 요금제를 선택하세요.</p></section>';

		case 19:
			return '<section class="livingtmw-deep-dive"><h2>광고 속도보다 실제 사용 시간대를 시험하세요</h2><p>인터넷 상품의 최대 속도는 항상 보장되는 속도가 아닙니다. 설치 전에 같은 건물의 거주자에게 저녁 시간 속도와 장애 빈도를 물어보고, 가능하면 휴대폰이나 노트북으로 다운로드·업로드·지연시간을 함께 측정하세요. 영상회의와 원격 접속은 다운로드 속도만 높아도 안정적인 것이 아니므로 업로드와 패킷 손실이 중요합니다.</p><p>계약할 때 설치비, 공유기 보증금, 최소 이용기간, 이전 설치와 해지 비용을 확인하세요. 건물에 광케이블이 들어와 있어도 원하는 호수까지 배선할 수 없거나 관리사무소 승인이 필요할 수 있습니다. 설치 기사가 방문하기 전에 케이블 경로와 공유기를 둘 위치, 전원 콘센트를 정해두면 불필요한 벽 타공을 줄일 수 있습니다.</p><h2>정전과 회선 장애를 분리해서 대비합니다</h2><p>정전 때 공유기만 UPS로 켜도 건물 통신장비에 전원이 없으면 인터넷이 끊길 수 있습니다. 발전기 전환 후 통신실까지 전력이 공급되는지 관리사무소와 통신사에 확인하세요. 중요한 업무가 있다면 가정 인터넷 하나에만 의존하지 말고 다른 통신사의 휴대폰 데이터나 회사 회선을 보조 수단으로 준비하는 편이 안전합니다.</p><div class="livingtmw-checklist"><h3>장애 신고 전에 기록할 내용</h3><ul><li>장애가 시작된 시간과 정전 여부</li><li>공유기 표시등과 재부팅 전 상태 사진</li><li>유선·와이파이 각각의 속도 측정 결과</li><li>같은 건물 다른 세대의 장애 여부</li><li>접수번호, 기사 방문 약속과 복구 시간</li></ul></div><p>공유기 비밀번호는 초기값에서 바꾸고 관리자 화면도 별도 비밀번호를 사용하세요. 재택근무 장비와 개인 기기를 가능하면 분리하고, 계약 종료 시 통신사 장비 반환 여부와 보증금 정산을 확인해야 추가 비용을 피할 수 있습니다.</p></section>';

		case 21:
			return '<section class="livingtmw-deep-dive"><h2>계좌 개설 목적과 실제 사용 방식을 먼저 정하세요</h2><p>급여 수령, 생활비 송금, 현지 결제 중 무엇이 주목적인지에 따라 필요한 계좌와 서비스가 달라질 수 있습니다. 외화 입금과 MMK 출금, 해외송금 가능 여부, 모바일 결제 연결, 일일 한도와 수수료를 각각 확인하세요. 회사 소개로 개설하는 경우 퇴사 후에도 같은 조건이 유지되는지 물어보는 것이 좋습니다.</p><p>지점 방문 전 공식 고객센터에 외국인 거주자의 최신 준비 서류와 예약 필요 여부를 확인하세요. 여권 영문 이름과 현지 전화번호, 주소·재직 증빙의 표기가 서로 다르면 추가 확인이 생길 수 있습니다. 제출한 서류와 신청서 사본, 담당 지점 연락처를 보관하면 나중에 정보 변경이나 계좌 제한을 해결할 때 도움이 됩니다.</p><h2>개설 당일 소액으로 모든 기능을 시험합니다</h2><p>계좌가 만들어지면 소액 입금과 출금, 다른 계좌로 이체, ATM 카드와 모바일 앱 로그인을 차례로 시험하세요. 수취인에게 보이는 영문 계좌명과 실제 여권 이름이 일치하는지 확인하고, OTP가 도착하지 않을 때의 복구 절차도 물어보세요. 큰 금액은 기능과 한도를 확인한 뒤 나누어 이동하는 편이 안전합니다.</p><div class="livingtmw-checklist"><h3>계좌 보안을 위한 기본 설정</h3><ul><li>모바일 앱과 이메일에 서로 다른 강한 비밀번호 사용</li><li>OTP·PIN·인증 코드를 직원이나 지인에게도 공유하지 않기</li><li>이체 알림과 일일 한도를 필요한 수준으로 설정</li><li>휴대폰 분실 시 은행에 연락할 번호를 별도 보관</li><li>비자·여권·주소 변경 시 갱신 기한 확인</li></ul></div><p>다른 사람 명의 계좌를 빌리거나 출처를 설명하기 어려운 자금을 입금하면 계좌 제한과 법적 문제가 생길 수 있습니다. 환전과 송금은 허가된 경로를 이용하고 계약·급여·생활비처럼 자금 목적을 확인할 수 있는 영수증과 서류를 보관하세요.</p></section>';


		case 23:
			return '<section class="livingtmw-deep-dive"><h2>환전 금액보다 먼저 거래 경로를 확인하세요</h2><p>환율 차이가 커 보일수록 최고 숫자만 찾기 쉽지만, 실제로는 거래 장소의 허가 여부와 신변 안전, 위조지폐 위험이 더 중요합니다. 중앙은행이나 은행의 공식 공지에서 허가된 창구와 당일 환율을 확인하고, 큰 금액은 한 번에 움직이기보다 필요한 생활비 범위에서 계획적으로 환전하세요. 온라인 커뮤니티의 환율 정보는 확인 시점과 거래 조건이 다를 수 있으므로 참고값으로만 봐야 합니다.</p><p>창구에서는 매입과 매도 중 어느 환율이 적용되는지, 수수료가 별도인지, 실제 받을 MMK가 얼마인지 거래 전에 계산해 달라고 요청하세요. 영수증에는 통화, 금액, 환율, 수수료와 날짜가 표시되는지 확인하고 창구를 떠나기 전에 현금을 세어야 합니다. 다른 장소에서 다시 세다가 금액 차이를 발견하면 사실관계를 입증하기 어렵습니다.</p><h2>달러 지폐 상태와 보관 방법도 준비합니다</h2><p>같은 액면이라도 구권, 접힘, 얼룩, 작은 찢김과 필기 때문에 거절되거나 다른 조건을 제시받을 수 있습니다. 한국에서 달러를 준비할 때 가능한 한 상태가 좋은 지폐를 받고, 고무줄이나 클립으로 자국을 내지 않도록 평평하게 보관하세요. 모든 현금을 한 지갑에 넣지 말고 이동에 필요한 금액과 보관할 금액을 나누는 편이 안전합니다.</p><div class="livingtmw-checklist"><h3>환전 직전 확인표</h3><ul><li>허가된 은행·환전소와 영업시간인지</li><li>BUY·SELL 중 적용되는 환율과 별도 수수료</li><li>여권 등 필요한 신분증과 거래 한도</li><li>지폐 훼손·구권에 대한 창구 기준</li><li>거래 영수증과 실제 수령액이 일치하는지</li></ul></div><p>환율이 유리하다는 이유로 모르는 사람을 따라가거나 현금을 공개된 장소에서 세지 마세요. 환전 규정은 달라질 수 있으므로 장기 체류자는 거래 영수증을 보관하고, 사업·급여·송금처럼 자금 출처 확인이 필요한 돈은 금융기관의 안내에 따라 처리해야 합니다.</p></section>';

		case 25:
			return '<section class="livingtmw-deep-dive"><h2>호출 위치를 정확히 잡으면 불필요한 대기를 줄일 수 있습니다</h2><p>양곤의 일부 도로는 중앙분리대와 일방통행 때문에 앱 지도상 가까워도 차량이 크게 돌아와야 합니다. 쇼핑몰이나 아파트에서는 출입구 이름과 도로 방향을 확인하고, 기사에게 보낼 건물명과 짧은 위치 설명을 준비하세요. GPS 핀이 건물 안쪽에 잡히면 차량이 들어올 수 있는 큰 도로 쪽 승차 지점으로 옮기는 편이 좋습니다.</p><p>탑승 전 차량 번호와 차종, 기사 사진을 앱 정보와 대조하세요. 일치하지 않는 차량에 타거나 앱 밖에서 요금을 다시 협상하자는 요청을 받을 경우 호출을 취소하고 안전한 곳에서 다시 확인해야 합니다. 혼자 이동할 때는 차량 정보와 실시간 경로를 가족이나 동료에게 공유하고 휴대폰 배터리를 충분히 남겨두세요.</p><h2>시간대별 요금과 이동 시간을 기록해 보세요</h2><p>출퇴근, 우천과 공항 이동은 평소보다 오래 걸리고 호출 가격도 달라질 수 있습니다. 집이나 직장을 정하기 전 같은 경로를 아침과 저녁에 각각 호출해 실제 요금과 시간을 기록하면 월 교통비를 더 정확히 계산할 수 있습니다. 중요한 약속이 있다면 예상 도착시간만 믿지 말고 침수와 정체를 고려한 여유 시간을 두세요.</p><div class="livingtmw-checklist"><h3>탑승과 하차 시 확인</h3><ul><li>앱의 차량 번호·기사·결제수단 대조</li><li>목적지와 예상 경로를 출발 전에 확인</li><li>현금 결제용 잔돈과 휴대폰 배터리 준비</li><li>도착 후 앱 완료 금액과 실제 지급액 비교</li><li>분실물 발생 시 확인할 영수증·이용기록 보관</li></ul></div><p>현금 결제에서는 큰 지폐의 거스름돈이 부족할 수 있습니다. 출발 전에 결제수단을 확인하고, 앱에 표시되지 않은 추가 비용을 요청받으면 항목과 이유를 먼저 물어보세요. 안전 문제가 생기면 차량 안에서 논쟁하기보다 밝고 사람이 있는 장소에서 내린 뒤 앱 고객지원과 필요한 기관에 기록을 남기는 편이 좋습니다.</p></section>';

		case 27:
			return '<section class="livingtmw-deep-dive"><h2>첫 장보기는 일주일치 기준표를 만드는 과정입니다</h2><p>처음부터 많은 양을 사기보다 자주 먹는 품목의 가격과 품질을 비교하는 데 집중하세요. 쌀, 달걀, 고기, 채소, 생수, 세제처럼 반복 구매하는 품목을 정하고 대형마트·시장·배달의 가격과 이동비를 함께 기록하면 자신의 생활 동선에 맞는 구매처가 보입니다. 상품 가격이 조금 싸더라도 Grab 왕복 비용과 시간이 더 들면 실제 절약이 아닐 수 있습니다.</p><p>수입 식품은 환율과 재고에 따라 가격이 크게 달라지고 다음 방문 때 품절될 수 있습니다. 오래 보관되는 양념은 소비 속도를 확인한 뒤 여유분을 사되, 냉장·냉동 제품은 정전과 보관 공간을 고려하세요. 현지 대체품은 작은 용량부터 맛과 품질을 시험하면 실패 비용을 줄일 수 있습니다.</p><h2>식품 안전은 매장 종류와 관계없이 직접 확인합니다</h2><p>포장 식품은 소비기한, 포장 팽창과 냉장고 온도를 보고 고기와 냉동식품은 장보기 마지막에 담으세요. 시장의 과일과 채소는 상처와 곰팡이를 확인하고 귀가 후 바로 선별·세척합니다. 배달은 도착 시간이 길어질 수 있으므로 냉동 상태가 풀렸거나 포장이 손상된 상품은 사진을 찍고 앱의 환불·교환 절차를 확인하세요.</p><div class="livingtmw-checklist"><h3>주간 장보기 기록 항목</h3><ul><li>상품 가격뿐 아니라 교통비와 배달비</li><li>구매량 중 실제 소비한 양과 버린 양</li><li>정전 후 냉장·냉동 상태 변화</li><li>대체 상품 허용 여부와 환불 결과</li><li>다음 주에 반복 구매할 품목과 구매처</li></ul></div><p>생수와 쌀처럼 무거운 품목은 정기 배달을 이용하고, 신선식품은 자주 소량 구매하는 방식이 편할 수 있습니다. 가족 수와 요리 빈도에 따라 최적의 구매처가 달라지므로 다른 사람의 추천 목록보다 자신의 일주일 소비 기록을 기준으로 조정하세요.</p></section>';

		case 29:
			return '<section class="livingtmw-deep-dive"><h2>쇼핑 장소는 품목과 이동 목적에 따라 선택합니다</h2><p>대형마트는 가격표와 포장 상품을 비교하기 쉽고 카드나 모바일 결제를 지원하는 경우가 많지만 수입품 가격이 높을 수 있습니다. 현지 시장은 채소와 과일을 소량 구매하기 좋지만 가격, 위생과 품질을 직접 판단해야 합니다. 쇼핑몰은 식사와 생활용품 구매를 한 번에 해결하기 편한 대신 이동 시간과 충동 구매까지 고려해야 합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>방문 목적</th><th>우선 확인할 것</th></tr></thead><tbody><tr><td>주간 식료품</td><td>냉장 상태, 반복 품목 가격, 집까지 이동</td></tr><tr><td>수입 생활용품</td><td>재고, 유통기한, 현지 대체품</td></tr><tr><td>시장 신선식품</td><td>당일 가격, 무게, 세척·보관</td></tr><tr><td>가족 외출</td><td>주차·Grab 승차장, 식당, 혼잡 시간</td></tr></tbody></table></div><h2>가격 비교는 같은 규격으로 해야 합니다</h2><p>상품 하나의 표시 가격만 보지 말고 용량당 가격과 유통기한을 비교하세요. 할인 묶음 상품은 모두 소비하기 전에 품질이 떨어지면 절약이 아닙니다. 수입품의 한국 가격을 그대로 기준으로 삼기보다 현지에서 구할 수 있는 대체 브랜드와 사용 목적을 비교하면 생활비를 줄이기 쉽습니다.</p><p>방문 전 공식 매장 목록과 지도에서 영업 여부를 확인하되 온라인 정보만 믿지 마세요. 지점 이전, 재고와 영업시간은 달라질 수 있습니다. 꼭 필요한 상품은 전화나 매장 채널로 재고를 확인하고, 장거리 이동이라면 다른 구매 목적을 함께 묶는 편이 효율적입니다.</p><div class="livingtmw-checklist"><h3>결제 후 바로 확인할 것</h3><ul><li>가격표와 영수증 금액·할인이 일치하는지</li><li>카드·모바일 결제가 중복 승인되지 않았는지</li><li>냉장 상품과 깨지기 쉬운 상품 포장 상태</li><li>교환·환불 기한과 필요한 영수증</li><li>Grab 승차 위치와 무거운 짐 이동 방법</li></ul></div></section>';

		case 31:
			return '<section class="livingtmw-deep-dive"><h2>한국 식재료는 대체하기 어려운 품목부터 정합니다</h2><p>모든 재료를 한국 제품으로 준비하면 비용과 보관 부담이 커집니다. 고추장, 된장, 김과 같이 맛의 기준이 되고 오래 보관되는 품목은 수입 제품을 선택하고, 달걀·고기·채소·과일은 현지 상품을 비교하는 방식이 현실적입니다. 자주 만들 메뉴를 먼저 정하면 실제로 사용하지 않을 소스와 간식을 충동적으로 사는 일을 줄일 수 있습니다.</p><p>한국에서 가져올 때는 항공 수하물과 현지 반입 규정을 확인하고 액체·발효 식품이 새지 않도록 원래 포장을 유지하세요. 처방 없이 반입하기 어려운 식품이나 동물성 제품이 있을 수 있으므로 최신 세관 안내를 확인해야 합니다. 현지에서 쉽게 살 수 있는 라면과 과자를 무겁게 가져오는 것보다 개인 취향이 강한 소량의 양념을 우선하는 편이 낫습니다.</p><h2>재고가 없을 때 사용할 대체표를 만들어 보세요</h2><p>한국 무와 배추가 없으면 현지 채소의 수분과 익는 시간을 먼저 시험하고, 고기 부위 이름은 사진과 원하는 조리법을 함께 보여주면 주문하기 쉽습니다. 처음 사용하는 재료는 한 번 요리할 양만 구매해 맛과 손질 난이도를 확인하세요. 성공한 대체 재료와 구매처를 메모하면 다음 장보기가 빨라집니다.</p><div class="livingtmw-checklist"><h3>수입 식품 구매 전 확인</h3><ul><li>한글 라벨과 원래 포장, 소비기한</li><li>냉장·냉동 유지 상태와 정전 가능성</li><li>가족이 기간 안에 소비할 수 있는 양인지</li><li>같은 용도의 현지 제품과 용량당 가격</li><li>알레르기 성분과 조리·보관 방법</li></ul></div><p>자주 품절되는 상품은 무조건 대량 구매하기보다 보관 전력과 유통기한을 계산하세요. 냉동고에 많이 쌓아두면 장시간 정전 때 손실이 커질 수 있습니다. 한 달 식단에서 한국 음식과 현지 음식을 적절히 나누면 비용뿐 아니라 재료 수급 스트레스도 줄일 수 있습니다.</p></section>';

		case 33:
			return '<section class="livingtmw-deep-dive"><h2>직접 운전하기 전에 현지 도로를 관찰하세요</h2><p>양곤의 차선 흐름, 교차로 진입과 보행자·오토바이 움직임은 한국에서 익숙한 방식과 다르게 느껴질 수 있습니다. 도착 직후에는 기사 차량이나 Grab을 이용해 출퇴근 경로를 여러 시간대에 관찰하고, 우회전·유턴·주차가 어려운 지점을 기록하세요. 지도상 거리보다 실제 소요 시간과 운전 피로가 차량 선택에 더 중요할 수 있습니다.</p><p>운전면허와 국제운전면허 사용 가능 여부는 체류 자격과 최신 행정 절차를 관계 기관에 확인해야 합니다. 렌터카나 회사 차량을 이용할 때는 차량 등록, 의무 보험과 운전자 범위, 사고 시 자기부담금, 국경·지역 이동 제한을 계약서에서 확인하세요. 다른 사람이 가능하다고 말한 경험만으로 법적 자격을 판단하면 안 됩니다.</p><h2>우기와 야간 운전 계획을 따로 세웁니다</h2><p>강한 비에는 차선과 도로 파손이 보이지 않고 침수 깊이를 판단하기 어렵습니다. 앞차가 통과했다고 따라 들어가지 말고 수심을 모르는 구간은 우회하세요. 야간에는 조명이 부족한 도로와 정차 차량, 보행자를 늦게 발견할 수 있으므로 속도를 낮추고 전조등·와이퍼·타이어 상태를 출발 전에 확인해야 합니다.</p><div class="livingtmw-checklist"><h3>사고 발생 시 기본 대응</h3><ul><li>먼저 사람의 안전과 추가 충돌 위험 확인</li><li>차량 위치·손상·도로 상황을 여러 각도에서 촬영</li><li>상대 차량 정보와 연락처, 목격자 기록</li><li>보험사·회사 담당자·필요한 기관에 즉시 연락</li><li>이해하지 못한 문서에 서명하거나 현금 합의하지 않기</li></ul></div><p>차량에는 우산, 충전기, 손전등, 비상 삼각대와 기본 구급용품을 준비하세요. 정비 이력이 불분명한 중고차는 가격보다 브레이크, 타이어, 냉각 계통과 에어컨을 독립 정비소에서 점검하는 편이 안전합니다.</p></section>';

		case 35:
			return '<section class="livingtmw-deep-dive"><h2>차량 유지비는 연료비와 비정기 수리비를 분리합니다</h2><p>월 연료비만 보면 차량 비용을 과소평가하기 쉽습니다. 엔진오일과 필터 같은 정기 소모품, 타이어·배터리·브레이크, 보험과 등록, 주차·세차, 갑작스러운 고장 수리를 별도 항목으로 기록하세요. 오래된 차량은 매월 같은 수리비가 드는 것이 아니므로 최근 12개월 지출을 합산해 월평균으로 나누는 편이 현실적입니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>비용 성격</th><th>기록 방법</th></tr></thead><tbody><tr><td>연료</td><td>주행거리·주유량·금액을 매번 기록</td></tr><tr><td>정기 정비</td><td>교환 주기와 다음 예정일 표시</td></tr><tr><td>소모·고장</td><td>부품값·공임·차량 중단일 분리</td></tr><tr><td>간접비</td><td>주차, 통행, 세차, 대체 교통비 포함</td></tr></tbody></table></div><h2>출퇴근 동선으로 실연비를 계산하세요</h2><p>공인 연비나 다른 운전자의 사례보다 자신의 경로에서 사용한 연료가 중요합니다. 주유할 때 총주행거리와 주유량을 기록하고, 에어컨 사용과 정체가 심했던 날을 표시하세요. 우회와 공회전이 많은 양곤에서는 같은 거리라도 연료 소비와 운전 시간이 크게 달라질 수 있습니다.</p><p>회사 통근 차량이라면 운전기사 비용, 초과근무, 주말 사용과 차량이 고장 났을 때 대체 교통비를 누가 부담하는지 정해야 합니다. 가족과 함께 쓰는 차량은 출퇴근 외 병원·학교·장보기 거리를 포함해 월 주행거리를 계산하세요. 장거리 통근이 계속된다면 집을 직장 가까이 옮길 때의 월세 차이와 차량비 절감액을 함께 비교할 필요가 있습니다.</p><div class="livingtmw-checklist"><h3>정비소에 맡기기 전 준비</h3><ul><li>증상이 발생하는 속도·시간·소리를 기록</li><li>교체 예정 부품과 공임 견적을 구분해 받기</li><li>부품의 제조사·신품·중고 여부 확인</li><li>교체한 기존 부품과 작업 영수증 요청</li><li>수리 후 같은 조건에서 재시험하기</li></ul></div><p>수리비가 차량 가치와 비교해 계속 커진다면 한 번의 큰 고장뿐 아니라 운행 중단과 안전 위험을 함께 판단하세요. 가장 저렴한 수리만 반복하기보다 신뢰할 수 있는 정비 이력과 부품 수급 가능성을 기준으로 차량 유지 또는 교체를 결정하는 편이 좋습니다.</p></section>';
	}

	return '';
}

/** Return an additional topic-specific field note for each published guide. */
function livingtmw_article_field_note( int $post_id ): string {
	$notes = array(
		8  => '<section class="livingtmw-field-note"><h2>예산이 흔들릴 때 조정하는 순서</h2><p>실제 생활에서는 환율, 우기 교통비, 전기 사용량 때문에 계획과 지출이 정확히 맞지 않습니다. 이때 식비부터 무조건 줄이기보다 최근 4주 지출을 고정비·필수 변동비·선택 지출로 나누세요. 집세와 비자 비용처럼 당장 바꾸기 어려운 항목은 그대로 두고, 배달 횟수·택시 이동·구독 서비스처럼 선택 가능한 항목의 상한을 정하는 편이 오래 유지됩니다. 생활비를 받은 날에는 집세, 공과금 예상액, 비상금을 먼저 분리하고 남은 금액만 주 단위로 나누면 월말 부족을 줄일 수 있습니다.</p><p>예산표에는 금액뿐 아니라 결제 통화도 함께 기록하세요. 달러로 정한 집세를 짯으로 낼 때 적용한 환율, 카드 해외 결제 수수료, 현금 인출 수수료가 서로 다르기 때문입니다. 큰 금액은 결제 전 환율과 수수료를 확인하고 영수증이나 송금 화면을 보관합니다. 한 달 뒤에는 계획 대비 차이가 컸던 세 항목만 골라 다음 달 기준을 수정하세요. 모든 항목을 한꺼번에 바꾸기보다 오차가 큰 곳부터 조정해야 자신의 생활 방식에 맞는 예산이 만들어집니다.</p><h2>처음 3개월에 따로 잡을 비용</h2><p>정착 초기에는 보증금, 중개 수수료, 침구와 조리도구, 유심, 비자 관련 이동처럼 매달 반복되지 않는 지출이 몰립니다. 이를 평소 생활비와 섞으면 월 지출을 과대평가하기 쉽습니다. 일회성 정착비를 따로 기록하고 구입 날짜와 예상 사용 기간을 적어 두세요. 가전이나 가구는 집에 포함된 품목과 수리 책임을 먼저 확인하고, 이사 가능성이 있다면 중고 처분 가능성도 고려합니다.</p>',
		11 => '<section class="livingtmw-field-note"><h2>집을 두 번째로 볼 때 확인할 것</h2><p>첫 방문에서는 채광과 인테리어에 눈이 가기 쉬우므로 계약 전에는 다른 시간대에 한 번 더 방문하는 것이 좋습니다. 평일 저녁의 교통과 소음, 휴대전화 수신, 수압과 온수, 발전기 전환 시 작동하는 콘센트를 직접 확인하세요. 관리사무소에는 정전 대응 시간, 쓰레기 배출, 방문객 등록 방법을 묻습니다. 사진과 메모를 같은 순서로 남기면 여러 집을 비교할 때 기억에 의존하지 않아도 됩니다.</p>',
		13 => '<section class="livingtmw-field-note"><h2>월세 외 비용까지 비교하는 방법</h2><p>표시된 임대료가 낮아도 관리비, 발전기 연료비, 인터넷, 주차비를 더하면 총액이 달라집니다. 후보마다 같은 표를 만들어 월 고정비와 예상 전기료를 합산하고 선납 기간 동안 묶이는 현금도 비교하세요. 계약서에는 수리 요청 창구와 비용 부담, 보증금 반환 시점, 중도 퇴거 조건을 적고 구두 합의는 메시지나 특약으로 남깁니다. 입주일에는 계량기와 기존 흠집을 촬영해 집주인에게 공유하세요.</p>',
		15 => '<section class="livingtmw-field-note"><h2>전기료 이상을 찾는 기록법</h2><p>매달 같은 날짜에 계량기 숫자를 촬영하고 청구서의 이전·현재 검침값과 대조하세요. 에어컨 사용 시간, 장기 외출, 정전과 발전기 사용 여부도 메모하면 사용량 급증 이유를 찾기 쉽습니다. 집을 비웠는데 소비량이 높다면 대기전력, 공용 설비 연결, 검침 오류 가능성을 관리인과 확인합니다. 요금만 비교하지 말고 사용량 단위를 함께 봐야 단가 상승과 실제 소비 증가를 구분할 수 있습니다.</p>',
		17 => '<section class="livingtmw-field-note"><h2>유심 교체 전 계정 보호</h2><p>번호를 바꾸기 전 은행, 메신저, 이메일의 복구 번호와 2단계 인증을 먼저 확인하세요. 기존 유심은 모든 변경이 끝날 때까지 보관하고 충전 영수증과 등록 정보를 남깁니다. 데이터가 빨리 소진되면 앱별 사용량에서 자동 업데이트와 동영상 재생을 점검하세요. 지방 이동 전에는 해당 지역의 통신 품질을 확인하고 중요한 업무가 있다면 다른 통신사의 보조 회선을 준비하는 편이 안전합니다.</p>',
		19 => '<section class="livingtmw-field-note"><h2>인터넷 설치 전 물어볼 항목</h2><p>신청할 때 설치비, 장비 보증금, 최소 사용 기간, 해지 시 반납 품목을 함께 확인하세요. 공유기와 작업 공간 사이에 벽이 많다면 추가 장비 비용도 묻습니다. 개통 직후에는 유선과 와이파이 속도를 각각 측정해 저장하세요. 장애 신고에는 발생 시각, 공유기 표시등 상태, 특정 기기만의 문제인지 전달해야 원인 확인이 빨라집니다.</p>',
		21 => '<section class="livingtmw-field-note"><h2>은행 업무 기록을 남기는 법</h2><p>계좌 개설과 송금 때 안내받은 수수료, 한도, 필요 서류는 담당 지점과 날짜를 붙여 기록하세요. 규정과 창구 안내가 달라질 수 있어 큰 거래 전에는 최신 조건을 다시 확인해야 합니다. 송금 화면에는 거래 번호와 수취인 정보가 보이게 저장하되 비밀번호와 일회용 인증번호는 보내지 마세요. 휴대전화 분실에 대비해 고객센터와 카드 정지 방법도 별도로 보관합니다.</p>',
		23 => '<section class="livingtmw-field-note"><h2>환전 결과를 정확히 비교하려면</h2><p>간판 환율만 보지 말고 최종적으로 받은 짯 금액을 기준으로 비교하세요. 지폐 상태와 권종에 따라 조건이 달라질 수 있으므로 돈을 건네기 전에 총수령액을 확인하고 상대방 앞에서 다시 계산합니다. 큰돈을 한 번에 바꾸기보다 필요 금액을 나누면 환율 변동과 현금 보관 위험을 줄일 수 있습니다. 거래 장소와 영수증을 기록하면 다음 환전 때 실제 조건도 비교하기 쉽습니다.</p>',
		25 => '<section class="livingtmw-field-note"><h2>택시 탑승 전 확인</h2><p>호출 화면의 차량 번호와 기사 정보를 실제 차량과 대조하고 목적지는 지도 핀과 건물 이름을 함께 보여 주세요. 침수나 통행 제한으로 예상 경로가 크게 달라지면 출발 전에 이유를 묻습니다. 현금 결제는 잔돈을 준비하고 하차 전 좌석과 바닥을 확인하세요. 분실물이나 요금 문제가 생기면 호출 기록, 탑승 시각, 차량 번호가 필요하므로 기록을 바로 삭제하지 않는 것이 좋습니다.</p>',
		27 => '<section class="livingtmw-field-note"><h2>장보기 기록으로 낭비 줄이기</h2><p>자주 사는 품목 10개의 가격과 구입처를 기록하면 시장과 마트의 장점을 현실적으로 비교할 수 있습니다. 신선식품은 단가보다 손질 후 실제 먹는 양과 보관 기간이 중요합니다. 정전 가능성을 고려해 과도하게 사지 말고 육류와 유제품은 이동 마지막에 구입하세요. 다음 목록을 만들 때 남은 재료를 먼저 확인하면 중복 구매와 음식물 낭비를 함께 줄일 수 있습니다.</p>',
		29 => '<section class="livingtmw-field-note"><h2>가격보다 먼저 볼 구매 조건</h2><p>수입품과 전자제품은 같은 모델처럼 보여도 보증 범위와 구성품이 다를 수 있습니다. 결제 전 교환 기간, 영수증 필요 여부, 공식 서비스센터를 확인하고 제품 번호를 촬영하세요. 온라인 판매자는 후기 개수보다 최근 후기, 실제 사진, 반품 절차를 살펴봅니다. 고가 물품은 판매자 메시지, 결제 내역, 개봉 직후 상태를 보관하면 하자 발생 시 설명하기 쉽습니다.</p>',
		31 => '<section class="livingtmw-field-note"><h2>한국 식재료 대체 기준</h2><p>상표가 익숙하지 않을 때는 제품명보다 원재료, 당도, 염도, 중량을 비교하세요. 고추장이나 간장처럼 맛의 중심이 되는 재료는 작은 용량으로 먼저 시험하고 채소는 현지 제철 품목 중 조리 후 식감이 비슷한 것을 찾습니다. 냉동·냉장 제품은 매장 보관 상태와 이동 시간을 고려해 마지막에 담으세요. 성공한 대체 품목과 조리 비율을 기록하면 다음 장보기 비용과 시간을 줄일 수 있습니다.</p>',
		33 => '<section class="livingtmw-field-note"><h2>운전 전 1분 점검</h2><p>출발 전에 타이어, 연료, 전조등, 휴대전화 배터리를 확인하고 우기에는 와이퍼와 침수 예상 구간을 추가로 살피세요. 목적지는 운전 중 조작하지 않도록 미리 설정합니다. 접촉 사고가 나면 안전한 위치를 확보한 뒤 차량 위치, 손상 부위, 상대 차량 번호를 촬영하고 보험 절차를 따르세요. 이해하지 못한 문서에는 바로 서명하지 말고 통역이나 보험 담당자의 도움을 받습니다.</p>',
		35 => '<section class="livingtmw-field-note"><h2>차량 보유비를 월 단위로 계산하기</h2><p>구매 가격만 비교하면 보험, 등록, 정비, 주차, 연료를 놓치기 쉽습니다. 연간 예상비용을 12개월로 나누고 타이어와 배터리 같은 비정기 교체비도 별도 적립하세요. 중고차는 정비 이력, 차대번호, 소유권 이전 가능 여부를 확인하고 시운전 결과를 기록합니다. 예상 보유 기간 뒤 판매 가격까지 보수적으로 잡으면 택시 이용과 차량 구매 중 자신의 이동 패턴에 맞는 선택을 판단하기 쉬워집니다.</p>',
	);

	return $notes[ $post_id ] ?? '';
}

/** Small final clarifications for guides whose original articles were shortest. */
function livingtmw_article_clarification( int $post_id ): string {
	$clarifications = array(
		8  => '<p class="livingtmw-clarification"><strong>비상금 기준:</strong> 평소 생활비와 분리해 최소 한 달의 집세·식비·교통비를 바로 사용할 수 있는 형태로 준비하세요. 병원 진료, 항공권 변경, 장기 정전 때의 숙박처럼 한 번에 큰돈이 필요한 상황을 가정합니다. 전액을 현금으로 보관하기보다 현지 결제수단과 해외에서 접근 가능한 수단을 나누고, 실제 사용했다면 다음 달 선택 지출보다 먼저 채우는 규칙을 정하는 것이 좋습니다.</p>',
		11 => '<p class="livingtmw-clarification">엘리베이터와 급수 펌프가 정전 때 작동하는지도 확인하세요. 고층일수록 발전기 범위가 일상 편의에 직접 영향을 줍니다. 반려동물, 가구 이동, 단기 방문객처럼 생활 방식과 관련된 제한은 계약 직전에 묻지 말고 후보를 비교하는 단계에서 확인해야 시간과 비용을 아낄 수 있습니다.</p>',
		13 => '<p class="livingtmw-clarification">외화로 임대료를 정했다면 실제 납부일에 어느 기관의 환율을 적용하는지 계약서에 적으세요. 납부 확인서는 매달 같은 방식으로 보관하고 현금 지급이라면 집주인의 서명과 날짜를 받습니다. 갱신 협의 시작 시점도 미리 정해 두면 계약 종료 직전의 급한 이사를 피할 수 있습니다.</p>',
		15 => '<p class="livingtmw-clarification">에어컨 필터 청소와 냉장고 문 패킹처럼 작은 관리도 소비 전력에 영향을 줍니다. 멀티탭 하나에 고출력 기기를 몰아 연결하지 말고 집의 전압 변동이 잦다면 관리인이나 전기 기술자에게 안전 상태를 확인하세요. 비용 절감보다 화재와 기기 손상을 예방하는 것이 우선입니다.</p>',
		17 => '<p class="livingtmw-clarification">유심의 유효기간과 번호 유지 조건도 일정표에 적어 두세요. 장기 출국 중 충전이나 사용 기록이 없어 번호가 정지되면 연결된 금융 계정 복구가 어려워질 수 있습니다. 고객센터 안내를 확인하고 출국 전 필요한 유지 조치를 끝내는 편이 안전합니다.</p>',
		19 => '<p class="livingtmw-clarification">재택근무가 중요하다면 한 회사의 최고 속도 상품보다 장애 때 사용할 휴대전화 테더링과 보조 전원을 함께 준비하세요. 실제 품질은 건물 배선과 시간대에도 영향을 받으므로 이웃의 최근 사용 경험을 확인하는 것이 유용합니다.</p>',
	);

	return $clarifications[ $post_id ] ?? '';
}

/** Detailed, non-repeating additions for the next six prepared articles. */
function livingtmw_upcoming_article_deep_dive( int $post_id ): string {
	$sections = array(
		37 => '<section class="livingtmw-deep-dive"><h2>우기 출근 계획은 전날부터 준비합니다</h2><p>비 예보가 있으면 평소 경로 하나만 믿지 말고 고가도로와 큰 도로를 중심으로 대체 경로를 저장하세요. 회사와 학교에는 침수 시 연락할 담당자와 재택·지각 처리 방법을 미리 확인합니다. 출발 직전에는 지도 앱뿐 아니라 거주지 관리실, 동료와 운전기사에게 실제 도로 상태를 물어보는 편이 정확합니다.</p><div class="livingtmw-checklist"><h3>우기 이동 가방</h3><ul><li>충전된 보조배터리와 방수 주머니</li><li>식수, 복용약과 간단한 간식</li><li>우산·얇은 우의와 여분 양말</li><li>보험·회사·가족의 비상 연락처</li></ul></div><p>차량 바닥까지 물이 올라오거나 수심을 알 수 없다면 진입하지 마세요. 침수 뒤에는 브레이크와 전기장치를 점검하고, 집에 돌아온 뒤 젖은 신발과 옷은 바로 세척·건조합니다. 일정 지연보다 사람과 차량의 안전을 우선하는 기준을 가족과 공유해 두는 것이 중요합니다.</p></section>',
		39 => '<section class="livingtmw-deep-dive"><h2>정전 대비는 전력 우선순위를 정하는 일입니다</h2><p>모든 기기를 오래 가동하려 하기보다 조명, 휴대전화, 인터넷, 냉장 보관 중 무엇이 꼭 필요한지 먼저 정하세요. 공유기용 UPS는 소비전력과 배터리 지속시간을 확인하고, 대용량 보조전원은 충전 위치와 환기·과열 안전수칙을 지켜야 합니다. 정전이 예고되면 냉장고 온도를 미리 낮추고 문을 자주 열지 않습니다.</p><div class="livingtmw-checklist"><h3>월 1회 정전 준비 점검</h3><ul><li>손전등·보조배터리 충전 상태</li><li>비상 식수와 상온 보관 식품의 기한</li><li>발전기 요금과 가동 공지 확인 방법</li><li>엘리베이터 중단 시 가족 이동 계획</li></ul></div><p>발전기 비용은 관리비에 포함되는지, 세대별 사용량으로 계산하는지 확인해 영수증을 보관하세요. 의료기기나 냉장 의약품을 사용한다면 일반적인 준비만으로 부족할 수 있으므로 병원과 관리실에 별도의 비상 전원 계획을 문의해야 합니다.</p></section>',
		41 => '<section class="livingtmw-deep-dive"><h2>배달 주문은 주소 설명에서 절반이 결정됩니다</h2><p>도로명만 입력하기보다 콘도 이름, 출입구, 가까운 랜드마크와 연락 가능한 번호를 함께 적으세요. 관리실 등록이나 로비 수령 규칙이 있으면 주문 메모에 넣습니다. 비가 심한 날에는 예상 시간이 늘고 취소될 수 있으므로 중요한 식사는 늦기 전에 주문하고 상온 보관 식품을 비상용으로 준비하는 편이 좋습니다.</p><div class="livingtmw-checklist"><h3>수령 즉시 확인</h3><ul><li>주문한 메뉴와 수량, 밀봉 상태</li><li>뜨거운 음식과 차가운 음식의 온도</li><li>영수증·할인·배달비의 일치 여부</li><li>누락·파손 사진과 앱 문의 기한</li></ul></div><p>현금 주문은 정확한 금액에 가까운 지폐를 준비하고 비대면으로 돈을 방치하지 마세요. 알레르기가 있다면 요청사항만 믿지 말고 도착 후 재료를 확인합니다. 반복 주문할 식당은 맛뿐 아니라 포장, 배달시간, 문제 대응 결과까지 기록하면 실패를 줄일 수 있습니다.</p></section>',
		43 => '<section class="livingtmw-deep-dive"><h2>도착 첫 주는 날짜별로 나누면 수월합니다</h2><p>첫날에는 생수, 통신, 잠자리와 집의 전기·수도부터 해결하고, 둘째 날부터 마트·병원·출퇴근 동선을 확인하세요. 모든 생활용품을 한꺼번에 사면 집에 이미 포함된 물건과 중복되기 쉽습니다. 일주일 동안 실제로 불편했던 항목을 기록한 뒤 가구와 가전을 결정하면 이사 비용도 줄어듭니다.</p><div class="livingtmw-checklist"><h3>중요 자료 백업</h3><ul><li>여권·비자·보험 서류 사본</li><li>집주인·관리실·회사 비상 연락처</li><li>처방약 성분명과 가까운 병원 위치</li><li>집 계약서와 입주 상태 사진</li></ul></div><p>현금과 카드는 한곳에 두지 말고 소액 결제로 각각 정상 작동하는지 시험하세요. 가족 구성원 모두가 집 주소를 현지어와 영어로 보여줄 수 있게 저장하고, 휴대전화가 꺼졌을 때 만날 장소도 정합니다. 정착 준비는 물건 목록보다 연락·이동·결제 수단을 실제로 시험하는 과정에 가깝습니다.</p></section>',
		45 => '<section class="livingtmw-deep-dive"><h2>병원 방문 전 증상을 한 장으로 정리합니다</h2><p>증상이 시작된 날짜, 체온, 복용 중인 약, 알레르기와 과거 진단을 한국어와 영어 성분명으로 준비하세요. 예약할 때 진료과, 통역 가능 여부, 접수비와 검사비 결제 통화를 확인합니다. 응급 증상은 비용 비교를 위해 기다리지 말고 즉시 현지 응급기관의 안내를 따라야 합니다.</p><div class="livingtmw-checklist"><h3>진료 후 받아둘 자료</h3><ul><li>영문 진단서와 검사 결과 원본</li><li>약 이름·용량·복용 기간이 적힌 처방전</li><li>항목별 영수증과 결제 증빙</li><li>재방문 시점과 악화 시 행동 지침</li></ul></div><p>처방약은 포장과 설명서를 함께 보관하고 다른 사람의 약을 나눠 먹지 마세요. 설명을 이해하지 못했다면 복용 전에 병원이나 약사에게 다시 확인합니다. 이 글의 비용은 작성자 경험일 뿐 질환별 예상액이 아니므로 보험 청구 조건과 보장 병원은 가입한 보험사에 직접 문의해야 합니다.</p></section>',
		47 => '<section class="livingtmw-deep-dive"><h2>생활비 비교는 같은 생활 수준으로 맞춥니다</h2><p>한국의 대중교통 생활과 양곤의 차량 통근, 한국 원룸과 발전기가 있는 외국인 선호 콘도를 그대로 비교하면 결론이 왜곡됩니다. 가족 수, 주거 면적, 통근거리, 외식 횟수, 에어컨 사용시간을 같은 조건으로 정한 뒤 월세·공과금·교통·식비를 합산하세요. 일회성 이사비와 비자 비용은 월 생활비와 분리합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>비교 항목</th><th>같이 기록할 조건</th></tr></thead><tbody><tr><td>주거</td><td>면적, 가구, 전력과 관리비</td></tr><tr><td>교통</td><td>월 이동거리와 소요시간</td></tr><tr><td>식비</td><td>현지식·한식·배달 횟수</td></tr><tr><td>환율</td><td>계산 날짜와 실제 수수료</td></tr></tbody></table></div><p>짯 가격을 원화로 바꿀 때는 체감 환율 하나를 장기간 고정하지 말고 비교일의 실제 환전 조건을 사용하세요. 저렴한 항목만 모아 전체 생활비가 싸다고 결론 내리기보다 의료, 교육, 정전 대비와 귀국 비용처럼 빈도는 낮지만 큰 지출도 별도 비상예산에 포함해야 현실적인 비교가 됩니다.</p></section>',
	);

	return $sections[ $post_id ] ?? '';
}

/** Show an editorial quality audit for every current and future blog post. */
function livingtmw_render_content_quality_audit( WP_Post $post ): void {
	$text       = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $post->post_content ) ) );
	$word_count = '' === $text ? 0 : count( preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY ) );
	$checks     = array(
		'본문 600어절 이상'       => $word_count >= 600,
		'소제목 3개 이상'         => substr_count( strtolower( $post->post_content ), '<h2' ) >= 3,
		'목록 또는 체크리스트'    => (bool) preg_match( '/<(ul|ol)\b/i', $post->post_content ),
		'출처 링크 1개 이상'      => (bool) preg_match( '/<a\b[^>]*href=/i', $post->post_content ),
	);
	echo '<p><strong>현재 본문: ' . esc_html( number_format_i18n( $word_count ) ) . '어절</strong></p><ul>';
	foreach ( $checks as $label => $passed ) {
		echo '<li>' . ( $passed ? '✅ ' : '⚠️ ' ) . esc_html( $label ) . '</li>';
	}
	echo '</ul><p>숫자만 늘리지 말고 직접 경험, 예외, 확인 날짜와 독자가 실행할 수 있는 절차를 보강하세요. 자동으로 표시되는 현장 업데이트는 본문 분량에 포함하지 않습니다.</p>';
}

add_action(
	'add_meta_boxes_post',
	static function (): void {
		add_meta_box( 'livingtmw-content-quality', '발행 전 콘텐츠 품질 검사', 'livingtmw_render_content_quality_audit', 'post', 'side', 'high' );
	}
);

/** Insert one fragment before a numbered section in the original article. */
function livingtmw_insert_before_numbered_section( string $content, int $number, string $fragment ): string {
	if ( '' === $fragment ) {
		return $content;
	}

	$pattern = '/(<h2\b[^>]*>\s*(?:<[^>]+>\s*)*' . $number . '\.)/iu';
	if ( ! preg_match( $pattern, $content ) ) {
		return $content;
	}

	return preg_replace_callback(
		$pattern,
		static function ( array $matches ) use ( $fragment ): string {
			return $fragment . $matches[1];
		},
		$content,
		1
	) ?? $content;
}

/** Place one final fragment immediately before the author's conclusion. */
function livingtmw_insert_before_conclusion( string $content, string $enhancements ): string {
	if ( '' === $enhancements ) {
		return $content;
	}

	$pattern = '/(<h2\b[^>]*>\s*(?:마무리(?:하며)?|마치며|정리|결론)\s*<\/h2>)/iu';
	if ( preg_match( $pattern, $content ) ) {
		return preg_replace_callback(
			$pattern,
			static function ( array $matches ) use ( $enhancements ): string {
				return $enhancements . $matches[1];
			},
			$content,
			1
		) ?? $content;
	}

	return $content . $enhancements;
}

/**
 * Weave unique supporting material through the author's numbered outline.
 * The image follows the introduction, evidence follows the first section,
 * deeper guidance sits around the middle, and the conclusion remains last.
 */
function livingtmw_weave_article_enhancements(
	string $content,
	string $update,
	string $deep_dive,
	string $field_note,
	string $clarification
): string {
	$image = '';
	if ( preg_match( '/<figure\b[^>]*livingtmw-field-image[^>]*>[\s\S]*?<\/figure>/i', $update, $image_match ) ) {
		$image  = $image_match[0];
		$update = str_replace( $image, '', $update );
	}

	preg_match_all( '/<h2\b[^>]*>\s*(?:<[^>]+>\s*)*(\d+)\./iu', $content, $numbered_matches );
	$numbers = array_values( array_unique( array_map( 'intval', $numbered_matches[1] ?? array() ) ) );
	if ( empty( $numbers ) ) {
		$all = $image . '<section class="livingtmw-field-update" aria-label="2026년 8월 현지 경험과 공식 자료 보강">' . $update . '</section>' . $deep_dive . $field_note . $clarification;
		return livingtmw_insert_before_conclusion( $content, $all );
	}

	$last_index = count( $numbers ) - 1;
	$second     = $numbers[ min( 1, $last_index ) ];
	$middle     = $numbers[ min( $last_index, max( 2, (int) floor( count( $numbers ) * 0.6 ) ) ) ];
	$last       = $numbers[ $last_index ];
	$deep       = $deep_dive;

	$content = livingtmw_insert_before_numbered_section( $content, $numbers[0], $image );
	$content = livingtmw_insert_before_numbered_section( $content, $second, '<section class="livingtmw-field-update" aria-label="2026년 8월 현지 경험과 공식 자료 보강">' . $update . '</section>' );
	$content = livingtmw_insert_before_numbered_section( $content, $middle, $deep );
	$content = livingtmw_insert_before_numbered_section( $content, $last, $field_note );

	return livingtmw_insert_before_conclusion( $content, $clarification );
}

add_filter(
	'the_content',
	static function ( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$post_id   = (int) get_the_ID();
		$update    = livingtmw_verified_experience_update( $post_id, livingtmw_field_update( $post_id ) );
		$deep_dive = livingtmw_article_deep_dive( $post_id );
		$field_note = livingtmw_article_field_note( $post_id );
		$clarification = livingtmw_article_clarification( $post_id );
		$upcoming_deep_dive = livingtmw_upcoming_article_deep_dive( $post_id );
		if ( '' === $update ) {
			return $content;
		}
		return livingtmw_weave_article_enhancements( $content, $update, $deep_dive . $upcoming_deep_dive, $field_note, $clarification );
	},
	15
);
