<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <title>Login</title>
  </head>
  <body>

    <div class="login">
      <div class="login-cont">
        <div class="left-logo">
          <img class="left-logo-image" src="{{asset('images/login/left-logo.jpg')}}">
          <div class="logo">
            <img src="{{asset('images/login/logo.png')}}">
          </div>
        </div>


        <div class="right-login-form">
          <div class="login-box">
			<h1>welcome back</h1>
			<form method="POST" action="{{ route('login') }}">
				@csrf
            <div class="input-group my-5">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror input_user" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email">
			</div>
			@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
			  <div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror input_pass" name="password" required autocomplete="current-password" placeholder="password">
			  </div>
			  @error('password')
			  <span class="invalid-feedback" role="alert">
				  <strong>{{ $message }}</strong>
			  </span>
		  @enderror
			<button class="btn" type="submit">login</button>
			</form>
          </div>
        </div>
      </div>
    </div>



  </body>
</html>

<style type="text/css">
.form-control:focus {
    border-color: #009688 !important;
}
  
.login {
  height: 100vh;
}
.input-group {
	margin: 10px 0px!important;
}

h1 {
	text-align: center;
    font-size: 36px;
    text-transform: uppercase;
    font-weight: 600;
	color: #009688;
	margin-bottom: 22px;
}

.login-cont {
  display: flex;
}

.left-logo,
.right-login-form {
  width: 50%;

}

.left-logo {
  position: relative;
}

.left-logo .logo img {
  position: absolute;
  top: 35%;
  left: 35%;
}

.left-logo .left-logo-image {
  width: 100%;
  height: 100vh;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.login .right-login-form button {
  width: 100%;
  background: #009688;
  color: white;
  margin-top: 17px;
}

.login-box {
  width: 38%;
  margin-left: 35%;
  margin-top: 35%;
}

.login .right-login-form label {
  text-align: left !important;
  font-weight: bold;
}

.login .right-login-form input {
  width: 100%;
}

.login .right-login-form span {
  color: #009688;
}

.login .right-login-form p {
  margin-top: 10px;
  text-align: center;
}

</style>