(function () {
	'use strict';

	var root = document.querySelector('[data-daily-fortune]');
	if (!root) return;

	var form = root.querySelector('[data-fortune-form]');
	var result = root.querySelector('[data-fortune-result]');
	var zodiacNames = { rat: '쥐띠', ox: '소띠', tiger: '호랑이띠', rabbit: '토끼띠', dragon: '용띠', snake: '뱀띠', horse: '말띠', goat: '양띠', monkey: '원숭이띠', rooster: '닭띠', dog: '개띠', pig: '돼지띠' };
	var focusNames = { balance: '종합', work: '일·학업', money: '금전', relationship: '관계' };
	var messages = {
		balance: [
			'속도를 높이기보다 우선순위를 하나 정하면 흐름이 또렷해집니다.',
			'미뤄둔 작은 일을 끝내면 생각보다 큰 여유가 생기는 날입니다.',
			'익숙한 방법을 조금 바꿔보세요. 사소한 변화가 기분을 환기합니다.',
			'주변의 말보다 내 상태를 먼저 살피면 균형을 찾기 쉽습니다.',
			'계획에 빈칸을 남겨두면 예상 밖의 기회를 편하게 받아들일 수 있습니다.'
		],
		work: [
			'어려운 일부터 20분만 집중해 보세요. 시작이 오늘의 가장 큰 진전입니다.',
			'설명하기 전에 핵심을 한 문장으로 정리하면 협업이 수월해집니다.',
			'완벽한 결과보다 검토 가능한 초안을 만드는 데 힘을 써보세요.',
			'새 일을 늘리기보다 진행 중인 한 가지를 마무리하기 좋은 날입니다.',
			'모르는 부분을 일찍 질문하면 불필요한 반복을 줄일 수 있습니다.'
		],
		money: [
			'큰 결정보다 오늘의 지출 한 가지를 점검하는 것이 도움이 됩니다.',
			'가격만 보지 말고 실제 사용 횟수와 유지 비용을 함께 비교해 보세요.',
			'충동적인 결제는 하루 미루고 필요한 이유를 적어보는 편이 좋습니다.',
			'작은 절약보다 반복되는 고정비를 살펴보면 더 분명한 답이 보입니다.',
			'수익을 서두르기보다 손실 가능성과 조건을 차분히 확인하세요.'
		],
		relationship: [
			'답을 정해두기보다 상대의 말을 끝까지 듣는 것이 관계에 도움이 됩니다.',
			'고마웠던 일을 구체적으로 표현하면 편안한 대화가 시작됩니다.',
			'오해가 있다면 메시지보다 차분한 대화로 확인해 보세요.',
			'혼자만의 시간이 필요한지 먼저 살피면 감정을 정리하기 쉽습니다.',
			'작은 약속을 지키는 행동이 긴 설명보다 신뢰를 높여줍니다.'
		]
	};
	var actions = [
		'물 한 잔을 마시고 오늘 할 일 세 가지를 적어보세요.',
		'10분 산책이나 가벼운 정리로 흐름을 바꿔보세요.',
		'오후에는 알림을 잠시 끄고 한 가지 일에 집중해 보세요.',
		'고마운 사람 한 명에게 짧은 안부를 전해보세요.',
		'오늘 쓴 금액을 잠들기 전에 한 번만 확인해 보세요.',
		'내일로 미룰 일과 오늘 끝낼 일을 분리해 적어보세요.'
	];
	var colors = ['푸른색', '연두색', '주황색', '보라색', '하늘색', '아이보리', '남색', '분홍색'];

	function hash(value) {
		var h = 2166136261;
		for (var i = 0; i < value.length; i += 1) {
			h ^= value.charCodeAt(i);
			h = Math.imul(h, 16777619);
		}
		return h >>> 0;
	}

	function dateKey() {
		return new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Yangon', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date());
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		var zodiac = form.elements.zodiac.value;
		var focus = form.elements.focus.value;
		if (!zodiac) {
			form.elements.zodiac.focus();
			result.innerHTML = '<p class="livingtmw-fortune__error">먼저 나의 띠를 선택해 주세요.</p>';
			return;
		}

		var seed = hash(dateKey() + ':' + zodiac + ':' + focus);
		var score = 62 + (seed % 30);
		var message = messages[focus][seed % messages[focus].length];
		var action = actions[(seed >>> 3) % actions.length];
		var color = colors[(seed >>> 5) % colors.length];
		var number = 1 + ((seed >>> 7) % 9);

		result.innerHTML = '<div class="livingtmw-fortune__result-head"><div><span>' + zodiacNames[zodiac] + ' · ' + focusNames[focus] + '</span><strong>오늘의 흐름 ' + score + '</strong></div><b aria-label="오늘의 흐름 점수 ' + score + '점">' + score + '<small>/100</small></b></div>' +
			'<p class="livingtmw-fortune__message">' + message + '</p>' +
			'<dl class="livingtmw-fortune__details"><div><dt>오늘의 작은 행동</dt><dd>' + action + '</dd></div><div><dt>기분 전환 색상</dt><dd>' + color + '</dd></div><div><dt>오늘의 숫자</dt><dd>' + number + ' <small>오락용이며 복권·투자와 무관합니다.</small></dd></div></dl>';
		result.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'nearest' });
	});
}());
