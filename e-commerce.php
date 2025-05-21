<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="style.css" />
	<title>Sign Up Form</title>
</head>

<body>
	<div class="container">
 		<div class="forms-container">
			<div class="signin-signup">
				<!-- Login Form -->
				<form action="login.php" method="POST" class="sign-in-form">
					<h2 class="title">Log In</h2>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="username" placeholder="Username" required />
					</div>
					<div class="password-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
					<input type="submit" value="Login" class="btn solid" />
				</form>

				<!-- Sign Up Form -->
				<form action="signup.php" method="POST" class="sign-up-form">
					<h2 class="title">Sign up</h2>
                    <div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="first_name" placeholder="First Name" />
					</div>
                    <div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="last_name" placeholder="Last Name" />
					</div>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="username" placeholder="Username"/>
					</div>
					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="email" name="email" placeholder="Email"/>
					</div>
					<div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Password"/>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password"/>
                    </div>
                    <div id="message" style="color: red; display: none;">Passwords do not match!</div>
                    <div id="requirements" style="color: red; display: none;"></div>
					<input type="submit" class="btn" value="Sign up" />
				</form>
			</div>
		</div>

		<div class="panels-container">
			<div class="panel left-panel">
				<div class="content">
					<h3>New to our community?</h3>
					<p>
						Discover a world of possibilities! Join us and explore a vibrant
          community where ideas flourish and connections thrive.
					</p>
					<button class="btn transparent" id="sign-up-btn">
						Sign up
					</button>
				</div>
				<img src="https://i.ibb.co/6HXL6q1/Privacy-policy-rafiki.png" class="image" alt="" />
			</div>
			<div class="panel right-panel">
				<div class="content">
					<h3>One of Our Valued Members</h3>
					<p>
						Thank you for being part of our community. Your presence enriches our
          shared experiences. Let's continue this journey together!
					</p>
					<button class="btn transparent" id="sign-in-btn">
						Log In
					</button>
				</div>
				<img src="https://i.ibb.co/nP8H853/Mobile-login-rafiki.png" class="image" alt="" />
			</div>
		</div>
	</div>

<script src="script.js"></script>
</body>
</html>