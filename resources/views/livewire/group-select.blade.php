<?php

$session_group_id = session()->get('group_id', '');

?>
<div class="sticky-top" id="app">
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
		<div class="container">
			<a class="navbar-brand" href="{{ route('welcome') }}">
				Calendar
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<!-- Left Side Of Navbar -->
				<ul class="navbar-nav mr-auto">
				</ul>

				<!-- livewire -->
				
				<div wire:ignore>
					<select class="select_group" name="groups">
						@foreach ($groups as $group)
						@if($group->hide === 0)
						@if(!empty($session_group_id) && $group->group_id == end($session_group_id))
						<option value="{{ $group->group_id }}" data-style="background-image: url({{ $group->avatar }});" selected="selected">{{ $group->group_name }}</option>
						@else
						<option value="{{ $group->group_id }}" data-style="background-image: url({{ $group->avatar }});">{{ $group->group_name }}</option>
						@endif
						@endif
						@endforeach
					</select>
					
				</div>
				<input id="group_id" hidden value="{{ $group_id }}">


				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto">
					<!-- Authentication Links -->
					@guest
					<li class="nav-item">
						<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
					</li>
					@if (Route::has('register'))
					<li class="nav-item">
						<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
					</li>
					@endif
					@else
					<li class="nav-item dropdown">

						@if(Auth::user()->avatar !== NULL)

						<img src="{{ Auth::user()->avatar }}" width="40" height="40">

						@else

						<img src="https://sun9-58.userapi.com/c857336/v857336273/bb0bd/2FhMZ8NKpfw.jpg" width="40" height="40">

						@endif

						@if (Auth::user()->name == 'Guest')

						<a style="display: inline-block;" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
							{{ Auth::user()->email }} <span class="caret"></span>
						</a>

						@else

						<a style="display: inline-block;" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
							{{ Auth::user()->name }} <span class="caret"></span>
						</a>

						@endif
						
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="{{ route('controlpanel') }}">
								{{ __('Панель управления') }}
							</a>
							<a class="dropdown-item" href="{{ route('profile') }}">
								{{ __('Профиль') }}
							</a>
							<a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
							{{ __('Выйти') }}
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</div>
				</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>
</div>

<?php
$user_id = Auth::user()->id;
$_SESSION['user_id'] = $user_id;
session()->push('group_id', $group_id);
?>	
<script type="text/javascript">
	$(document).ready(function(){
		$(function () {
			$.widget( "custom.iconselectmenu", $.ui.selectmenu, {
				_renderItem: function( ul, item ) {
					var li = $( "<li>" ),
					wrapper = $( "<div>", { text: item.label } );

					if ( item.disabled ) {
						li.addClass( "ui-state-disabled" );
					}

					$( "<span>", {
						style: item.element.attr( "data-style" ),
						"class": "ui-icon " + item.element.attr( "data-class" )
					}).appendTo( wrapper );

					return li.append( wrapper ).appendTo( ul );
				}
			});

			$(".select_group")
			.iconselectmenu({
				change: function( e, ui ) {
					@this.set('select', e.target.value);
					setTimeout(function() {
						$('#search').click();
						$('#repair').click();
						calendar.destroy();
						calendar.render();
						calendar.refetchEvents();
					}, 650);

				}
			})
			.iconselectmenu( "menuWidget")
			.addClass( "ui-menu-icons customicons" );
		});

	});
</script>