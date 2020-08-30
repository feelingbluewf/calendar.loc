@php
session_start();
$user_id = Auth::user()->id;
$_SESSION['user_id'] = $user_id;
@endphp
<html>
<head>
	<link rel="stylesheet" type="text/css" href="{{ url('/css/generic.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ url('/css/site.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('/css/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('/css/mdb.min.css') }}">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.min.css">
	<link rel="stylesheet" href="{{ url('/css/lightbox.css') }}">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
	<link href="{{ url('/packages/core/main.css') }}" rel='stylesheet' />
	<link href="{{ url('/packages/core/style.css') }}" rel='stylesheet' />
	<link href="{{ url('/packages/daygrid/main.css') }}" rel='stylesheet' />
	<link href="{{ url('/packages/timegrid/main.css') }}" rel='stylesheet' />
	<link href="{{ url('/packages/list/main.css') }}" rel='stylesheet' />
	<link rel="stylesheet" href="{{ url('/css/style.css') }}" rel='stylesheet' />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<title>Календарь</title>

	@livewireAssets
	
</head>
<body>
	@livewire('group-select')
	<div class="modal"></div>
	<div class="container-fluid">
		<div class="pt-4">
			<div class="row text-center">
				<div class="col-2">@livewire('post-table')</div>
				<div class="col-10" id='calendar'></div>
			</div>	
		</div>
	</div>
	<div id="external-events-dialog" class="hide fixed">
		<form>
			<div class="form-group">
				<label>Весь текст поста:</label>
				<div class="editor" id="post_fulltext_external"></div>
				<div class="vk_link" id="vk_link"></div>
			</div>
		</form>
	</div>
	<div id="calendar-events-dialog" class="hide fixed">
		<form id="form_editor">
			<div id="alert"></div>
			<div class="form-group">
				<label>Весь текст поста:</label>
				<div class="editor" id="post_fulltext_calendar" contenteditable></div>
				<div class="editor_pic upload" id="post_pictures_calendar"><div class="fs-upload-target"></div></div>
				<div id="res"></div>
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="link">
					<label class="custom-control-label" for="link">Указать источник</label>
				</div>
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="ad">
					<label class="custom-control-label" for="ad">Опубликовать пост как рекламу</label>
				</div>
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="sign">
					<label class="custom-control-label" for="sign">Поставить подпись</label>
				</div>
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="disable_comments">
					<label class="custom-control-label" for="disable_comments">Выключить комментарии</label>
				</div>
				<div class="custom-control custom-checkbox margin">
					<input type="checkbox" class="custom-control-input" id="every">
					<label class="custom-control-label" for="every">Постить</label>
					<select class="select" id="every_select" disabled>
						<option value="4">Каждый год</option>
						<option value="3">Каждый месяц</option>
						<option value="2">Каждую неделю</option>
						<option value="1">Каждый день</option>
					</select>
				</div>
				<div id="date" class="custom-checkbox margin">
					<label>Дата публикации:</label>
					<div class="input-group date" id="datetimepicker" data-target-input="nearest">
						<input type="text" id="start" class="form-control datetimepicker-input" data-target="#datetimepicker" data-toggle="datetimepicker"/>
						<div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
						</div>
					</div>
				</div>
				<div class="counter_wrap">
					<div class="counter" id="counter"></div>
				</div>
				<button type="submit" id="create" class="btn btn-success">Создать</button>
				<button type="submit" id="update" class="btn btn-success">Сохранить</button>
				<button type="submit" id="delete" class="btn btn-danger">Удалить</button>
			</div>
		</form>
	</div>
</body>
</html>
<script src="{{ url('/js/app.js') }}"></script>
<script src="{{ url('/js/core.js') }}"></script>
<script src="{{ url('/js/upload.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('/js/lightbox.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://momentjs.com/downloads/moment-timezone-with-data-10-year-range.js"></script>
<script src="{{ url('/packages/core/main.js') }}"></script>
<script src="{{ url('/packages/interaction/main.js') }}"></script>
<script src="{{ url('/packages/daygrid/main.js') }}"></script>
<script src="{{ url('/packages/timegrid/main.js') }}"></script>
<script src="{{ url('/packages/list/main.js') }}"></script>
<script src="{{ url('/js/config.js') }}"></script>
<script src="{{ url('/js/functions.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/89c4207f56.js" crossorigin="anonymous"></script>
<script type="text/javascript">
	var user_id = <?php echo Auth::user()->id ?> ;
	var vk_token = "<?php echo Auth::user()->vk_token ?>" ;
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("mouseenter", ".item" , function() {
			$('.fs-upload-target').removeClass('fs-upload-target');
			$('#post_pictures_calendar div:first').addClass('event-block');
		});
		$(document).on("mouseleave", ".item" , function() {
			$('#post_pictures_calendar div:first').addClass('fs-upload-target');
		});
		$(document).on("click", ".ui-button" , function() {
			if($('.fs-upload-target').length == 0){
				$('#post_pictures_calendar').append('<div class="fs-upload-target"></div>');
			}
		});
		$(document).on("click", ".fc-time-grid-event" , function() {
			$('.fs-upload-target').removeClass('event-block');
			$('#post_pictures_calendar div:first').addClass('fs-upload-target');
		});
	});
</script>
