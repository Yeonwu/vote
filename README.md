# vote
관리자가 해야 할 일


투표 기록 초기화
--------------------------------------
이전에 투표했던 기록이 남아있으면 오류가 발생한다.
관리자 계정으로 로그인하면 들어갈 수 있는 "개표 결과 보기" 페이지로 들어가 초기화 버튼을 눌러 기록을 모두 지워야한다.



학생들 계정 초기화
--------------------------------------
졸업생과 신입생, 학년이 바뀐 사람들의 정보를 업데이트해줘야한다.
phpmyadmin 가져오기 기능을 이용해 엑셀에서 정보를 가져오면 편하다.
인터넷에서 찾아보면 방법이 나온다.
어렵다면 조은길 선생님을 찾아가 조언을 구하면 도와주실 것이다. ^^



후보자들 명단 입력
--------------------------------------
새로운 후보자들 명단을 입력해주어야 한다.
config.php의 20, 35줄에 있는 $grade_imwon 과 $dormitory_imwon을 수정하면 된다.
각 학년과 성별을 맞춰 이름을 입력하면 된다.
후보자가 1명인 경우는 이름 뒤에 찬성, 반대를 붙성, 반대를 붙여서 구분해주어야 한다.

ex) 10학년 남자 후보자들을 수정하려면 
$grade_imwon = array(
	'10학년' => array(
		'male' => array('김효준', '정환호'), <---------------------------------------------여기를 수정하면 된다.
		'female' => array('문하진', '박시은', '황은우', '황예희'),
	),
	'11학년' => array(
		'male' => array('최한빈', '권세영'),
		'female' => array('김지우', '심주영', '백서은')
	),
	'12학년' => array(
		'male' => array('이세찬 찬성', '이세찬 반대'), <------------------------------------------ 찬성 / 반대의 예시
		'female' => array('김진솔 찬성', '김진솔 반대')
	)
);

$grade_imwon = array(
	'10학년' => array(
		'male' => array('후보자1', '후보자2', '후보자3'), <---------------------------------------------이런식으로.
		'female' => array('문하진', '박시은', '황은우', '황예희'),
	),
	'11학년' => array(
		'male' => array('최한빈', '권세영'),
		'female' => array('김지우', '심주영', '백서은')
	),
	'12학년' => array(
		'male' => array('이세찬 찬성', '이세찬 반대'),
		'female' => array('김진솔 찬성', '김진솔 반대')
	)
);

$dormitory_imwon도 똑같이 수정하면 된다.


파일 수정
--------------------------------------
config.php 16번줄
define('ADMIN_EMAIL', '201028@bmrschool.org');
에서 '201028@bmrschool.org'를 관리자 이메일로 바꿔준다.
ex) 211037@bmrschool.org이 관리자 이메일이라면 define('ADMIN_EMAIL', '211037@bmrschool.org'); 처럼 적어준다.

config.php 17번줄
define('SALT', 'saltforme');
에서 'saltforme'를 주기적으로 다른 내용으로 바꿔준다. ex) 'hahahaImsmart'으로 바꾼다면 define('SALT', 'hahahaImsmart'); 처럼 적어준다.




문의는 yeonwu319@gmail.com으로 이메일 ㄱㄱ
언제든지 답변 가능하니 부담없이 연락주세요.
