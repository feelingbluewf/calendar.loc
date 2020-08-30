$(document).ready(function() {
	let now = moment().format('Y-MM-DD HH:mm:ss');
  $.ajaxSetup({
    headers: {

      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }
  })

  setTimeout(function() {
    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events-list');
    new Draggable(containerEl, {
     itemSelector: '.fc-event',
     eventData: function(eventEl) {
      return {
       title: eventEl.innerText.trim(),
       post_id: $(eventEl).attr('data-event'),
     } 
   }
 });
    var calendarEl = document.getElementById('calendar');
    window.calendar = new Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      customButtons: {
        addEvent: {
          icon: 'plus',
          click: function() {
            viewCreateDialog();
          },
        }
      },
      header: {
        left: 'prev,next today addEvent',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      events: [
      {
        start: '2014-11-10T10:00:00',
        end: moment().add(29 - moment().minute() % 30, "minutes").format("Y-MM-DD HH:mm:ss"),
        rendering: 'background',
        overlap: false
      }
      ],
      eventSources: [
      events,
      ],
      locale: 'ru',
  eventTimeFormat: { // like '14:30:00'
  hour: '2-digit',
  minute: '2-digit',
  meridiem: false
},
editable: true,
allDaySlot:false,
showNonCurrentDates: false,
firstDay: 1,
dragScroll: false,
slotEventOverlap: false,
defaultView: 'timeGridWeek',
droppable: true,
  // new event dragging
  eventDrop: function(info) {
  	let now = moment().format('Y-MM-DD HH:mm:ss');
  	let eventStart = moment(info.event.start).format('Y-MM-DD HH:mm:ss');
    let tz = moment.tz.guess(true);
    if(now < eventStart){
      updateEvent(info.event, tz);
      setTimeout(function(){
       calendar.refetchEvents();
     }, 250);
    }
    else{
      Swal.fire({
       position: 'center',
       icon: 'error',
       title: 'Эта дата уже истекла',
       showConfirmButton: true,
       timer: 1700
     })
      calendar.refetchEvents();
    }
  },
  // new event creating
  eventReceive: function(info) {
  	let now = moment().format('Y-MM-DD HH:mm:ss');
  	let eventStart = moment(info.event.start).format('Y-MM-DD HH:mm:ss');
  	if(now < eventStart){
  		let tz = moment.tz.guess(true);
  		let post_id = $(info.draggedEl).attr('data-event');
  		let group_id = $('#group_id').val();
  		createEvent(info.event, post_id, tz, group_id);
  		calendar.refetchEvents();
  		info.event.remove();
  		setTimeout(function(){
  			calendar.refetchEvents();
  		}, 250);
  	}
  	else{
  		Swal.fire({
  			position: 'center',
  			icon: 'error',
  			title: 'Эта дата уже истекла',
  			showConfirmButton: true,
  			timer: 1700
  		})
  		info.event.remove();
  	}
  },
  eventRender: function(info){
  	let classes = info.event.classNames;
  	let el = info.el;
  	let view = info.view.type;
    let description = info.event.extendedProps.description;
    icon(classes['2'], el, view);
    tooltip(description, classes['2'], el);
  },
  eventClick: function(arg) {
  	window.arg = arg;
  	window.delete_object = arg.event;
  	viewPost(arg);
  }
});

    calendar.render();

  }, 200);
  var events = function(fetchInfo, successCallback, failureCallback) {
    var start = moment(fetchInfo.start).format('Y-MM-DD HH:mm:ss');
    var end = moment(fetchInfo.end).format('Y-MM-DD HH:mm:ss'); 
    var group_id = $('#group_id').val();
    $.ajax({
     url: "/calendar/getEvents",
     type: "POST",
     data: {'group_id': group_id, 'start':start, 'end':end},
     success: function (response) {
      if(response == ']'){
       response = '[{ }]';
     }
     successCallback(JSON.parse(response));
     console.log(JSON.parse(response));
   },
   error: function(response) {
    failureCallback(response); 
  }
});
  }

  // Optinons

  $('#calendar-events-dialog, #external-events-dialog, #addEvent-dialog').dialog({
  	autoOpen: false,
  	show: {
  		effect: 'drop',
  		duration: 500
  	},
  	hide: {
  		effect: 'clip',
  		duration: 500
  	}
  });

  lightbox.option({
  	'resizeDuration': 200,
  	'wrapAround': true
  });

  // jQuery 

  $('#every').change(function() {
  	if ($('#every').is(':checked')) {
  		$('#every_select').prop('disabled', false);
  	}
  	else{
  		$('#every_select').prop('disabled', true);
  	}
  });

  $('.info').click(function (){
  	var post_id = $(this).attr('data-event');
  	$.ajax({
  		type:"get",
  		url:"/calendar/viewSelectPostData",
  		data:{"post_id":post_id},
  		success:function(data){
        console.log(data.text);
        var post_text = $('#post_fulltext_external');
        var vk_link = $('#vk_link');
        if(data.fulltext == 'Нет текста'){
          data.fulltext = '';
          post_text.html(data.fulltext);
        } 
        else{
          post_text.html(data.fulltext + '<br>');
        }
        var arr_attachments = data.pictures.split(",");
        $.each(arr_attachments, function(index, item) {
          if(item != ''){
            let pic = "<div class='item'><a href='"+ item +"' data-lightbox='image-1'><div style='background: url("+ item +") #000 no-repeat center;' class='bg-img'></div></a></div>";
            post_text.append(pic);
          }
        })
        post_link = "<span><a href='" + data.post_link + "' target='_blank'>Ссылка на пост в группе</a></span>"
        vk_link.html(post_link);
      },
      error: function() {
        alert('error');
      }
    });
  	$('#external-events-dialog').dialog('open');
  });

  $('#update').on('click', function (){
   $('body').addClass("loading");
   var object_id = arg.event.id;
   $.ajax({
    type: "PUT",
    url: "/calendar/updatePostData",
    data: createOrUpdate(object_id),
    complete: function() {
      $('body').removeClass("loading");
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Объект успешно сохранен',
        showConfirmButton: false,
        timer: 700
      })
      setTimeout(function(){
        $('button.ui-dialog-titlebar-close').click();
        calendar.refetchEvents();
      }, 700);
    },
    error: function() {
      $('body').removeClass("loading");
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Произошла ошибка',
        showConfirmButton: false,
        timer: 2000
      })
    }
  });

   return false;

 });

  $('#create').on('click', function () {
   $('body').addClass("loading");
   var group_id = $('#group_id').val();
   $.ajax({
    type: "POST",
    url: "/calendar/createEvent",
    data: createOrUpdate('0', group_id),
    complete: function() {
      $('body').removeClass("loading");
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Объект успешно сохранен',
        showConfirmButton: false,
        timer: 700
      })
      setTimeout(function(){
        $('button.ui-dialog-titlebar-close').click();
        calendar.refetchEvents();
      }, 700);
    },
    error: function() {
      $('body').removeClass("loading");
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Произошла ошибка',
        showConfirmButton: false,
        timer: 2000
      })
    }
  });

   return false;

 });

  $('#delete').on('click', function (){
  	$('body').addClass("loading");
  	let object_id = arg.event.id;
  	$.ajax({
  		type:"DELETE",
  		url:"/calendar/deleteEvent",
  		data:{"object_id":object_id},
  		complete: function() {
  			$('body').removeClass("loading");
  			Swal.fire({
  				position: 'center',
  				icon: 'success',
  				title: 'Объект успешно удален',
  				showConfirmButton: false,
  				timer: 500
  			})
  			setTimeout(function(){
  				$('button.ui-dialog-titlebar-close').click();
  			}, 700);
  			setTimeout(function(){
  				calendar.refetchEvents(); 
  				delete_object.remove();
  			}, 1350);
  		},
  		error: function(){
  			$('body').removeClass("loading");
  			Swal.fire({
  				position: 'center',
  				icon: 'error',
  				title: 'Произошла ошибка',
  				showConfirmButton: false,
  				timer: 2000
  			})
  		}
  	});
  	return false;
  });


  $('button.ui-dialog-titlebar-close').on('click', function () {
    $('.fs-upload-target').html('');
    $('#post_fulltext_calendar').html('');
    $('#alert').html('');
    $('#datetimepicker').datetimepicker('clear');
    $('button.fc-addEvent-button').prop('disabled', false);
    $('#link').prop('checked', false);
    $('#ad').prop('checked', false);
    $('#sign').prop('checked', false);
    $('#disable_comments').prop('checked', false);
    $('#every').prop('checked', false);
    $('#every_select').prop('disabled', false);
  });


// Image upload


$(".upload").upload({
	action: "/vkRequest/uploadImage",
	label: false,
	maxQueue: '1',
  maxConcurrent: '1',
  beforeSend: onBeforeSend
}).on("filestart", fileStart).on("fileprogress", fileProgress).on("filecomplete", fileComplete).on("fileerror", fileError);


function onBeforeSend(formdata) {
  formdata.append('token', vk_token);
  let items = $('div.item').filter(function() {
    return $(this).data('url') !== undefined;
  });
  if(10 - items.length <= 0){
    Swal.fire({
     icon: 'error',
     title: 'Ошибка!',
     text: 'Вы прикрепили слишком много файлов в пост.',
   });
    return false
  }
  return formdata;
}

function fileStart (e, file) {
  var html ='<li class=loading-str data-index="' + file.index + '"><span class="file">' + file.name + '</span><progress value="0" max="100"></progress><span class="loading"></span></li>';
  $("#res").append(html);
  $("#res").find('li[data-index='+file.index+']').find('.loading').text('0%');
}


function fileProgress (e, file, percent) {
	$("#res")
	.find('li[data-index='+file.index+']')
	.find('progress').attr('value', percent)
	.next().text(percent + '%');
}

function fileComplete (e, file, response) {
	if(response == '' || response.toLowerCase() == 'error') {
		$("#res").find('li[data-index='+file.index+']').addClass('upload_error').find('.loading').text($.parseJSON(response).error);
	}
	else{
    addPicture($.parseJSON(response).url, $.parseJSON(response).attachment, $.parseJSON(response).object_id, $.parseJSON(response).pic_counter);
    $("#res").find('li[data-index='+file.index+']').remove();

  }
}

function fileError (e, file) {
	$("#res").find('li[data-index='+file.index+']').remove();
}

});

