<?php

require "./config.php";

?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf8" />
		<meta
			name="google-signin-client_id"
			content="<?php echo GOOGLE_CLIENT_TOKEN; ?>"
		/>
		<title>2021년 임원투표</title>
	</head>
	<style>
		body{width: 100vw; height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0;}
		.hide{display: none !important;}
	</style>
	<body>
		<h1>
			2021년 임원투표
		</h1>
		<h3>
			본인의 별무리 계정으로 로그인해 주세요.
		</h3>
		<div>
			<div class="g-signin2" data-onsuccess="onSignIn"></div>
			<a id="signout" href="#" class="hide" onclick="signOut();">Sign out</a>
		</div>
		<form action="./vote.php" method="POST">
			<input type="hidden" id="idtoken" name="idtoken"/>
		</form>
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<script>
			function get(query) {
				return document.querySelector(query);
			}
			ssigig
			function onSignIn(googleUser) {
				let form = get('form');
				let input = get('#idtoken');
				
				get('.g-signin2').classList.add('hide');
				get('#signout').classList.remove('hide');
				
				if(!confirm(googleUser.getBasicProfile().getName() + '님으로 로그인 하셨습니다. 취소를 누르면 로그아웃됩니다.')) {
					signOut();
					return;
				}
				
				input.value = googleUser.getAuthResponse().id_token;
				form.submit();
			}
			function signOut() {
				var auth2 = gapi.auth2.getAuthInstance();
				auth2.signOut().then(function () {
					get('.g-signin2').classList.remove('hide');
					get('#signout').classList.add('hide');
					
					alert('로그아웃 되었습니다.');
				});
			}
		</script>
	</body>
</html>