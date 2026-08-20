(function () {
	'use strict';

	var root = document.querySelector('[data-daily-fortune]');
	if (!root) return;
	var language = root.dataset.language === 'my' ? 'my' : 'ko';

	var form = root.querySelector('[data-fortune-form]');
	var result = root.querySelector('[data-fortune-result]');
	var birthFields = root.querySelector('[data-birth-fields]');
	var zodiacField = root.querySelector('[data-zodiac-field]');
	var lunarLeap = root.querySelector('[data-lunar-leap]');
	var zodiacOrder = ['rat', 'ox', 'tiger', 'rabbit', 'dragon', 'snake', 'horse', 'goat', 'monkey', 'rooster', 'dog', 'pig'];
	var zodiacNames = { rat: '쥐띠', ox: '소띠', tiger: '호랑이띠', rabbit: '토끼띠', dragon: '용띠', snake: '뱀띠', horse: '말띠', goat: '양띠', monkey: '원숭이띠', rooster: '닭띠', dog: '개띠', pig: '돼지띠' };
	var zodiacNamesMy = { rat: 'ကြွက်နှစ်', ox: 'နွားနှစ်', tiger: 'ကျားနှစ်', rabbit: 'ယုန်နှစ်', dragon: 'နဂါးနှစ်', snake: 'မြွေနှစ်', horse: 'မြင်းနှစ်', goat: 'ဆိတ်နှစ်', monkey: 'မျောက်နှစ်', rooster: 'ကြက်နှစ်', dog: 'ခွေးနှစ်', pig: 'ဝက်နှစ်' };
	var periods = {
		today: { label: '오늘의 운세', lead: '오늘은', range: '하루의 흐름' },
		tomorrow: { label: '내일의 운세', lead: '내일은', range: '다가올 하루' },
		week: { label: '이주의 운세', lead: '이번 주는', range: '한 주의 흐름' },
		month: { label: '이달의 운세', lead: '이번 달은', range: '한 달의 흐름' }
	};
	var categories = {
		total: {
			label: '총운', icon: '✨',
			openings: [
				'서두르기보다 우선순위를 분명히 할수록 원하는 방향이 또렷해집니다.',
				'익숙한 흐름 안에서 작은 변화를 시도하면 답답했던 일이 자연스럽게 풀릴 수 있습니다.',
				'주변의 속도에 맞추기보다 자신의 리듬을 지키는 것이 좋은 결과로 이어집니다.',
				'미뤄둔 일을 하나 정리하는 순간 새로운 기회를 받아들일 여유가 생깁니다.'
			],
			guides: [
				'해야 할 일을 모두 붙잡기보다 가장 중요한 한 가지를 먼저 끝내세요. 오전에는 판단이 필요한 일을, 오후에는 정리와 확인이 필요한 일을 배치하면 안정감이 커집니다.',
				'예상과 다른 제안이 들어오더라도 바로 거절하지 말고 조건을 차분히 살펴보세요. 처음에는 번거롭게 보여도 생활의 균형을 되찾는 계기가 될 수 있습니다.',
				'혼자 해결하려던 문제를 믿을 만한 사람과 나누면 생각보다 단순한 해법이 보입니다. 도움을 요청하는 것은 흐름을 잃는 일이 아니라 시간을 아끼는 선택입니다.'
			],
			cautions: [
				'다만 피곤한 상태에서 중요한 답을 서두르지 마세요. 잠깐 멈춰 확인하는 습관이 사소한 실수를 막아줍니다.',
				'좋은 흐름일수록 약속 시간과 준비물을 다시 확인하세요. 기본을 지키는 태도가 운의 폭을 넓혀줍니다.',
				'말 한마디를 너무 크게 해석하지 않는 편이 좋습니다. 사실과 추측을 구분하면 마음의 소모를 줄일 수 있습니다.'
			]
		},
		love: {
			label: '애정운', icon: '💗',
			openings: [
				'상대의 마음을 짐작하기보다 따뜻하고 솔직한 대화를 시작하기 좋은 흐름입니다.',
				'사소한 배려가 관계의 온도를 바꾸는 시기이니 익숙한 사람에게도 고마움을 표현해 보세요.',
				'새로운 인연보다 이미 곁에 있는 사람의 진심을 발견할 가능성이 큽니다.',
				'감정을 감추기보다 부담 없는 말로 전하면 서로의 거리를 자연스럽게 좁힐 수 있습니다.'
			],
			guides: [
				'연인이 있다면 해결책을 먼저 말하기보다 상대가 무엇을 느꼈는지 끝까지 들어주세요. 싱글이라면 꾸며낸 모습보다 편안한 태도가 좋은 인상을 남깁니다.',
				'연락 횟수만으로 관계를 판단하지 말고 대화의 내용과 서로의 상황을 함께 살펴보세요. 짧더라도 진심이 담긴 안부가 긴 설명보다 효과적입니다.',
				'기대하는 것이 있다면 시험하듯 행동하지 말고 구체적으로 이야기하세요. 서로 다른 기준을 확인하는 과정이 관계를 더 단단하게 만듭니다.'
			],
			cautions: [
				'과거의 서운함을 지금의 상황에 그대로 겹쳐 보지 않도록 주의하세요. 확인되지 않은 추측은 대화를 어렵게 만들 수 있습니다.',
				'감정이 올라왔을 때는 메시지를 바로 보내기보다 한 번 읽어본 뒤 전하세요. 부드러운 표현이 진심을 더 정확하게 전달합니다.',
				'상대의 선택과 경계를 존중해야 좋은 관계가 이어집니다. 운세보다 직접적인 대화와 동의를 우선하세요.'
			]
		},
		money: {
			label: '금전운', icon: '💰',
			openings: [
				'큰 수익을 좇기보다 새는 돈을 찾을 때 금전 흐름이 안정되는 시기입니다.',
				'필요와 욕심을 구분하면 만족도는 지키면서 지출 부담을 줄일 수 있습니다.',
				'작은 절약보다 반복되는 비용을 점검하는 일이 더 큰 효과를 만듭니다.',
				'금전과 관련된 조건을 꼼꼼히 비교할수록 유리한 선택에 가까워집니다.'
			],
			guides: [
				'구매를 결정하기 전에 실제 사용 횟수와 유지 비용까지 적어보세요. 가격이 싸다는 이유만으로 선택하기보다 오래 쓸 수 있는지를 따지는 편이 좋습니다.',
				'정기 결제, 환율, 수수료처럼 눈에 잘 띄지 않는 항목을 확인하세요. 오늘의 작은 점검이 다음 달 예산에 예상보다 큰 여유를 만들 수 있습니다.',
				'돈이 오가는 약속은 친한 사이에도 금액과 날짜를 분명히 기록하세요. 명확한 기준이 관계와 재정을 함께 지켜줍니다.'
			],
			cautions: [
				'충동적인 투자나 대출 결정은 피하고 손실 가능성을 먼저 확인하세요. 이 운세는 금융 판단의 근거가 될 수 없습니다.',
				'기분 전환을 위한 소비가 반복되지 않는지 살펴보세요. 결제를 하루 미루는 것만으로도 필요한 지출인지 판단하기 쉬워집니다.',
				'수익만 강조하는 제안은 계약 조건과 출처를 반드시 확인하세요. 이해되지 않는 상품에는 돈을 넣지 않는 것이 안전합니다.'
			]
		},
		work: {
			label: '직장운', icon: '💼',
			openings: [
				'새 일을 늘리기보다 진행 중인 업무의 완성도를 높일 때 평가가 따라옵니다.',
				'핵심을 짧게 정리하는 능력이 협업과 소통에서 강점으로 드러납니다.',
				'평소보다 한발 먼저 준비하면 예상 밖의 요청에도 여유 있게 대응할 수 있습니다.',
				'꾸준히 해온 일이 눈에 띄기 시작하니 결과와 과정을 차분히 정리해 두세요.'
			],
			guides: [
				'가장 어려운 업무를 짧은 단위로 나누고 첫 단계부터 시작하세요. 완벽한 결과를 한 번에 만들기보다 검토할 수 있는 초안을 빠르게 공유하는 편이 유리합니다.',
				'회의나 보고 전에는 결론, 근거, 필요한 결정 순서로 메모하세요. 상대가 무엇을 선택해야 하는지 분명하게 보여주면 불필요한 반복을 줄일 수 있습니다.',
				'동료의 도움을 받았다면 구체적으로 고마움을 표현하세요. 좋은 협업 관계가 다음 기회와 정보로 자연스럽게 이어질 수 있습니다.'
			],
			cautions: [
				'구두로 합의한 내용은 짧게라도 기록으로 남기세요. 일정과 담당자의 작은 오해가 업무를 지연시킬 수 있습니다.',
				'피드백을 개인적인 평가로 받아들이지 않는 편이 좋습니다. 필요한 부분만 골라 반영하면 오히려 실력을 보여줄 기회가 됩니다.',
				'바쁜 분위기에 휩쓸려 우선순위를 놓치지 마세요. 급한 일과 중요한 일을 구분해야 야근과 재작업을 줄일 수 있습니다.'
			]
		},
		study: {
			label: '학업·성적운', icon: '📚',
			openings: [
				'넓게 훑기보다 한 주제를 깊게 이해할 때 학습 효율이 높아집니다.',
				'틀린 문제와 헷갈린 개념을 다시 보는 시간이 성적 향상의 열쇠가 됩니다.',
				'짧게라도 매일 반복하는 공부가 몰아서 하는 공부보다 좋은 흐름을 만듭니다.',
				'배운 내용을 자신의 말로 설명하면 부족한 부분을 빠르게 찾을 수 있습니다.'
			],
			guides: [
				'공부를 시작하기 전에 이번 시간에 끝낼 범위를 작게 정하세요. 25분 집중 후 5분 쉬는 방식처럼 시작과 끝이 분명한 계획이 집중력을 지켜줍니다.',
				'새로운 자료를 계속 찾기보다 이미 가진 교재와 오답을 활용하세요. 아는 것과 실제로 풀 수 있는 것의 차이를 확인하는 과정이 중요합니다.',
				'시험을 준비한다면 제한 시간을 두고 문제를 풀어보세요. 점수보다 막힌 지점과 실수 유형을 기록하면 다음 학습 방향이 선명해집니다.'
			],
			cautions: [
				'다른 사람의 진도와 자신을 비교하면 집중력이 흐트러질 수 있습니다. 어제보다 이해한 내용이 하나 늘었는지를 기준으로 삼으세요.',
				'밤을 새워 공부하기보다 수면 시간을 확보하세요. 기억을 정리할 시간이 부족하면 익숙한 문제에서도 실수가 늘 수 있습니다.',
				'예상 점수에 지나치게 의미를 두지 마세요. 실제 성적은 준비, 시험 환경과 평가 기준에 따라 달라집니다.'
			]
		},
		health: {
			label: '건강운', icon: '🌿',
			openings: [
				'몸의 작은 신호를 무시하지 않고 생활 리듬을 점검하기 좋은 시기입니다.',
				'무리한 운동보다 꾸준한 수면과 가벼운 움직임이 컨디션 회복에 도움이 됩니다.',
				'피로를 의지로 버티기보다 휴식 시간을 미리 확보해야 활력을 지킬 수 있습니다.',
				'식사와 수분 섭취 시간을 일정하게 맞추면 하루의 기복을 줄이는 데 도움이 됩니다.'
			],
			guides: [
				'오래 앉아 있다면 한 시간마다 자세를 바꾸고 잠깐 걸어보세요. 거창한 계획보다 목과 어깨를 풀고 물을 마시는 작은 습관부터 시작하는 편이 좋습니다.',
				'몸이 무겁게 느껴질 때는 운동 강도를 높이기보다 수면과 식사 상태를 먼저 돌아보세요. 회복할 시간을 주는 것도 건강 관리의 중요한 부분입니다.',
				'양곤의 더위와 습도, 대기질을 확인하고 야외 활동 시간을 조절하세요. 갈증을 느끼기 전부터 조금씩 물을 마시는 습관이 도움이 됩니다.'
			],
			cautions: [
				'통증이나 이상 증상이 계속되면 운세로 판단하지 말고 의료기관이나 자격을 갖춘 전문가에게 상담하세요.',
				'검증되지 않은 건강식품이나 치료법을 운세 결과만 보고 시도하지 마세요. 복용 중인 약이 있다면 전문가와 먼저 상의해야 합니다.',
				'피곤할수록 운전과 위험한 작업에 주의하세요. 충분한 휴식과 실제 몸 상태를 모든 일정보다 우선하는 편이 안전합니다.'
			]
		}
	};
	var periodsMy = {
		today: { label: 'ယနေ့ကံကြမ္မာ', lead: 'ယနေ့တွင်', range: 'ယနေ့၏ အခြေအနေ' },
		tomorrow: { label: 'မနက်ဖြန်ကံကြမ္မာ', lead: 'မနက်ဖြန်တွင်', range: 'မနက်ဖြန်၏ အခြေအနေ' },
		week: { label: 'ယခုအပတ်ကံကြမ္မာ', lead: 'ယခုအပတ်တွင်', range: 'တစ်ပတ်တာ အခြေအနေ' },
		month: { label: 'ယခုလကံကြမ္မာ', lead: 'ယခုလတွင်', range: 'တစ်လတာ အခြေအနေ' }
	};
	var categoryNamesMy = { total: 'အထွေထွေ', love: 'အချစ်ရေး', money: 'ငွေကြေး', work: 'အလုပ်အကိုင်', study: 'ပညာရေး·အမှတ်', health: 'ကျန်းမာရေး' };
	var myReadings = {
		total: {
			opening: 'အရာအားလုံးကို တစ်ပြိုင်နက် မလုပ်ဘဲ အရေးကြီးဆုံးအလုပ်ကို ဦးစားပေးပါက အခြေအနေ ပိုမိုရှင်းလင်းလာမည်။',
			guide: 'နံနက်ပိုင်းတွင် ဆုံးဖြတ်ချက်ချရမည့်အလုပ်ကို ဦးစွာလုပ်ပြီး နေ့လယ်ပိုင်းတွင် စစ်ဆေးခြင်းနှင့် ပြီးစီးအောင်လုပ်ခြင်းကို အာရုံစိုက်ပါ။ ယုံကြည်ရသူတစ်ဦးနှင့် အကြံမျှဝေခြင်းက မမြင်ရသေးသော နည်းလမ်းတစ်ခုကို တွေ့စေနိုင်သည်။',
			caution: 'ပင်ပန်းနေချိန်တွင် အရေးကြီးသောအဖြေကို အလျင်စလို မပေးပါနှင့်။ အချက်အလက်နှင့် ကိုယ်ပိုင်ခန့်မှန်းချက်ကို ခွဲခြားပြီး တစ်ကြိမ်ထပ်စစ်ဆေးပါ။'
		},
		love: {
			opening: 'တစ်ဖက်သူ၏စိတ်ကို ခန့်မှန်းနေခြင်းထက် နူးညံ့ပြီး ရိုးသားသောစကားပြောဆိုမှုက ဆက်ဆံရေးကို ပိုနီးကပ်စေမည်။',
			guide: 'ချစ်သူရှိပါက ဖြေရှင်းချက်ကို ချက်ချင်းမပြောဘဲ သူ၏ခံစားချက်ကို အဆုံးအထိ နားထောင်ပါ။ တစ်ကိုယ်တည်းဖြစ်ပါက အတုယူထားသောပုံစံထက် သဘာဝကျပြီး သက်သောင့်သက်သာရှိသော အပြုအမူက ကောင်းသောအထင်အမြင်ကို ပေးနိုင်သည်။',
			caution: 'အတိတ်က စိတ်မကောင်းမှုကို လက်ရှိအခြေအနေပေါ် မတင်ပါနှင့်။ ကံကြမ္မာစာထက် နှစ်ဦးကြား တိုက်ရိုက်ပြောဆိုမှုနှင့် သဘောတူညီမှုကို ဦးစားပေးပါ။'
		},
		money: {
			opening: 'အမြတ်ကြီးကို လိုက်ရှာခြင်းထက် မလိုအပ်ဘဲ ထွက်နေသောအသုံးစရိတ်ကို ရှာဖွေပါက ငွေကြေးအခြေအနေ ပိုတည်ငြိမ်လာမည်။',
			guide: 'ဝယ်ယူရန် မဆုံးဖြတ်မီ အမှန်တကယ်အသုံးပြုမည့်အကြိမ်နှင့် ထိန်းသိမ်းစရိတ်ကိုပါ တွက်ချက်ပါ။ ပုံမှန်ပေးချေမှု၊ ငွေလဲနှုန်းနှင့် ဝန်ဆောင်ခကဲ့သို့ မမြင်သာသောအသုံးစရိတ်များကို စစ်ဆေးခြင်းက နောက်လဘတ်ဂျက်ကို ကူညီနိုင်သည်။',
			caution: 'ရင်းနှီးမြှုပ်နှံမှု သို့မဟုတ် ချေးငွေကို အလျင်စလို မဆုံးဖြတ်ပါနှင့်။ ဤကံကြမ္မာစာသည် ငွေကြေးဆုံးဖြတ်ချက်အတွက် အခြေခံမဟုတ်ပါ။'
		},
		work: {
			opening: 'အလုပ်အသစ်များ ထပ်ယူခြင်းထက် လက်ရှိလုပ်နေသောအလုပ်ကို အရည်အသွေးကောင်းကောင်း ပြီးစီးအောင်လုပ်ခြင်းက အသိအမှတ်ပြုမှု ရစေမည်။',
			guide: 'ခက်ခဲသောအလုပ်ကို အဆင့်ငယ်များခွဲပြီး ပထမအဆင့်ကို စတင်ပါ။ အစည်းအဝေးမတိုင်မီ အဖြေ၊ အကြောင်းပြချက်နှင့် လိုအပ်သောဆုံးဖြတ်ချက်ကို အစဉ်လိုက် မှတ်သားထားပါက ပူးပေါင်းလုပ်ဆောင်မှု ပိုလွယ်ကူမည်။',
			caution: 'နှုတ်ဖြင့် သဘောတူထားသော အချိန်နှင့် တာဝန်ကို စာဖြင့်တိုတို ပြန်လည်အတည်ပြုပါ။ အရေးပေါ်အလုပ်နှင့် အရေးကြီးသောအလုပ်ကို ခွဲခြားရန် လိုအပ်သည်။'
		},
		study: {
			opening: 'အကြောင်းအရာများစွာကို အလျင်အမြန်ဖတ်ခြင်းထက် ခေါင်းစဉ်တစ်ခုကို နက်နက်ရှိုင်းရှိုင်း နားလည်ခြင်းက ပိုထိရောက်မည်။',
			guide: 'စာမလေ့လာမီ ယခုအချိန်အတွင်း ပြီးစီးမည့်အပိုင်းကို သေးသေးလေး သတ်မှတ်ပါ။ မေးခွန်းမှားများနှင့် မရှင်းလင်းသောအယူအဆများကို ပြန်ကြည့်ပြီး ကိုယ်ပိုင်စကားဖြင့် ရှင်းပြကြည့်ခြင်းက အားနည်းချက်ကို လျင်မြန်စွာ တွေ့စေမည်။',
			caution: 'အခြားသူ၏ လေ့လာနှုန်းနှင့် မိမိကိုယ်ကို မနှိုင်းယှဉ်ပါနှင့်။ အိပ်ရေးပျက်ခြင်းက သိပြီးသားမေးခွန်းတွင်ပင် အမှားများစေနိုင်သဖြင့် အနားယူချိန်ကို ထိန်းပါ။'
		},
		health: {
			opening: 'ခန္ဓာကိုယ်၏ သေးငယ်သောအချက်ပြမှုကို မလျစ်လျူရှုဘဲ အိပ်စက်ခြင်း၊ အစားအသောက်နှင့် နေ့စဉ်လှုပ်ရှားမှုကို ပြန်စစ်ရန် ကောင်းသောအချိန်ဖြစ်သည်။',
			guide: 'အကြာကြီးထိုင်ရပါက တစ်နာရီလျှင် တစ်ကြိမ် ကိုယ်နေဟန်ပြောင်းပြီး ခဏလမ်းလျှောက်ပါ။ ရန်ကုန်၏ အပူချိန်၊ စိုထိုင်းဆနှင့် လေထုအရည်အသွေးကို စစ်ဆေးကာ ရေကို အနည်းငယ်စီ မကြာခဏသောက်ပါ။',
			caution: 'နာကျင်မှု သို့မဟုတ် ထူးခြားသောလက္ခဏာ ဆက်ရှိနေပါက ကံကြမ္မာစာဖြင့် မဆုံးဖြတ်ဘဲ ဆေးရုံ သို့မဟုတ် ကျွမ်းကျင်သူနှင့် တိုင်ပင်ပါ။'
		}
	};
	var myUi = {
		'오늘부터 이달까지 자세히 보는 운세': 'ယနေ့မှ ယခုလအထိ အသေးစိတ်ကံကြမ္မာ',
		'생년월일로 보는 나의 생활 운세': 'မွေးသက္ကရာဇ်ဖြင့် ကြည့်သော ကျွန်ုပ်၏ကံကြမ္မာ',
		'양력·음력 생년월일 또는 나의 띠를 선택하면 총운, 애정운, 금전운, 직장운, 학업·성적운, 건강운을 자세히 보여드립니다. 오늘·내일·이번 주·이번 달의 흐름을 자유롭게 바꿔 볼 수 있으며 입력 정보는 서버로 전송하거나 저장하지 않습니다.': 'မွေးသက္ကရာဇ် သို့မဟုတ် မိမိ၏ တိရစ္ဆာန်နှစ်ကို ရွေးချယ်ပြီး အထွေထွေ၊ အချစ်ရေး၊ ငွေကြေး၊ အလုပ်အကိုင်၊ ပညာရေးနှင့် ကျန်းမာရေးကံကြမ္မာကို ကြည့်နိုင်သည်။ ထည့်သွင်းသောအချက်အလက်များကို ဆာဗာသို့ မပို့ဘဲ မသိမ်းဆည်းပါ။',
		'운세 기준': 'ကံကြမ္မာတွက်ချက်မည့် အချက်အလက်', '생년월일': 'မွေးသက္ကရာဇ်', '띠 직접 선택': 'တိရစ္ဆာန်နှစ်ကို ရွေးမည်',
		'달력 구분': 'ပြက္ခဒိန်အမျိုးအစား', '양력': 'နေပြက္ခဒိန်', '음력': 'လပြက္ခဒိန်', '태어난 해': 'မွေးနှစ်', '월': 'လ', '일': 'ရက်',
		'음력 윤달 생일입니다': 'လပြက္ခဒိန်၏ ဝါထပ်လတွင် မွေးဖွားသည်',
		'양력 생일은 음력 새해 경계를 반영해 띠를 계산합니다. 음력은 음력 달력에 적힌 연·월·일을 입력하세요.': 'နေပြက္ခဒိန်ရက်မှ တိရစ္ဆာန်နှစ်ကို လပြက္ခဒိန်နှစ်သစ်အတိုင်း တွက်ချက်သည်။ လပြက္ခဒိန်ကို ရွေးပါက လပြက္ခဒိန်ရှိ နှစ်၊ လ၊ ရက်ကို ထည့်ပါ။',
		'나의 띠': 'ကျွန်ုပ်၏ တိရစ္ဆာန်နှစ်', '띠를 선택하세요': 'တိရစ္ဆာန်နှစ်ကို ရွေးပါ', '내 운세 자세히 보기': 'ကျွန်ုပ်၏ကံကြမ္မာကို ကြည့်မည်',
		'🔒 생년월일과 선택값은 이 브라우저에서만 계산되며 저장·전송되지 않습니다.': '🔒 မွေးသက္ကရာဇ်နှင့် ရွေးချယ်မှုများကို ဤဘရောက်ဇာတွင်သာ တွက်ချက်ပြီး သိမ်းဆည်းခြင်း၊ ပေးပို့ခြင်း မပြုပါ။',
		'생년월일 또는 띠를 선택해 자세한 운세를 확인하세요': 'မွေးသက္ကရာဇ် သို့မဟုတ် တိရစ္ဆာန်နှစ်ကို ရွေးပြီး အသေးစိတ်ကံကြမ္မာကို ကြည့်ပါ',
		'오늘·내일·이번 주·이번 달의 여섯 가지 운세를 볼 수 있습니다.': 'ယနေ့၊ မနက်ဖြန်၊ ယခုအပတ်နှင့် ယခုလအတွက် ကံကြမ္မာခြောက်မျိုးကို ကြည့်နိုင်သည်။',
		'운세는 오락과 자기 점검을 위한 참고 콘텐츠입니다.': 'ကံကြမ္မာစာသည် ဖျော်ဖြေရေးနှင့် ကိုယ့်ကိုယ်ကို ပြန်လည်သုံးသပ်ရန်အတွက်သာ ဖြစ်သည်။',
		'미래를 예측하거나 결과를 보장하지 않습니다. 투자·대출·복권 구매, 진료·복약, 계약과 같은 중요한 결정은 운세가 아니라 검증된 정보와 전문가의 조언을 기준으로 판단하세요.': 'အနာဂတ်ကို တိကျစွာခန့်မှန်းခြင်း သို့မဟုတ် ရလဒ်ကို အာမခံခြင်း မဟုတ်ပါ။ ရင်းနှီးမြှုပ်နှံမှု၊ ချေးငွေ၊ ဆေးကုသမှုနှင့် စာချုပ်ကဲ့သို့ အရေးကြီးသောဆုံးဖြတ်ချက်များတွင် အတည်ပြုထားသောအချက်အလက်နှင့် ကျွမ်းကျင်သူ၏အကြံကို အသုံးပြုပါ။',
		'결과는 어떻게 만들어지나요?': 'ရလဒ်ကို မည်သို့ဖန်တီးသနည်း။', '오늘의 운세를 건강하게 활용하는 법': 'ကံကြမ္မာစာကို သင့်တင့်စွာ အသုံးပြုနည်း', '자주 묻는 질문': 'မေးလေ့ရှိသောမေးခွန်းများ'
	};
	var currentProfile = null;
	var activePeriod = 'today';
	var activeCategory = 'total';

	function hash(value) {
		var h = 2166136261;
		for (var i = 0; i < value.length; i += 1) {
			h ^= value.charCodeAt(i);
			h = Math.imul(h, 16777619);
		}
		return h >>> 0;
	}

	function yangonDate() {
		return new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Yangon', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date());
	}

	function periodKey(period) {
		var today = yangonDate();
		var date = new Date(today + 'T12:00:00Z');
		if (period === 'tomorrow') date.setUTCDate(date.getUTCDate() + 1);
		if (period === 'week') {
			var weekday = date.getUTCDay() || 7;
			date.setUTCDate(date.getUTCDate() - weekday + 1);
		}
		if (period === 'month') return today.slice(0, 7);
		return date.toISOString().slice(0, 10);
	}

	function periodSet() {
		return language === 'my' ? periodsMy : periods;
	}

	function translateStaticUi() {
		if (language !== 'my') return;
		var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
		var nodes = [];
		while (walker.nextNode()) nodes.push(walker.currentNode);
		nodes.forEach(function (node) {
			var original = node.nodeValue;
			var trimmed = original.trim();
			if (!myUi[trimmed]) return;
			node.nodeValue = original.replace(trimmed, myUi[trimmed]);
		});
		Object.keys(zodiacNamesMy).forEach(function (key) {
			var option = form.querySelector('option[value="' + key + '"]');
			if (option) option.textContent = zodiacNamesMy[key];
		});
		form.elements.birth_year.placeholder = 'ဥပမာ 1990';
		form.elements.birth_month.placeholder = '1~12';
		form.elements.birth_day.placeholder = '1~31';
		document.documentElement.lang = 'my';
		document.title = document.title.replace('오늘의 운세', 'ယနေ့ကံကြမ္မာ');
		var pageTitle = document.querySelector('h1.entry-title');
		if (pageTitle) pageTitle.textContent = 'ယနေ့ကံကြမ္မာ';
	}

	function setMethod(method) {
		var useBirth = method === 'birth';
		birthFields.hidden = !useBirth;
		zodiacField.hidden = useBirth;
		['birth_year', 'birth_month', 'birth_day'].forEach(function (name) { form.elements[name].required = useBirth; });
		form.elements.zodiac.required = !useBirth;
	}

	function setCalendar(calendar) {
		/* Lunar leap-month input was intentionally removed; calendar selection is sufficient. */
	}

	function validDate(year, month, day, calendar) {
		if (year < 1930 || year > new Date().getFullYear() || month < 1 || month > 12 || day < 1) return false;
		if (calendar === 'lunar') return day <= 30;
		var date = new Date(Date.UTC(year, month - 1, day));
		return date.getUTCFullYear() === year && date.getUTCMonth() === month - 1 && date.getUTCDate() === day;
	}

	function lunarYearFromSolar(year, month, day) {
		try {
			var parts = new Intl.DateTimeFormat('en-u-ca-chinese', { year: 'numeric', timeZone: 'UTC' }).formatToParts(new Date(Date.UTC(year, month - 1, day)));
			var related = parts.filter(function (part) { return part.type === 'relatedYear'; })[0];
			if (related && /^\d{4}$/.test(related.value)) return parseInt(related.value, 10);
		} catch (error) {
			return null;
		}
		return null;
	}

	function zodiacForYear(year) {
		return zodiacOrder[((year - 4) % 12 + 12) % 12];
	}

	function categoryResult(categoryKey) {
		var category = categories[categoryKey];
		var seed = hash(periodKey(activePeriod) + ':' + activePeriod + ':' + categoryKey + ':' + currentProfile.zodiac + ':' + currentProfile.birthKey);
		var period = periodSet()[activePeriod];
		if (language === 'my') {
			return {
				score: 58 + (seed % 37),
				opening: period.lead + ' ' + myReadings[categoryKey].opening,
				guide: myReadings[categoryKey].guide,
				caution: myReadings[categoryKey].caution
			};
		}
		return {
			score: 58 + (seed % 37),
			opening: period.lead + ' ' + category.openings[seed % category.openings.length],
			guide: category.guides[(seed >>> 4) % category.guides.length],
			caution: category.cautions[(seed >>> 8) % category.cautions.length]
		};
	}

	function renderResult() {
		var shownPeriods = periodSet();
		var periodButtons = Object.keys(shownPeriods).map(function (key) {
			return '<button type="button" role="tab" aria-selected="' + (key === activePeriod) + '" class="' + (key === activePeriod ? 'is-active' : '') + '" data-fortune-period="' + key + '">' + shownPeriods[key].label + '</button>';
		}).join('');
		var categoryButtons = Object.keys(categories).map(function (key) {
			var item = categories[key];
			var itemResult = categoryResult(key);
			return '<button type="button" class="livingtmw-fortune__category ' + (key === activeCategory ? 'is-active' : '') + '" aria-expanded="' + (key === activeCategory) + '" data-fortune-category="' + key + '"><span><i aria-hidden="true">' + item.icon + '</i><strong>' + (language === 'my' ? categoryNamesMy[key] : item.label) + '</strong></span><b>' + itemResult.score + '<small>/100</small></b></button>';
		}).join('');
		var detail = categoryResult(activeCategory);
		var category = categories[activeCategory];
		result.innerHTML = '<div class="livingtmw-fortune__profile"><span>' + currentProfile.label + ' · ' + (language === 'my' ? zodiacNamesMy[currentProfile.zodiac] : zodiacNames[currentProfile.zodiac]) + '</span><strong>' + shownPeriods[activePeriod].label + '</strong></div>' +
			'<div class="livingtmw-fortune__periods" role="tablist" aria-label="' + (language === 'my' ? 'ကံကြမ္မာကာလ ရွေးချယ်ရန်' : '운세 기간 선택') + '">' + periodButtons + '</div>' +
			'<div class="livingtmw-fortune__category-grid" aria-label="' + (language === 'my' ? 'ကဏ္ဍအလိုက် ကံကြမ္မာ' : '분야별 운세') + '">' + categoryButtons + '</div>' +
			'<article class="livingtmw-fortune__reading" aria-live="polite"><header><div><span>' + shownPeriods[activePeriod].range + '</span><h3>' + category.icon + ' ' + (language === 'my' ? categoryNamesMy[activeCategory] : category.label) + '</h3></div><b>' + detail.score + '<small>/100</small></b></header><p class="livingtmw-fortune__reading-lead">' + detail.opening + '</p><p>' + detail.guide + '</p><p class="livingtmw-fortune__reading-caution"><strong>' + (language === 'my' ? 'သတိပြုရန်' : '살펴볼 점') + '</strong> ' + detail.caution + '</p></article>';
	}

	form.addEventListener('change', function (event) {
		if (event.target.name === 'method') setMethod(event.target.value);
		if (event.target.name === 'calendar') setCalendar(event.target.value);
	});
	setMethod(form.elements.method.value);
	setCalendar(form.elements.calendar.value);
	translateStaticUi();

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		var method = form.elements.method.value;
		var calendar = form.elements.calendar.value;
		var zodiac = form.elements.zodiac.value;
		var birthKey = 'direct';
		var label = language === 'my' ? 'တိရစ္ဆာန်နှစ်ကို ရွေးထားသည်' : '띠 직접 선택';

		if (method === 'birth') {
			var year = parseInt(form.elements.birth_year.value, 10);
			var month = parseInt(form.elements.birth_month.value, 10);
			var day = parseInt(form.elements.birth_day.value, 10);
			if (!validDate(year, month, day, calendar)) {
				form.elements.birth_year.focus();
				result.innerHTML = '<p class="livingtmw-fortune__error">' + (language === 'my' ? 'မွေးသက္ကရာဇ်နှင့် ပြက္ခဒိန်အမျိုးအစားကို ပြန်စစ်ပါ။' : '생년월일을 달력 구분에 맞게 확인해 주세요.') + '</p>';
				return;
			}
			var zodiacYear = calendar === 'solar' ? lunarYearFromSolar(year, month, day) : year;
			if (!zodiacYear) {
				result.innerHTML = '<p class="livingtmw-fortune__error">' + (language === 'my' ? 'ဤဘရောက်ဇာတွင် နေပြက္ခဒိန်မှ တိရစ္ဆာန်နှစ်ကို မတွက်နိုင်ပါ။ တိရစ္ဆာန်နှစ်ကို ကိုယ်တိုင်ရွေးပါ။' : '이 브라우저에서는 양력의 띠 계산을 지원하지 않습니다. ‘띠 직접 선택’을 이용해 주세요.') + '</p>';
				return;
			}
			zodiac = zodiacForYear(zodiacYear);
			birthKey = [calendar, year, month, day].join('-');
			label = language === 'my' ? year + ' ခုနှစ် ' + month + ' လ ' + day + ' ရက်' : (calendar === 'solar' ? '양력 ' : '음력 ') + year + '년 ' + month + '월 ' + day + '일';
		} else if (!zodiac) {
			form.elements.zodiac.focus();
			result.innerHTML = '<p class="livingtmw-fortune__error">' + (language === 'my' ? 'မိမိ၏ တိရစ္ဆာန်နှစ်ကို ဦးစွာရွေးပါ။' : '먼저 나의 띠를 선택해 주세요.') + '</p>';
			return;
		}

		currentProfile = { zodiac: zodiac, birthKey: birthKey, label: label };
		activePeriod = 'today';
		activeCategory = 'total';
		renderResult();
		result.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'nearest' });
	});

	result.addEventListener('click', function (event) {
		var periodButton = event.target.closest('[data-fortune-period]');
		var categoryButton = event.target.closest('[data-fortune-category]');
		if (periodButton) {
			activePeriod = periodButton.dataset.fortunePeriod;
			activeCategory = 'total';
			renderResult();
		}
		if (categoryButton) {
			activeCategory = categoryButton.dataset.fortuneCategory;
			renderResult();
			var reading = result.querySelector('.livingtmw-fortune__reading');
			if (reading) reading.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}
	});
}());
