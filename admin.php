<?php

require "./config.php";

$id = escape($_GET['id']);
$verifyToken = escape($_GET['verifyToken']);

if(hash('sha256', $id . SALT) != $verifyToken) {
	echo goIndex('계정 인증 오류입니다.');
	exit;
}

if($id != ADMIN_EMAIL) {
	echo goIndex('관리자 계정으로 로그인해 주세요');
	exit;
}

if(isset($_GET['do'])) {
	if($_GET['do'] == 'delete') {

		$sql = "DELETE FROM `vote_info`";
		execute_query($sql);
		echo goIndex('삭제되었습니다');
		exit;
	}
}
?>

<button onclick="del();">초기화</button>
<script>
	function del(){
		if(confirm('투표 결과를 모두 지우시겠습니까?')) {
			window.location.href = './admin.php?id=<?php echo $id;?>&verifyToken=<?php echo $verifyToken;?>&do=delete';
		}
	}
</script>

<?php

$mysqli = con_db();

$sql = "
SELECT
    COUNT(*) AS cnt
FROM
    `vote_info`
WHERE
    `voted_to` = ";

echo "<h3>학년 임원</h3>";
foreach($grade_imwon as $grade => $row) {
	echo "<h5>{$grade} 임원</h5>";
	foreach($row as $gender => $col) {
		foreach($col as $name) {
			$result = $mysqli -> query("{$sql}'{$name}'");
			if($result) {
				$result = $result -> fetch_assoc()['cnt'];
				echo "{$grade} {$gender} {$name}: {$result}표<br>";
			} else {
				echo $sql;
			}
		}
		$result = $mysqli -> query("
			SELECT
				COUNT(*) AS cnt
			FROM
				`vote_info`
			WHERE
			`grade` = '{$grade}' AND `gender` = '{$gender}' AND `type` = 'grade' AND `voted_to` = '기권'
		");
		if($result) {
			$result = $result -> fetch_assoc()['cnt'];
			echo "{$grade} {$gender} 기권표: {$result}표<hr>";
		}
	}
}

echo "<h3>기숙사 임원</h3>";
foreach($dormitory_imwon as $grade => $row) {
	foreach($row as $gender => $col) {
	echo "<h5>{$grade} {$gender}기숙사 임원</h5>";
		foreach($col as $name) {
			$result = $mysqli -> query("{$sql}'{$name}'");
			if($result) {
				$result = $result -> fetch_assoc()['cnt'];
				echo "{$grade} {$gender} {$name}: {$result}표<br>";
			} else {
				echo $sql;
			}
		}
		$result = $mysqli -> query("
			SELECT
				COUNT(*) AS cnt
			FROM
				`vote_info`
			WHERE
			`grade` = '{$grade}' AND `gender` = '{$gender}' AND `type` = 'grade' AND `voted_to` = '기권'
		");
		if($result) {
			$result = $result -> fetch_assoc()['cnt'];
			echo "{$grade} {$gender} 기권표: {$result}표<hr>";
		}
	}
}

?>