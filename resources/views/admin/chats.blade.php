@extends('layouts.app', ['isPageChats' => true, 'title' => 'Чаты']) @section('content')
<div class="w-100 d-flex justify-content-cente">
	
</div>
<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
		Page 1
	</div>
	<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
		Page 2
	</div>
	<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
		Page 3
	</div>
	<div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
		Page 4
	</div>
</div>
<div class="fixed-bottom form-row">
	<div class="col-3"></div>
	<div class="chat-bottom col-8">
		<div class="input-group mb-0">
			<input type="text" class="form-control" placeholder="Имя получателя" aria-label="Имя получателя" aria-describedby="basic-addon2">
			<div class="input-group-append">
				<button class="" type="button"><i class="tim-icons icon-send"></i></button>
			</div>
		</div>
	</div>
	<div class="col-1"></div>
</div>
<pre class="d-none"><code>{{ var_dump($chats) }}</code></pre>
<style>
	.chat-bottom {
		background: #36364e;
		padding: 4px;
	}
</style>
@endsection