<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ $title ?? config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
	
	<!-- Styles -->
	<link rel="stylesheet" href="{{ mix('css/app.css') }}" />
	<script src="{{ mix('js/app.js') }}"></script>
	<script src="/js/chartjs.min.js"></script>
	<link href="/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
	<link href="/css/nucleo-icons.css" rel="stylesheet" />
	<link href="/demo/demo.css" rel="stylesheet" />
	<style>
		.alerts-block {
			position: fixed!important;
			right: 10px!important;
			top: 1%!important;
			min-width: 240px;
			z-index: 1;
		}
		
		.alert {
			max-width: 340px;
		}
		
		.custom-file-label {
			position: absolute;
			top: 0;
			right: 0;
			left: 0;
			z-index: 1;
			height: calc(1.6em + 0.75rem + 2px);
			padding: 0.375rem 0.75rem;
			font-weight: 400;
			line-height: 1.6;
			color: rgba(255, 255, 255, 0.8);
			background-color: transparent;
			border: 1px solid #2b3553;
			border-radius: 0.25rem;
		}
		
		.custom-file-label::after {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			z-index: 3;
			display: block;
			height: calc(1.6em + 0.75rem);
			padding: 0.375rem 0.75rem;
			line-height: 1.6;
			color: rgba(255, 255, 255, 0.8);
			content: "Выберите";
			background-color: transparent;
			border-left: inherit;
			border-radius: 0 0.25rem 0.25rem 0;
		}
		
		.form-group .form-control, .input-group .form-control {
			padding: 8px;
		}
		
		select option {
			margin: 40px;
			background: #1f273d;
			color: #fff;
		}
		
		option:checked, option:hover {
			background: #3c4664!important;
			color: #fff;
		}
		
		.list-group.usersList > .list-group-item {
			color: #41415d;
			background: transparent;
			border-color: rgba(0, 0, 0, 0.05);
		}
		
		.list-group.usersList > .list-group-item:first-child {
			border-top: 0;
			border-radius: 0;
		}
		
		.list-group.usersList > .list-group-item:last-child {
			border-radius: 0;
			border-bottom: 0;
		}
	</style>
	@auth
	<script>
		$(document).ready(function() {
			axios.defaults.baseURL = '{{ config('app.url') }}api';
			axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
			axios.defaults.headers.common['Authorization'] = 'Bearer {{ auth()->user()->token }}';
			
			window.http = axios;
			window.alerts = (text, type = 'success', callback = false, timer = 8000) => {
				$.notify({
				  icon: "tim-icons icon-bell-55",
				  message: text,
				}, {
				  type: type,
				  timer: timer,
				  placement: {
					from: 'top',
					align: 'right'
				  }
				});
				
				if (callback != false)
				{
					setTimeout(callback, timer);
				}
			}
			window.isJson = (item) => {
				try {
					item = JSON.parse(item);
					return true;
				} catch (e) {
					return false;
				}
			}
		});
	</script>
	@endauth
</head>

<body>
	<div class="wrapper" id="app">
		@auth
		<div class="sidebar">
			<div class="sidebar-wrapper">
				<div class="logo">
					<a href="http://www.creative-tim.com" class="simple-text logo-normal">
						{{ $title ?? config('app.name', 'Laravel') }}
					</a>
				</div>
				@isset($isPageChats)
				<ul class="nav">
					<ul class="list-unstyled" id="list-tab" role="tablist">
						<a class="media mb-2 active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">
							<img src="/storage/avatars/2_avatar1584197940.jpg" class="ml-2 mr-3" width="48" height="48" alt="/storage/avatars/2_avatar1584197940.jpg">
							<div class="media-body">
								<h5 class="mt-0 mb-1">User Name</h5>
								Тестовое сообщение
							</div>
						</a>
						<a class="media mb-3" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">
							<img src="/storage/avatars/2_avatar1584197940.jpg" class="ml-2 mr-3" width="48" height="48" alt="/storage/avatars/2_avatar1584197940.jpg">
							<div class="media-body">
								<h5 class="mt-0 mb-1">User Name 2</h5>
								Тестовое сообщение
							</div>
						</a>
						<a class="media mb-3" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">
							<img src="/storage/avatars/2_avatar1584197940.jpg" class="ml-2 mr-3" width="48" height="48" alt="/storage/avatars/2_avatar1584197940.jpg">
							<div class="media-body">
								<h5 class="mt-0 mb-1">User Name 3</h5>
								Тестовое сообщение
							</div>
						</a>
						<a class="media mb-3" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
							<img src="/storage/avatars/2_avatar1584197940.jpg" class="ml-2 mr-3" width="48" height="48" alt="/storage/avatars/2_avatar1584197940.jpg">
							<div class="media-body">
								<h5 class="mt-0 mb-1">User Name 4</h5>
								Тестовое сообщение
							</div>
						</a>
					</ul>
				</ul>
				@else
				<ul class="nav">
					<li class="@isset($isPageHome) active @endisset">
						<a href="/admin">
							<i class="tim-icons icon-chart-pie-36"></i>
							<p>Главная</p>
						</a>
					</li>
					<li class="@isset($isPageChats) active @endisset">
						<a href="/admin/chats">
							<i class="tim-icons icon-chat-33"></i>
							<p>Чаты</p>
						</a>
					</li>
					<li class="@isset($isPagePayments) active @endisset">
						<a href="/admin/payments">
							<i class="tim-icons icon-wallet-43"></i>
							<p>Платежи</p>
						</a>
					</li>
					<li class="@isset($isPageMarkers) active @endisset">
						<a href="/admin/markers">
							<i class="tim-icons icon-pin"></i>
							<p>Маркеры</p>
						</a>
					</li>
					<li class="@isset($isPageNotify) active @endisset">
						<a href="/admin/notify">
							<i class="tim-icons icon-bell-55"></i>
							<p>Уведомления</p>
						</a>
					</li>
					<li class="@isset($isPageUsers) active @endisset">
						<a href="/admin/users">
							<i class="tim-icons icon-single-02"></i>
							<p>Пользователи</p>
						</a>
					</li>
				</ul>
				@endisset
			</div>
		</div>
		@endauth
		<div class="main-panel">
			@auth
			<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<div class="navbar-toggle d-inline">
							<button type="button" class="navbar-toggler">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</button>
						</div>
						<a class="navbar-brand" href="#pablo">ShopReklama.ru</a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
       				</button>
					<div class="collapse navbar-collapse" id="navigation">
						<ul class="navbar-nav ml-auto">
							<li class="dropdown nav-item">
								<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
									<div class="notification d-none d-lg-block d-xl-block"></div>
									<i class="tim-icons icon-sound-wave"></i>
									<p class="d-lg-none">
										Новое уведомление
									</p>
								</a>
								<ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
									<li class="nav-link">
										<a href="#" class="nav-item dropdown-item">Уведомление №1</a>
									</li>
									<li class="nav-link">
										<a href="#" class="nav-item dropdown-item">Уведомление №2</a>
									</li>
								</ul>
							</li>
							<li class="dropdown nav-item">
								<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
									<div class="photo">
										<img src="/img/anime3.png">
									</div>
									<b class="caret d-none d-lg-block d-xl-block"></b>
									<p class="d-lg-none">
										Выход
									</p>
								</a>
								<ul class="dropdown-menu dropdown-navbar">
									<li class="nav-link">
										<a href="#" class="nav-item dropdown-item">Профиль</a>
									</li>
									<li class="nav-link">
										<a href="#" class="nav-item dropdown-item">Настройки</a>
									</li>
									<div class="dropdown-divider"></div>
									<li class="nav-link">
										<a href="#" class="nav-item dropdown-item">Выход</a>
									</li>
								</ul>
							</li>
							<li class="separator d-lg-none"></li>
						</ul>
					</div>
				</div>
			</nav>
			@endauth
			<div id="alerts" class="alerts-block"></div>
			<div class="content" @guest style="padding: 80px 5px 20px 5px;" @endguest>
				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>