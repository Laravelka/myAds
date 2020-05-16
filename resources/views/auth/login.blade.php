@extends('layouts.app', ['isPageLogin' => true, 'title' => 'Авторизация'])) @section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Авторизация') }}</div>
				<div class="card-body">
					<form method="POST" action="/login">
						@csrf
						<div class="form-group row">
							<label for="phone_or_email" class="col-md-4 col-form-label text-md-right">{{ __('Телефон или E-mail') }}</label>

							<div class="col-md-6">
								<input id="phone_or_email" type="text" class="form-control @error('phone_or_email') is-invalid @enderror" name="phone_or_email" value="{{ old('phone_or_email') }}" required autofocus>
								@error('phone_or_email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

							<div class="col-md-6">
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
								@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input @error('remember_me') is-invalid @enderror" type="checkbox" id="remember_me" name="remember_me" value="1" checked>
										Запомнить меня?
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
										@error('remember_me')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</label>
								</div>
							</div>
						</div>
						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('Войти') }}
								</button>
								@if (Route::has('password.request'))
								<a class="btn btn-link" href="{{ route('password.request') }}">
									{{ __('Забыли пароль?') }}
								</a>
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	window.onload = () => {
		let rememberMe = document.getElementById('remember_me');

		rememberMe.onclick = () => {
			if(rememberMe.checked) {
				rememberMe.value = 1;
			}else {
				rememberMe.value = 0;
			}
		}
	};
</script>
@endsection