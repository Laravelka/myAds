@extends('layouts.app') @section('content')
<div class="w-100 d-flex justify-content-cente">
	<button type="submit" id="createUserOpen" class="btn btn-primary">
		{{ __('Добавить пользователя') }}
	</button>
</div>
<div class="w-100" style="
	overflow-x: scroll;
">
	<table class="table">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th>Роль</th>
				<th>Имя</th>
				<th>Аватар</th>
				<th>Телефон</th>
				<th>E-mail</th>
				<th>Зарегистрирован</th>
				<th class="text-right">Посл. вход</th>
				<th class="text-right">Действия</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user)
			<tr id="user-body-{{ $user['id'] }}">
				<td class="text-center">{{ $user['id'] }}</td>
				<td>{{ $user['role'] == 'admin' ? 'Админ' : 'Пользователь' }}</td>
				<td>{{ $user['name'] }}</td>
				<td><a href="{{ $user['image'] }}"  class="btn btn-sm btn-success" download>Скачать</a> <a href="{{ $user['image'] }}"  class="btn btn-sm btn-success">Открыть</a></td>
				<td>{{ $user['phone'] }}</td>
				<td>{{ $user['email'] }}</td>
				<td>{{ $user['created_at'] }}</td>
				<td class="text-right">{{ $user['updated_at'] }}</td>
				<td class="td-actions text-right">
					<button type="button" data-user="{{ json_encode($user->toArray()) }}" class="btn btn-success btn-link btn-icon btn-sm editUser">
						<i class="tim-icons icon-settings"></i>
					</button>
					<button type="button" rel="tooltip" data-user="{{ json_encode($user->toArray()) }}" class="btn btn-danger btn-link btn-icon btn-sm deleteUser">
						<i class="tim-icons icon-simple-remove"></i>
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class="modal modal-black fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Редактировать пользователя</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="tim-icons icon-simple-remove"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" id="editUser" enctype="multipart/form-data">
					<input type="hidden" id="editUser-id" name="id">
					<div class="form-group">
						<label for="name">Имя</label>
						<input type="text" class="form-control" id="editUser-name" name="name" placeholder="Введите имя">
						<span class="invalid-feedback" id="editUser-error-name" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="role">Роль</label>
						<select id="editUser-role" class="form-control">
							<option value="user" >Пользователь</option>
							<option value="admin">Администратор</option>
						</select>
						<span class="invalid-feedback" id="createUser-error-role" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="image" class="w-100">Аватар</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="image" id="editUser-image">
							<label class="custom-file-label" for="editUser-image" data-browse="Выберите">Доступные форматы: jpeg,png,jpg,svg</label>
						</div>
						<span class="invalid-feedback" id="editUser-error-image" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="phone">Телефон</label>
						<input type="text" class="form-control" id="editUser-phone" name="phone" placeholder="Введите номер телефона">
						<span class="invalid-feedback" id="editUser-error-phone" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="editUser-email" name="email" placeholder="Введите e-mail">
						<span class="invalid-feedback" id="editUser-error-email" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="password">Пароль</label>
						<input type="password" class="form-control" id="editUser-password" name="password">
						<span class="invalid-feedback" id="editUser-error-password" role="alert"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger ml-2" data-dismiss="modal" aria-hidden="true">Отмена</button>
				<button type="button" class="btn btn-success" id="editUserOk">Сохранить</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-black fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить пользователь</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="tim-icons icon-simple-remove"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" id="createUser" enctype="multipart/form-data">
					<div class="form-group">
						<label for="name">Имя</label>
						<input type="text" class="form-control" id="createUser-name" name="name" placeholder="Введите имя">
						<span class="invalid-feedback" id="createUser-error-name" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="role">Роль</label>
						<select id="createUser-role" class="form-control">
							<option value="user" >Пользователь</option>
							<option value="admin">Администратор</option>
						</select>
						<span class="invalid-feedback" id="createUser-error-role" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="image" class="w-100">Аватар</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="image" id="createUser-image">
							<label class="custom-file-label" for="createUser-image" data-browse="Выберите">Доступные форматы: jpeg,png,jpg,svg</label>
						</div>
						<span class="invalid-feedback" id="createUser-error-image" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="phone">Телефон</label>
						<input type="text" class="form-control" id="createUser-phone" name="phone" placeholder="Введите номер телефона">
						<span class="invalid-feedback" id="createUser-error-phone" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="createUser-email" name="email" placeholder="Введите e-mail">
						<span class="invalid-feedback" id="createUser-error-email" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="password">Пароль</label>
						<input type="password" class="form-control" id="createUser-password" name="password">
						<span class="invalid-feedback" id="createUser-error-password" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="password_confirmation">Пароль повторно</label>
						<input type="password" class="form-control" id="createUser-password_confirmation" name="password_confirmation">
						<span class="invalid-feedback" id="createUser-error-password_confirmation" role="alert"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger ml-2" data-dismiss="modal" aria-hidden="true">Отмена</button>
				<button type="button" class="btn btn-success" id="createUserOk">Добавить</button>
			</div>
		</div>
	</div>
</div>
<style>
	
</style>
<script>
	$(document).ready(function() {
		var formName = 'editUser';
		var createFormName = 'createUser';
		
		const authToken = '{{ auth()->user()->token }}';
		
		$('.editUser').on('click', function(e) {
			e.preventDefault();
			const user = $(this).data('user');
			
			console.log(user);
			
			for(var key in user)
			{
				let newValue = user[key];
				const element = document.getElementById(formName + '-' + key);
				
				if (key == 'image')
				{
					$('#image-url').attr('href', newValue);
					$('#image-download').attr('href', newValue);
				}
				else if (key == 'role')
				{
					$(element, 'option[value=' + newValue + ']').attr('selected','selected');
				}
				else if (element !== null)
				{
					element.value = newValue;
				}
			}
			$('#editUserModal').modal({show: true});
		});
		
		$('.deleteUser').on('click', function(e) {
			e.preventDefault();
			const user = $(this).data('user');
			
			http.delete('/admin/users/delete/' + user.id, user)
			.then((response) => {
				const data = response.data;
				
				if (data.error)
				{
					if (data.message)
					{
						alerts(data.message, 'danger');
					}
					else
					{
						alerts('Пустой ответ сервера', 'danger');
						
						console.error(data);
					}
				}
				else
				{
					if (data.message)
					{
						$('#user-body-' + user.id).remove();
						
						alerts(data.message);
					}
					else if (data.redirect)
					{
						location.href = data.redirect;
					}
					else
					{
						alerts('Пустой ответ сервера', 'danger');
					}
				}
			})
			.catch((error) => {
				console.log('err: ', error.response);
				
				if (typeof error.response == 'object')
				{
					const data = error.response;
					
					if (data.error)
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else
						{
							alerts('Пустой ответ сервера', 'danger');
							
							console.error(data);
						}
					}
					else
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else
						{
							alerts('Пустой ответ сервера', 'danger');
							
							console.error(data);
						}
					}
				}
				else
				{
					alerts('Произошла ошибка, перезагрузите страницу', 'danger');
							
					console.error(data);
				}
			});
		});
		
		$('#editUserOk').click(function(e) {
			e.preventDefault();
			
			var formData = new FormData(document.getElementById(formName));
			
			formData.append('role', document.getElementById(createFormName + '-role').value);
			
			http.post('/admin/users/update', formData).then((response) => {
				const data = response.data ? response.data : false;
				
				if (data.error)
				{
					if (data.message)
					{
						alerts(data.message, 'danger');
					}
					else if (data.messages)
					{
						var i = 0;
						var form = $('#' + formName);
						
						$.each(data.messages, function(key, msg) {
							let message;

							if (i == 0)
							{
								$('#' + formName + '-' + key).focus();
							}
							message = (Array.isArray(msg) ? msg[0] : msg);
							
							form.find('#' + formName + '-error-' + key).html('<strong>' + message + '</strong>').addClass('d-block');
							form.find('#' + formName + '-' + key).addClass('is-invalid');

							setTimeout(function() {
								form.find('#' + formName + '-error-' + key).html('').removeClass('d-block');
								form.find('#' + formName + '-' + key).removeClass('is-invalid');
							}, 4000);

							i++;
						});
					}
				}
				else
				{
					if (data.message)
					{
						alerts(data.message);
					}
					else if (data.redirect)
					{
						location.href = data.redirect;
					}
					else
					{
						alerts('Пустой ответ сервера', 'danger');
					}
				}
			})
			.catch((error) => {
				const data = error.response.data;
				
				if (typeof data == 'object')
				{
					if (data.error)
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else if (data.messages)
						{
							var i = 0;
							var form = $('#' + formName);

							$.each(data.messages, function(key, msg) {
								let message;

								if (i == 0)
								{
									$('#' + formName + '-' + key).focus();
								}
								message = (Array.isArray(msg) ? msg[0] : msg);

								console.log('#' + formName + '-error-' + key);

								form.find('#' + formName + '-error-' + key).html('<strong>' + message + '</strong>').addClass('d-block');
								form.find('#' + formName + '-' + key).addClass('is-invalid');

								setTimeout(function() {
									form.find('#' + formName + '-error-' + key).html('').removeClass('d-block');
									form.find('#' + formName + '-' + key).removeClass('is-invalid');
								}, 4000);

								i++;
							});
						}
					}
					else
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else if (data.redirect)
						{
							location.href = data.redirect;
						}
						else
						{
							alerts('Пустой ответ сервера', 'danger');
						}
					}
				}
				else
				{
					console.error(data);
				}
			});
		});
		
		$('#createUserOpen').click(function(e) {
			e.preventDefault();
			
			$('#createUserModal').modal({show: true});
		});
		
		$('#createUserOk').click(function(e) {
			e.preventDefault();
			
			var formData = new FormData(document.getElementById(createFormName));
			
			formData.append('role', document.getElementById(createFormName + '-role').value);
			
			http.post('/admin/users/create', formData).then((response) => {
				const data = response.data ? response.data : false;
				
				if (typeof data == 'object')
				{
					if (data.error)
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else if (data.messages)
						{
							var i = 0;
							var form = $('#' + createFormName);

							$.each(data.messages, function(key, msg) {
								let message;

								if (i == 0)
								{
									$('#' + createFormName + '-' + key).focus();
								}
								message = (Array.isArray(msg) ? msg[0] : msg);

								form.find('#' + createFormName + '-error-' + key).html('<strong>' + message + '</strong>').addClass('d-block');
								form.find('#' + createFormName + '-' + key).addClass('is-invalid');

								setTimeout(function() {
									form.find('#' + createFormName + '-error-' + key).html('').removeClass('d-block');
									form.find('#' + createFormName + '-' + key).removeClass('is-invalid');
								}, 4000);

								i++;
							});
						}
					}
					else
					{
						if (data.message)
						{
							alerts(data.message, 'success', function() {
								location.reload();
							}, 4000);
						}
						else if (data.redirect)
						{
							location.href = data.redirect;
						}
						else
						{
							alerts('Пустой ответ сервера', 'danger');
						}

					}
				}
			})
			.catch((error) => {
				const data = error.response.data ? error.response.data : false;
				
				if (typeof data == 'object')
				{
					if (data.error)
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else if (data.messages)
						{
							var i = 0;
							var form = $('#' + createFormName);

							$.each(data.messages, function(key, msg) {
								let message;

								if (i == 0)
								{
									$('#' + createFormName + '-' + key).focus();
								}
								message = (Array.isArray(msg) ? msg[0] : msg);
								
								form.find('#' + createFormName + '-error-' + key).html('<strong>' + message + '</strong>').addClass('d-block');
								form.find('#' + createFormName + '-' + key).addClass('is-invalid');

								setTimeout(function() {
									form.find('#' + createFormName + '-error-' + key).html('').removeClass('d-block');
									form.find('#' + createFormName + '-' + key).removeClass('is-invalid');
								}, 4000);

								i++;
							});
						}
					}
					else
					{
						if (data.message)
						{
							alerts(data.message, 'danger');
						}
						else if (data.redirect)
						{
							location.href = data.redirect;
						}
						else
						{
							alerts('Пустой ответ сервера', 'danger');
						}
					}
				}
				else
				{
					alerts('Пустой ответ сервера', 'danger');
					
					console.error(data);
				}
			});
		});
	});
</script>
@endsection