<style> body{width: 100vw; height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0;}</style>

<?php
require "./config.php";

$id = escape($_POST['id']);
$verifyToken = escape($_POST['verifyToken']);
$gradeMaleVote = $_POST['gradeMaleVote'];
$gradeFemaleVote = $_POST['gradeFemaleVote'];
$dormitoryVote = $_POST['dormitoryVote'];

if(hash('sha256', $id . SALT) != $verifyToken) {
	echo goIndex("죄송합니다. 이메일 정보가 잘못되었습니다. 새로고침해 주세요.");
	exit;
}

$sql = "

SELECT 
	*
FROM
	`user_info`
WHERE
	`email` = '{$id}';

";

$result = execute_query($sql);
if($result === FALSE) {
	echo goIndex("죄송합니다. 사용자 정보를 찾지 못했습니다. 주변 선생님께 문의해주세요. id: {$id}");
	exit;
}
$result = $result -> fetch_assoc();
$voted = $result['voted'];
$grade = $result['grade'];
$gender = $result['gender'];

$grade_imwon = $grade_imwon[$grade];
$dormitory_imwon = $dormitory_imwon[$grade][$gender];
$max_vote = $max_vote[$grade];

if($voted) {
	echo goIndex("이미 투표를 하셨습니다. id: {$id}");
	exit;
}


$sql = "

INSERT INTO
	`vote_info`
(`voted_to`, `grade`, `gender`, `type`)
VALUES
";

//남자 임원 투표
if(!isset($gradeMaleVote)) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 남자학년임원 정보에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
if(count($gradeMaleVote) != count($grade_imwon['male'])) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 남자학년임원 정보 개수에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
$len = count($gradeMaleVote);
$cnt = 0;
$format = "'{$grade}', 'male', 'grade'";
for($i = 0; $i < $len; $i++) {
	if((int)$gradeMaleVote[$i] == 1) {
		$sql .= "('{$grade_imwon['male'][$i]}', {$format}),";
		$cnt += 1;
	}
}
if($cnt > $max_vote['male']) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 남자학년임원은 최대 {$max_vote['male']}명에게 투표할 수 있습니다. 다시 시도해주세요");
	exit;
}
if($cnt == 0) {
	$sql .= "('기권', {$format}),";
}


//여자 임원 투표
if(!isset($gradeFemaleVote)) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 여자학년임원 정보에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
if(count($gradeFemaleVote) != count($grade_imwon['female'])) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 여자학년임원 정보 개수에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
$len = count($gradeFemaleVote);
$cnt = 0;
$format = "'{$grade}', 'female', 'grade'";
for($i = 0; $i < $len; $i++) {
	if((int)$gradeFemaleVote[$i] == 1) {
		$sql .= "('{$grade_imwon['female'][$i]}', {$format}),";
		$cnt += 1;
	}
}
if($cnt > $max_vote['female']) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 여자학년임원은 최대 {$max_vote['female']}명에게 투표할 수 있습니다. 다시 시도해주세요");
	exit;
}
if($cnt == 0) {
	$sql .= "('기권', {$format}),";
}


//기숙사 임원 투표
if(!isset($dormitoryVote)) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 기숙사임원 정보에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
if(count($dormitoryVote) != count($dormitory_imwon)) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 기숙사임원 정보 개수에 문제가 있습니다. 다시 시도해주세요");
	exit;
}
$len = count($dormitoryVote);
$cnt = 0;
$format = "'{$grade}', '{$gender}', 'dormitory'";
for($i = 0; $i < $len; $i++) {
	if((int)$dormitoryVote[$i] == 1) {
		$sql .= "('{$dormitory_imwon[$i]}', {$format}),";
		$cnt += 1;
	}
}
if($cnt > 1) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 기숙사임원은 최대 1명에게 투표할 수 있습니다. 다시 시도해주세요");
	exit;
}
if($cnt == 0) {
	$sql .= "('기권', {$format}),";
}


$sql = substr($sql, 0, -1);
$result = execute_query($sql);
if($result === FALSE) {
	echo goIndex("죄송합니다. 투표에 실패했습니다. 내부 오류입니다.");
	var_dump($sql);
	exit;
}

$sql = "
UPDATE `user_info`
SET `voted` = TRUE
WHERE `email` = '{$id}'
";

$result = execute_query($sql);
if($result === FALSE) {
	echo goIndex("죄송합니다. 사용자 정보를 찾지 못했습니다. 주변 선생님께 문의해주세요. id: {$id}");
	exit;
}

echo "<h1>수고하셨습니다. 정상적으로 투표가 완료되었습니다.</h1> <p>made by Web Project Team.<p>"

?>