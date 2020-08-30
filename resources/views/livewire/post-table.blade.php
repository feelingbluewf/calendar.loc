<div id='external-events'>
	<div>
		<button type="submit" id="repair" class="hide">repair</button>
		<div>
			<select wire:model="from_group_name" wire:change="gotoPage(1)" id="from_group_name" class="select-control select margin-5">
				<option value='Все'>Все</option>
				@foreach ($groups as $group)
				{{ $group_name = $group->group_name . '(' . $group->quantity . ')' }}
				<option value="{{ $group->group_name }}" data-style="background-image: url({{ $group->avatar }});">{{ $group_name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<input wire:model="search" wire:keydown="gotoPage(1)" wire:click="$refresh" id="search" class="form-control margin-5" type="text" placeholder="Поиск...">
		</div>
		<div id='external-events-list'>
			@foreach ($posts as $post)
			<div class="fc-event info block" data-event="{{ $post->post_id }}">
				{{ StringService::strsize($post->text, 180) }}<br>
				@php $attachmets = explode(',', $post->attachments_urls); @endphp
				@foreach($attachmets as $attachment)
				@if(preg_match("/vk.com/", $attachment))
				<img class='img' src='/images/gif.png'>
				@else
				<img class='img' src='{{ $attachment }}'>
				@endif
				@endforeach
			</div>
			@endforeach
			@if(empty($post))
			@for ($i = 0; $i < $perPage; $i++)
			<div class="fc-event info block hide"></div>
			@endfor
			@endif
			<div id="pagination">
				{{ $posts->onEachSide(1)->links() }}
			</div>
		</div>
	</div>
</div>










