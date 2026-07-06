
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bem-vindo ao Studio Franciele Cesario</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	@include('partials.pwa-head')
	<style>
		body, html {
			height: 100%;
			margin: 0;
			padding: 0;
			font-family: 'Segoe UI', sans-serif;
		}
		.video-bg {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			object-fit: cover;
			z-index: -1;
		}
		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(0,0,0,0.5);
			z-index: 0;
		}
		.content {
			position: relative;
			z-index: 1;
			color: #fff;
			text-align: center;
			top: 30vh;
		}
		.navbar {
			z-index: 2;
		}
	</style>
</head>
<body>
	<!-- Vídeo de fundo (exemplo do Unsplash, pode trocar por outro vídeo de salão de beleza) -->
	<video class="video-bg" autoplay muted loop>
		<source src="/videos/studio.mp4" type="video/mp4">
		Seu navegador não suporta vídeo em HTML5.
	</video>
	<div class="overlay"></div>

	<!-- Menu superior -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Studio Franciele Cesario</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link" href="/login">Fazer Login</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="content">
		<h1>Bem-vindo ao Studio Franciele Cesario</h1>
		<p style="font-size:1.3em;">Transforme seu visual com nossos serviços de beleza e bem-estar.<br>
		Agende seu horário e viva uma experiência única!</p>
		@auth
			<a href="{{ url('/agendamento') }}" class="btn btn-light btn-lg mt-3">Agende seu horário</a>
		@else
			<a href="{{ url('/login') }}" class="btn btn-light btn-lg mt-3">Agende seu horário</a>
		@endauth
	</div>

	@include('partials.pwa-install')
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

