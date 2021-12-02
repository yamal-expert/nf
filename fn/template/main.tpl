<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Фабрика Новостей</title>
	
	<link rel="icon" href="./template/images/logo.png">

    <link href="./template/css/tabler.css" rel="stylesheet"/>
    <link href="./template/css/style.css" rel="stylesheet"/>
</head>

<body class="antialiased">
	<div class="wrapper">
		<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
			<div class="container-fluid">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"><span class="navbar-toggler-icon"></span></button>
				<h1 class="navbar-brand navbar-brand-autodark">
					<a href="/fn/"><img src="./template/images/logo.png" height="64" alt="Завод Новостей" class="navbar-brand-image"></a>
				</h1>
				
				<div class="collapse navbar-collapse" id="navbar-menu">
					<ul class="navbar-nav pt-lg-3">
						{news-dates}  
						<li>&nbsp;</li>						
					</ul>
					<div class="panel-1 fixed-bottom bg-blue-lt p-2 mx-2">
						<button class="btn btn-warning w-100 show-search-panel">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg> Поиск
						</button>
					</div>
				</div>
			</div>
		</aside>
		
		<div class="page-wrapper  ajax-content-1">
			
			<div class="jumbotron d-flex align-items-center min-vh-100">
				<div class="container text-center">
				
					<img src="./template/images/logo.png" height="128" alt="Завод Новостей">
				
				</div>
			</div>
			
			
		</div>
	</div>
    
    <script src="./template/js/jquery.min.js"></script>
    <script src="./template/js/tabler.min.js"></script>
    <script src="./template/js/scripts.js"></script>
    
  </body>
</html>