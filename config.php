<?php
// define('GOOGLE_CLIENT_TOKEN', '197843889767-cgu83jdlh1k6069m96dspsj48eg9og2p.apps.googleusercontent.com');

// define('DB_HOST', 'localhost');
// define('DB_USER', 'sunglak7020');
// define('DB_PW', 'qq11qq11..');
// define('DB_NAME', 'sunglak7020');

define('GOOGLE_CLIENT_TOKEN', '1066738465595-vhkcpba38fqjfrrgq6r5b06fllidfnbb.apps.googleusercontent.com');

define('DB_HOST', 'localhost');
define('DB_USER', 'admin');
define('DB_PW', '1111');
define('DB_NAME', 'vote');

define('DEBUG', FALSE);
define('ADMIN_EMAIL', '201028@bmrschool.org');
define('SALT', 'saltforme');

$grade_imwon = array(
	'10학년' => array(
		'male' => array('김효준', '정환호'),
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

$dormitory_imwon = array(
	'10학년' => array(
		'male' => array('류강현', '박진영'),
		'female' => array('김하진 찬성', '김하진 반대'),
	),
	'11학년' => array(
		'male' => array('유준호 찬성', '유준호 반대'),
		'female' => array('정인하 찬성', '정인하 반대')
	),
	'12학년' => array(
		'male' => array('박준서 찬성', '박준서 반대'),
		'female' => array('김하은', '최하경')
	)
);

$max_vote = array(
	'10학년' => array(
		'male' => 2,
		'female' => 2,
	),
	'11학년' => array(
		'male' => 2,
		'female' => 1
	),
	'12학년' => array(
		'male' => 1,
		'female' => 1
	)
);

function con_db() {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);

	if ($mysqli -> connect_errno) {
		if (PRINT_DEBUG == true) {
			echo "MySQL연결에 실패했습니다: " . $mysqli -> connect_error;
			exit();
		}
		return -1;
	}

	$mysqli -> set_charset('utf8');

	return $mysqli;
}

function execute_query($sql) {
	$mysqli = con_db();

	$result = $mysqli -> query($sql);

	if ($result) {
		$mysqli -> close();
		return $result;
	}
	
	if (DEBUG == true) {
		echo "쿼리에 오류가 있습니다: " . $mysqli -> error;
		echo "<br> query: <br>";
		echo $sql;
		exit();
	}
	return FALSE;

}

function escape($str) {
	return htmlspecialchars($str);
}

function goIndex($msg) {
	if(DEBUG) {
		return "<script>alert('{$msg}');</script><a href='./index.php'>이동</a>";
	}
	return "<script>alert('{$msg}'); window.location.href = './index.php';</script>";
}

if(DEBUG) {
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
}
?>