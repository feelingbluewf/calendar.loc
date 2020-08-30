<?php
session_start();
session()->push('parent_group_id', $group_id);
?>
<title>Панель управления</title>
<link rel="stylesheet" type="text/css" href="{{ url('/css/panel.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://kit.fontawesome.com/89c4207f56.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.all.min.js"></script>
@extends('layouts.app')


@section('content')
<div class="modal"></div>
@livewireAssets
<div class="container">
	<h1>Группа: {{ $group_name }} </h1>
	<p>ID группы: {{ $group_id }}<p>
		<form>
			<label for="post_quantity">Введите кол-во постов:</label>
			<div class="row" style="margin: 0;">
				<input type="number" class="form-control-input col-sm-1" value="100" id="post_quantity" aria-describedby="post_quantity">
			</div>
			<div class="form-group">
				<label for="group_name">Введите ID группы:</label>
				<input type="text" class="form-control-input" id="group_name" aria-describedby="group_name">
				<button type="submit" id="getGroup_name" style="position: relative; top: -1px;" class="btn btn-success">Сохранить</button>
			</div>
		</form>
	</div>

	@livewire('group-panel')

	@endsection

	<script type="text/javascript">
		var parent_group_name = '<?php echo $group_name ?>';
		var parent_group_id = <?php echo $group_id ?>;
		$(document).ready(function(){

			$.ajaxSetup({
				headers: {

					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

				}
			});

			$(document).on("click", "#getGroup_name" , function() {

				var group_name = $('#group_name').val();
				var post_quantity = $('#post_quantity').val();
				$('#group_name').val('');
				$('#post_quantity').val('100');

				$.ajax({
					type: 'POST',
					url: '/vkRequest/getSourceGroupAndPostsQuantity',
					dataType: 'json',
					data:{"group_name":group_name, "post_quantity":post_quantity, "parent_group_id":parent_group_id},
					success: function(data) {
						let offset = data.offset;
						let quantityBefore = data.offset;
						let postsQuantity = data.postsQuantity;
						let sourceGroup = data.sourceGroup;
						let sourceGroupName = sourceGroup[0]['name'];

						var counter = Math.ceil(postsQuantity / 100);

						var i = 0;                  

						function myLoop() {         
							setTimeout(function() { 

								$.ajax({
									type:"POST",
									url:"/vkRequest/parsePosts",
									dataType: "json",
									data:{"sourceGroup":sourceGroup, "offset":offset, "parent_group_id":parent_group_id, "parent_group_name": parent_group_name, "itterationNum":i},
									success:function(data) {

										if($("tbody#" + data.group_id + "").length){

											$("tbody#" + data.group_id + "").find("td.quantity").html(data.quantity);

											if(data.itterationNum == counter - 1) {

												var quantityAfter= $("tbody#" + data.group_id + "").find("td.quantity").html();
												
												var quantityAdded = Math.abs(quantityBefore - quantityAfter);

												Swal.fire({
													position: 'center',
													icon: 'success',
													title: 'Группа ' + sourceGroupName + ' успешно обновлена, новых постов: ' + quantityAdded + '',
													showConfirmButton: false,
													timer: 2000
												});

											}

										}
										else {

											var table = $('#table');
											let group = "<tbody id='" + data.group_id + "'><tr><td><a href='https://vk.com/" + data.group_screen_name + "' target=_blank>" + data.group_name + "</td><td class='quantity'>" + data.quantity + "</td><td><span class='label label-success'>Активный</span></td><td><i data-group_id='" + data.group_id + "' class='fas fa-play opacity'></i> <i data-group_id='" + data.group_id + "' class='fas fa-pause margin-left'></i> <i data-group_id='" + data.group_id + "' data-unique_id='" + data.unique_id + "' data-group_name='" + data.group_name + "' data-quantity='" + data.quantity + "' class='fas fa-trash margin-left'> </i></td></tr></tbody>";
											table.append(group);
										}

										if(data.itterationNum == counter - 1) {

											Swal.fire({
												position: 'center',
												icon: 'success',
												title: 'Группа ' + sourceGroupName + ' успешно обновлена, новых постов: ' + data.quantity + '',
												showConfirmButton: false,
												timer: 2000
											});

										}

									},
									error: function(data) {

										$('body').removeClass("loading");

										Swal.fire({
											position: 'center',
											icon: 'error',
											title: data.responseJSON.message,
											showConfirmButton: true
										});

										return;

									}
								});

								offset = offset + 100;

								i++;                    
								if (i < counter) {           
									myLoop();             
								}

							}, 1650)
						}	

						myLoop();
						
					},
					error: function(data) {
						Swal.fire({
							position: 'center',
							icon: 'error',
							title: data.responseJSON.message,
							showConfirmButton: true
						});
					}
				});

				return false;

			});

			$(document).on("click", ".fa-play" , function() {
				var group_id = $(this).attr('data-group_id');
				$.ajax({
					type:"PUT",
					url:"/parsepanel/startSourceParse",
					data:{"group_id":group_id, "parent_group_id":parent_group_id},
					success:function(data) {
					}
				});
				$(this).addClass('opacity');
				$("" + '#' + group_id + "").find('i.fa-pause').removeClass('opacity');
				$("" + '#' + group_id + "").find('span').removeClass('label-warning').addClass('label-success').html('Активный');
			});

			$(document).on("click", ".fa-pause" , function() {
				Swal.fire({
					title: 'Вы действительно хотите приостановить сбор постов?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Да',
					cancelButtonText: 'Нет'
				}).then((result) => {
					if (result.value) {
						var group_id = $(this).attr('data-group_id');
						$.ajax({
							type:"PUT",
							url:"/parsepanel/stopSourceParse",
							data:{"group_id":group_id, "parent_group_id":parent_group_id},
							success:function(data) {
								if(data.error != 'OK'){

									$(this).addClass('opacity');
									$("" + '#' + group_id + "").find('i.fa-play').removeClass('opacity');
									$("" + '#' + group_id + "").find('span').removeClass('label-success').addClass('label-error').html(data.error);

								}
								else {

									$(this).addClass('opacity');
									$("" + '#' + group_id + "").find('i.fa-play').removeClass('opacity');
									$("" + '#' + group_id + "").find('span').removeClass('label-success').addClass('label-warning').html('Приостановлено');

								}
							}
						});
					}
				})
			});

			$(document).on("click", ".fa-trash" , function() {
				Swal.fire({
					title: 'Вы действительно хотите удалить группу из списка?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Да',
					cancelButtonText: 'Нет',
          		/*input: 'checkbox',
          		inputPlaceholder: 'Удалить все посты этой группы из календаря'*/
          	}).then((result) => {
          		if (result.value) {
          			var group_id = $(this).attr('data-group_id');
          			var unique_id = $(this).attr('data-unique_id');
          			var group_name = $(this).attr('data-group_name') + ', ';
          			var quantity = $(this).attr('data-quantity');
          			$.ajax({
          				type:"DELETE",
          				url:"/parsepanel/deleteSource",
          				data:{"group_id":group_id, "unique_id":unique_id, "group_name":group_name, "parent_group_id":parent_group_id, "quantity":quantity},
          				success:function(data) {
          				}
          			});
          			$("" + '#' + group_id + "").remove();
          		}
          	})
          });	
		});

	</script>
