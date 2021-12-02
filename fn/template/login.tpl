<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<title>Вход в систему</title>
	<link rel="icon" href="./template/images/logo.png">
	<link href="./template/css/tabler.css" rel="stylesheet"/>
</head>
	
<body class="antialiased border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
		<div class="container-tight py-4">
			
		
		<form class="card card-md" action="." method="post" autocomplete="off">
			<div class="card-body">
			
			<div class="text-center mb-4">
				<a href="."><img src="./template/images/logo.png" height="96" alt=""></a>
			</div>
			
				<h2 class="card-title text-center mb-4">Вход в систему</h2>
				
				{msg}
				
				<div class="mb-3">
					<label class="form-label">Имя пользователя</label>
					<select name="lo" class="form-select">
						{logins}
					</select>
				</div>
				<div class="mb-2">
					<label class="form-label">Пароль</label>
					<div class="input-group input-group-flat">
						<input type="password" class="form-control" name="pa" placeholder="Пароль" autocomplete="off">
					</div>
				</div>
				
				<div class="form-footer">
					<button type="submit" class="btn btn-primary w-100">Войти</button>
				</div>
			</div>        
        </form>
      </div>
    </div>
    <script src="./template/js/jquery.min.js"></script>
    <script src="./template/js/tabler.min.js"></script>
    <script src="./template/js/login.js"></script>
  </body>
</html>