<!DOCTYPE html>
<?php
require "./config.php";
require_once "./google-api-php-client--PHP7.0_1/google-api-php-client--PHP7.0/vendor/autoload.php";

$id_token = escape($_POST['idtoken']);

$client = new Google_Client(['client_id' => GOOGLE_CLIENT_TOKEN]);
$payload = $client->verifyIdToken($id_token);

$id = '';
$verify_token = '';

if ($payload) {
	$id = $payload['email'];
	$verify_token = hash('sha256', $id . SALT);
} else {
	echo goIndex("죄송합니다. 로그인 인증에 실패했습니다. 다시 로그인해 주세요");
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
if($result == -1) {
	echo goIndex("죄송합니다. 사용자 정보를 찾지 못했습니다. 주변 선생님께 문의해주세요. id: {$id}");
	exit;
}
$result = $result -> fetch_assoc();
$voted = $result['voted'];
$grade = $result['grade'];
$gender = $result['gender'];
$name = $result['name'];

if($voted) {
	echo "
	";
}

if(isset($grade_imwon[$grade])) {
	$grade_imwon = $grade_imwon[$grade];
	$max_vote = $max_vote[$grade];
} else {
	echo goIndex("학년임원 정보를 불러오지 못했습니다. 새로고침해 주세요.");
	exit;
}

if(isset($dormitory_imwon[$grade][$gender])) {
	$dormitory_imwon = $dormitory_imwon[$grade][$gender];
} else {
	echo goIndex("기숙사 임원 정보를 불러오지 못했습니다. 새로고침해 주세요.");
	exit;
}

?>

<html>
	<head>
		<meta charset="utf8">
		<title>2021년 임원투표</title>
		<style>
			body{width: 600px; height: 100vh; display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; margin-left: calc((100vw - 600px)/2);}
			form{width: 400px;}
			input{width: 15px; height: 15px;}
			label{cursor: pointer; width: 350px; display: inline-block;}
			label:hover{background: #DDDDDD;}
			.title, label{padding-left: 50px;}
			.center{text-align: center;}
			.options{font-size: 20px;}
			.hide{display: none;}
		</style>
	</head>
	<body>
		<?php if($id == ADMIN_EMAIL) { ?>
		<a href='./admin.php?id=<?php echo $id;?>&verifyToken=<?php echo $verify_token;?>'>
			개표 결과 보기
		</a>
		<?php }?>
		<p class="center">
			<?php echo $grade;?>
			<?php if($gender == 'male') { ?>
				남자, 
			<?php } else { ?>
				여자, 
			<?php } ?>
			<?php echo $name . ', ' . $id . ' 계정으로 투표하고 있습니다.';?><br>
		</p>
		<p class="center">
			위 정보가 맞지 않다면 주변 선생님께 문의해주시기 바랍니다.<br>
			<strong>*개인 정보는 인증을 위한 목적으로만 저장됩니다. 비밀투표가 보장됩니다.*</strong>
		</p>
		<p>
			별무리 투표는 별무리 이메일 계정으로 가능하며, <strong>본인의 계정</strong>으로 로그인했는지 <strong>꼭 확인하고 투표</strong>해주시기 바랍니다.<br> 아래의 체크를 통해 본인 확인을 대신하도록 하겠습니다.  
		</p>
		<div>
			<label>
				위 내용을 숙지하였으며 이에 동의합니다.
				<input id="confirm" type="checkbox" onclick="showForm();">
			</label>
		</div>
		<div id="how-to-use" class='hide'>
			<h3>
				기권 방법 설명
			</h3>
			<p>
				투표 항목을 아무것도 체크하지 않을 시 자동으로 기권표로 처리됩니다.<br>
				2명 중 1명에게만 체크하는 것도 가능합니다.
			</p>
		</div>
		<form action="./vote_mysql.php" method="POST" class='hide'>
			<input type='hidden' name='id' value='<?php echo $id;?>'/>
			<input type='hidden' name='verifyToken' value='<?php echo $verify_token;?>'/>
			<hr>
			<p class="title">
				남자 학년 임원 투표<br>
				(최대 <?php echo $max_vote['male']?>명 투표할 수 있습니다)
			</p>
			<div class='vote-group'>
				<?php foreach($grade_imwon['male'] as $val) { ?>
					<label>
						<input 
							onclick="checkNumber(event);" 
							data-max-vote='<?php echo $max_vote['male']?>' 
							type="checkbox" 
							class="gradeMaleVote" 
							value="<?php echo $val;?>" 
						/>
						<span class="options"><?php echo $val;?></span>
					</label>
					<br>
				<?php } ?>
			</div>
			<br>
			<hr>
			<p class="title">
				여자 학년 임원 투표<br>
				(최대 <?php echo $max_vote['female']?>명 투표할 수 있습니다)
			</p>
			<div class='vote-group'>
				<?php foreach($grade_imwon['female'] as $val) { ?>
					<label>
						<input 
							onclick="checkNumber(event);" 
							data-max-vote='<?php echo $max_vote['female']?>' 
							type="checkbox" 
							class="gradeFemaleVote" 
							value="<?php echo $val;?>" 
						/>
						<span class="options"><?php echo $val;?></span>
					</label>
					<br>
				<?php } ?>
			</div>
			<br>
			<hr>
			<p class="title">
				기숙사 임원 투표<br>
				(최대 1명 투표할 수 있습니다)
			</p>
			<div class='vote-group'>
				<?php foreach($dormitory_imwon as $val) { ?>
					<label>
						<input 
							onclick="checkNumber(event);" 
							data-max-vote='1' 
							type="checkbox" 
							class="dormitoryVote" 
							value="<?php echo $val;?>"
						/>
						<span class="options"><?php echo $val;?></span>
					</label>
					<br>
				<?php } ?>
			</div>
			<br>
			<hr>
			<button type="button" onclick="handleSubmit();">투표</button>
		</form>
		<script>
			function get(query) {return document.querySelector(query);}
			function getAll(query){return document.querySelectorAll(query);}
			
			function showForm() {
				get('form').classList.remove('hide');
				get('#how-to-use').classList.remove('hide');
			}
			function handleSubmit() {
				if(!get('#confirm').checked) {
					alert('본인확인내용을 읽고 동의해 주세요.');
					return;
				}
				if(confirm('투표하시겠습니까?')) {
					let form = get('form');

					let gradeMale = getAll('.gradeMaleVote');
					let gradeFemale = getAll('.gradeFemaleVote');
					let dorm = getAll('.dormitoryVote');

					let inputs = '';

					let i = 0;
					for(i = 0; i < gradeMale.length; i++) {
						if(gradeMale[i].checked) {
							inputs += '<input type="hidden" name="gradeMaleVote[]" value="1">';
						} else {
							inputs += '<input type="hidden" name="gradeMaleVote[]" value="0">';
						}
					}

					for(i = 0; i < gradeFemale.length; i++) {
						if(gradeFemale[i].checked) {
							inputs += '<input type="hidden" name="gradeFemaleVote[]" value="1">';
						} else {
							inputs += '<input type="hidden" name="gradeFemaleVote[]" value="0">';
						}
					}

					for(i = 0; i < dorm.length; i++) {
						if(dorm[i].checked) {
							inputs += '<input type="hidden" name="dormitoryVote[]" value="1">';
						} else {
							inputs += '<input type="hidden" name="dormitoryVote[]" value="0">';
						}
					}

					form.innerHTML += inputs;
					form.submit();
				}
			}
			
			let a;
			let checkboxes;
			let sum;
			let max_vote;
			function checkNumber(event) {
				checkboxes = event.target.parentElement.parentElement.children;
				sum = 0;
				max_vote = Number(event.target.dataset.maxVote);
				a = event.target;
				for(let i = 0; i < checkboxes.length; i++) {
					if(checkboxes[i].tagName == 'LABEL') {
						if(checkboxes[i].firstElementChild.checked == true) sum += 1;
					}
				}
				if(sum > max_vote) {
					alert('최대 ' + max_vote + '명에게만 투표할 수 있습니다. 체크 해제 후 다시 투표해 주세요.');
					event.target.checked = !event.target.checked;
				}
			}
		
		</script>
	</body>
</html>