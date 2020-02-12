

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
    
<head>
	<title>Event App</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>
<style>
	/* Coded with love by Mutiullah Samim */
    body,
		html {
			margin: 0;
			padding: 0;
			height: 100%;
			background-image: url('http://event-app.ohm-conception.com/images/background.jpg');
			background-repeat: no-repeat;
		}
		.user_card {
			height: 400px;
			width: 350px;
			margin-left: 10%;
			margin-top: auto;
			margin-bottom: auto;
			background: white ;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			border-radius: 5px;

		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -75px;
			border-radius: 50%;
			background: #1565c0;;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		
		.login_btn {
			width: 100%;
			background: teal !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group {
			width: 373px !important;
		}
		.input-group-text {
			background: white !important;
			color: #1565C0 !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
		}
		.my_title {
			position: absolute;
    		top: 9%;          
			color: black;
		}
		</style>
<!--Coded with love by Mutiullah Samim-->
<body>
	<div class="container h-100">
		<div class="d-flex justify-content-end h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center form_container">
					<h4 class="my_title">Welcome Back</h4>
					<form method="POST" action="{{ route('login') }}">
						@csrf
						<div class="input-group mb-3">
							{{-- <input type="text" name="" class="form-control input_user" value="" placeholder="username"> --}}
							<input id="email" type="email" class="form-control @error('email') is-invalid @enderror input_user" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
						</div>
						<div class="input-group mb-2">
							{{-- <input type="password" name="" class="form-control input_pass" value="" placeholder="password"> --}}
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror input_pass" name="password" required autocomplete="current-password" placeholder="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
						</div>
							<div class="mt-3 ">
				 	<button type="submit" name="button" class="btn login_btn">Login</button>
				   </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
