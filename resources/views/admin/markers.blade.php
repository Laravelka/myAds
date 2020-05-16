@extends('layouts.app', ['isPageMarkers' => true, 'title' => 'Маркеры'])) @section('content')
<div class="w-100 d-flex justify-content-cente">
	<button type="submit" id="createMarkerOpen" class="btn btn-primary">
		{{ __('Добавить маркер') }}
	</button>
</div>
<div class="w-100" style="
	overflow-x: scroll;
">
	<table class="table">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th>Адрес</th>
				<th>Тип</th>
				<th>Изображение</th>
				<th>Размер</th>
				<th>Широта</th>
				<th>Долгота</th>
				<th class="text-right">Цена(месяц)</th>
				<th class="text-right">Цена(год)</th>
				<th class="text-right">Тип цены</th>
				<th class="text-right">Статус</th>
				<th class="text-right">Действия</th>
			</tr>
		</thead>
		<tbody>
			@foreach($markers as $marker)
			<tr id="marker-body-{{ $marker->id }}">
				<td class="text-center">{{ $marker->id }}</td>
				<td>{{ $marker->address }}</td>
				<td>{{ $marker->type }}</td>
				<td><a href="{{ $marker->image }}"  class="btn btn-sm btn-success" download>Скачать</a> <a href="{{ $marker->image }}"  class="btn btn-sm btn-success">Открыть</a></td>
				<td>{{ $marker->size_billboard }}</td>
				<td>{{ $marker->latitude }}</td>
				<td>{{ $marker->longitude }}</td>
				<td class="text-right">{{ $marker->price_month }} ₽</td>
				<td class="text-right">{{ $marker->price_year }} ₽</td>
				<td class="text-right">{{ $marker->type_price == 'special' ? 'Специальная' : 'Нормальная' }}</td>
				<td class="text-right">{{ $marker->status == 'success' ? 'Занят' : 'Свободен' }}</td>
				<td class="td-actions text-right">
					<button type="button" data-marker="{{ json_encode((array) $marker) }}" class="btn btn-success btn-link btn-icon btn-sm editMarker">
						<i class="tim-icons icon-settings"></i>
					</button>
					<button type="button" rel="tooltip" data-marker="{{ json_encode((array) $marker) }}" class="btn btn-danger btn-link btn-icon btn-sm deleteMarker">
						<i class="tim-icons icon-simple-remove"></i>
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class="modal modal-black fade" id="editMarkerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Редактировать маркер</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="tim-icons icon-simple-remove"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" id="editMarker" enctype="multipart/form-data">
					<input type="hidden" id="editMarker-id" name="id">
					<div class="form-group">
						<label for="address">Адрес</label>
						<input type="text" class="form-control" id="editMarker-address" name="address" placeholder="Введите адрес">
						<span class="invalid-feedback" id="editMarker-error-address" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="type">Тип маркера</label>
						<select id="editMarker-type" class="form-control">
							<option value="transport" >Транспорт</option>
							<option value="stops">Остановка</option>
							<option value="cafes">Кафе</option>
							<option value="gyms">Спротзал</option>
							<option value="billboard">Рекламные щит</option>
						</select>
						<span class="invalid-feedback" id="editMarker-error-type" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="image" class="w-100">Изображение <a href="#" id="image-url" class="badge badge-success float-right mt-1">Открыть</a><a href="#" id="image-download" class="badge badge-success float-right mt-1 mr-1" download>Скачать</a></label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="image" id="editMarker-image">
							<label class="custom-file-label" for="editMarker-image" data-browse="Выберите">Доступные форматы: jpeg,png,jpg,svg</label>
						</div>
						<span class="invalid-feedback" id="editMarker-error-image" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="size_billboard">Размер</label>
						<input type="text" class="form-control" id="editMarker-size_billboard" name="size_billboard" placeholder="Введите размер биллборда">
						<span class="invalid-feedback" id="editMarker-error-size_billboard" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="latitude">Широта</label>
						<input type="text" class="form-control" id="editMarker-latitude" name="latitude" placeholder="Введите широту">
						<span class="invalid-feedback" id="editMarker-error-latitude" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="longitude">Долгота</label>
						<input type="text" class="form-control" id="editMarker-longitude" name="longitude" placeholder="Введите долготу">
						<span class="invalid-feedback" id="editMarker-error-longitude" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="price_month">Цена в месяц</label>
						<input type="number" class="form-control" id="editMarker-price_month" name="price_month" placeholder="Введите цену">
						<span class="invalid-feedback" id="editMarker-error-price_month" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="price_year">Цена в год</label>
						<input type="number" class="form-control" id="editMarker-price_year" name="price_year" placeholder="Введите цену">
						<span class="invalid-feedback" id="editMarker-error-price_year" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="type_price">Тип цены</label>
						<select id="editMarker-type_price" class="form-control">
							<option value="normal" >Нормальная</option>
							<option value="special">Специальная</option>
						</select>
						<span class="invalid-feedback" id="editMarker-error-type_price" role="alert"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="editMarkerOk">Сохранить</button>
				<button type="button" class="btn btn-danger ml-2" data-dismiss="modal" aria-hidden="true">Отмена</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-black fade" id="createMarkerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить маркер</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="tim-icons icon-simple-remove"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" id="createMarker" enctype="multipart/form-data">
					<div class="form-group">
						<label for="address">Адрес</label>
						<input type="text" class="form-control" id="createMarker-address" name="address" placeholder="Введите адрес">
						<span class="invalid-feedback" id="createMarker-error-address" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="typee">Тип маркера</label>
						<select id="createMarker-type" class="form-control">
							<option value="transport" >Транспорт</option>
							<option value="stops">Остановка</option>
							<option value="cafes">Кафе</option>
							<option value="gyms">Спротзал</option>
							<option value="billboard">Рекламные щит</option>
						</select>
						<span class="invalid-feedback" id="createMarker-error-type" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="image" class="w-100">Изображение</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="image" id="createMarker-image">
							<label class="custom-file-label" for="createMarker-image" data-browse="Выберите">Доступные форматы: jpeg,png,jpg,svg</label>
						</div>
						<span class="invalid-feedback" id="createMarker-error-image" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="size_billboard">Размер</label>
						<input type="text" class="form-control" id="createMarker-size_billboard" name="size_billboard" placeholder="Введите размер биллборда">
						<span class="invalid-feedback" id="createMarker-error-size_billboard" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="latitude">Широта</label>
						<input type="text" class="form-control" id="createMarker-latitude" name="latitude" placeholder="Введите широту">
						<span class="invalid-feedback" id="createMarker-error-latitude" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="longitude">Долгота</label>
						<input type="text" class="form-control" id="createMarker-longitude" name="longitude" placeholder="Введите долготу">
						<span class="invalid-feedback" id="createMarker-error-longitude" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="price_month">Цена в месяц</label>
						<input type="number" class="form-control" id="createMarker-price_month" name="price_month" placeholder="Введите цену">
						<span class="invalid-feedback" id="createMarker-error-price_month" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="price_year">Цена в год</label>
						<input type="number" class="form-control" id="createMarker-price_year" name="price_year" placeholder="Введите цену">
						<span class="invalid-feedback" id="createMarker-error-price_year" role="alert"></span>
					</div>
					<div class="form-group">
						<label for="type_price">Тип цены</label>
						<select id="createMarker-type_price" class="form-control">
							<option value="normal" >Нормальная</option>
							<option value="special">Специальная</option>
						</select>
						<span class="invalid-feedback" id="createMarker-error-type_price" role="alert"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger ml-2" data-dismiss="modal" aria-hidden="true">Отмена</button>
				<button type="button" class="btn btn-success" id="createMarkerOk">Отправить</button>
			</div>
		</div>
	</div>
</div>
<style>
	
</style>
<script>
	$(document).ready(function() {
		var formName = 'editMarker';
		var createFormName = 'createMarker';
		
		const authToken = '{{ auth()->user()->token }}';
		
		$('.editMarker').on('click', function(e) {
			e.preventDefault();
			const marker = $(this).data('marker');
			
			for(var key in marker)
			{
				let newValue = marker[key];
				const element = document.getElementById(formName + '-' + key);
				if (key == 'image')
				{
					$('#image-url').attr('href', newValue);
					$('#image-download').attr('href', newValue);
				}
				else if (key == 'type_price')
				{
					$(element, 'option[value=' + newValue + ']').attr('selected','selected');
				}
				else if (key == 'type')
				{
					$(element, 'option[value=' + newValue + ']').attr('selected','selected');
				}
				else if (element !== null)
				{
					element.value = newValue;
				}
			}
			$('#editMarkerModal').modal({show: true});
		});
		
		$('.deleteMarker').on('click', function(e) {
			e.preventDefault();
			const marker = $(this).data('marker');
			
			http.delete('/admin/markers/delete/' + marker.id, marker)
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
						$('#marker-body-' + marker.id).remove();
						
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
				
				if (isJson(data) || typeof data == 'object')
				{
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
		
		$('#editMarkerOk').click(function(e) {
			e.preventDefault();
			
			var formData = new FormData(document.getElementById(formName));
			
			formData.append('type_price', document.getElementById(formName + '-type_price').value);
			
			http.post('/admin/markers/update', formData).then((response) => {
				const data = response.data;
				
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
		
		$('#createMarkerOpen').click(function(e) {
			e.preventDefault();
			
			$('#createMarkerModal').modal({show: true});
		});
		
		$('#createMarkerOk').click(function(e) {
			e.preventDefault();
			
			var formData = new FormData(document.getElementById(createFormName));
			
			formData.append('type_price', document.getElementById(createFormName + '-type_price').value);
			
			http.post('/admin/markers/create', formData).then((response) => {
				const data = response.data;
				
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
					console.error(data);
				}
			});
		});
	});
</script>
@endsection