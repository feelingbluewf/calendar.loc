function createEvent(event, post_id, tz, group_id) {
  let event_start = event.start;
  let formatted_event_start = moment(event.start).format('Y-MM-DD HH:mm:ss');
  $.ajax({
    type:"POST",
    url:"/calendar/createEvent",
    data:{"id":post_id,
    "title":event.title, 
    "start":formatted_event_start,
    "fulltext":event.extendedProps.fulltext, 
    "attachments":event.extendedProps.attachments,
    "user_id":user_id,
    "attachments_others":event.extendedProps.attachments_others,
    "attachments_urls":event.extendedProps.attachments_urls,
    "timezone":tz, 
    "group_id":group_id
  },
  traditional:true,
  success:function(msg){
  },
  error:function(msg){
    console.log(msg);
    alert('Ошибка');
  }
});
}

function updateEvent(event, tz) {
  let event_start = event.start;
  let formatted_event_start = moment(event.start).format('Y-MM-DD HH:mm:ss');   
  $.ajax({
    type:"PUT",
    url:"/calendar/updateEvent",
    data:{"id":event.id, 
    "start":formatted_event_start,
    "timezone":tz
  },
  traditional:true,
  success:function(msg){
  },
  error:function(msg){
    console.log(msg);
    alert('Ошибка');
  }
});
}

function viewPost(arg) {
  window.post_text = $('#post_fulltext_calendar');
  window.object_id = arg.event.id;
  window.counter = $('#counter');
  $.ajax({
    type:"GET",
    url:"/calendar/viewPostData",
    data:{"object_id":object_id},
    dataType: "json",
    success:function(data){
      window.post_pictures = $('#post_pictures_calendar').find('.fs-upload-target');
      let start = data.start;
      var arr_attachments_urls = data.attachments_urls;
      var arr_attachments_others = data.attachments_others;
      var arr_attachments = data.attachments;
      var link = data.link;
      var ad = data.ad;
      var sign = data.sign;
      var disable_comments = data.disable_comments;
      var every = data.every;
      var error = data.error;
      var options = { "1": true, "0": false };
      var time_interval = { day: '1', week: '2', month: '3', year: '4', none: '1'};
      var every_option = [{ day: true, week: true, month: true, year: true, none: false}, {day: false, week: false, month: false, year: false, none: true}];
      $('#alert').html('');
      if(error == 'OK' && error != '') {
        $('#alert').html('<div class="alert alert-success" role="alert"><a href="#" class="alert-link">Пост успешно опубликован!</a></div>');
      }
      if(error != 'OK' && error != '') {
        $('#alert').html('<div  class="alert alert-danger" role="alert"><a href="#" class="alert-link">' + error + '!</a></div>')
      }
      $('#link').prop('checked', options[link]);
      $('#ad').prop('checked', options[ad]);
      $('#sign').prop('checked', options[sign]);
      $('#disable_comments').prop('checked', options[disable_comments]);
      $('#every').prop('checked', every_option[0][every]);
      $('#every_select').prop('disabled', every_option[1][every]);
      $("#every_select option[value=" + time_interval[every] + "]").prop('selected', true);
      if(arr_attachments_others == ''){
        arr_sum = (arr_attachments.slice(0, -1) + arr_attachments_others).split(',');
      }else{
        arr_sum = (arr_attachments + arr_attachments_others.slice(0, -1)).split(',');
      }
      if(arr_attachments_others == '' && arr_attachments == ''){
        arr_sum = '';
      }
      window.pic_counter = 10 - arr_sum.length;
      counter.html("Можно добавить еще картинок:" + (pic_counter));
      arr = [];
      for (let i = 0; i < arr_attachments_urls.split(",").length; i++) {
        arr.push({ vk: arr_attachments.split(",")[i], url: arr_attachments_urls.split(",")[i] });
      }
      if(data.text == 'Нет текста'){
        data.text = '';
      }
      post_text.html(data.text + '<br>');
      post_pictures.html('');
      arr.forEach(makeImage);
      function makeImage(item){
        if(item.url !== '' && item.vk !== ''){
          let pic = "<div class='item' data-url='"+ item.url +",' data-attachment='"+ item.vk +",' data-object_id='"+ object_id +"'><span class='close_pic' onclick='attachementDelete(this)'>&times;</span><a href='"+ item.url +"' data-lightbox='image-1'><div style='background: url("+ item.url +") #000 no-repeat center;' class='bg-img'></div></a></div>";
          post_pictures.append(pic);
          
        }
      }
      
      $('#datetimepicker').datetimepicker('destroy');

      if(start < moment().format('Y-MM-DD HH:mm:ss')) {

        $('#datetimepicker').datetimepicker({
          date: start
        });

        $('#datetimepicker').datetimepicker('disable');

      }
      else {
        $('#datetimepicker').datetimepicker({
          format: 'Y-MM-DD HH:mm',
          minDate: moment().format('LLLL'),
          useCurrent: false,
          locale: 'ru',
          autoclose: true,
          date: start,
          locale: 'ru'
        });
        $('#datetimepicker').datetimepicker('enable');
      }
    }
  });
  $('button.fc-addEvent-button').prop('disabled', true);
  $('#create').addClass('hide');
  $('#update').removeClass('hide');
  $('#delete').removeClass('hide');
  $('#calendar-events-dialog').dialog('open');
};

function viewCreateDialog() {
  window.post_pictures = $('#post_pictures_calendar').find('.fs-upload-target');
  window.counter = $('#counter');
  counter.html("Можно добавить еще картинок:" + '10');
  $('#calendar-events-dialog').dialog('open');
  $('#datetimepicker').datetimepicker('destroy');
  $('#datetimepicker').datetimepicker({
    format: 'Y-MM-DD HH:mm',
    minDate: moment().format('LLLL'),
    useCurrent: true,
    locale: 'ru',
    autoclose: true
  });
  $('#datetimepicker').datetimepicker('enable');
  $('#create').removeClass('hide');
  $('#update').addClass('hide');
  $('#delete').addClass('hide');

}

function createOrUpdate(object_id, group_id) {
  var text = $('#post_fulltext_calendar').text();
  var items = $('div.item').filter(function() {
    return $(this).data('url') !== undefined;
  });
  var attachments = [];
  $(items).each(function() {
    attachments.push({ url: $(this).data('url'), attachment: $(this).data('attachment')});
  })
  var link = '0';
  var ad = '0';
  var sign = '0';
  var disable_comments = '0';
  var every = 'none';
  var tz = moment.tz.guess(true);
  var start = $('#start').val();
  let formatted_event_start = moment(start).format('Y-MM-DD HH:mm:ss');
  var rename = { 'Каждый день': 'day', 'Каждую неделю': 'week', 'Каждый месяц': 'month', 'Каждый год': 'year'};
  if ($('#link').is(':checked')) {
    var link = '1';
  } 
  if ($('#ad').is(':checked')) {
    var ad = '1';
  } 
  if ($('#sign').is(':checked')) {
    var sign = '1';
  } 
  if ($('#disable_comments').is(':checked')) {
    var disable_comments = '1';
  } 
  if ($('#every').is(':checked')) {
    var every_text = $('#every_select option:selected').text();
    var every = rename[every_text];
  }

  if(object_id != '0') {
    var data = {"object_id":object_id, "attachments":attachments, "timezone":tz, "start":formatted_event_start, "text":text, "link":link, "ad":ad, "sign":sign, "disable_comments":disable_comments, "every":every};
  }
  else {

    var data = {"group_id":group_id, "attachments":attachments, "timezone":tz, "start":formatted_event_start, "text":text, "link":link, "ad":ad, "sign":sign, "disable_comments":disable_comments, "every":every};

  }

  return data;
  
}

function attachementDelete(item) {
  $(item).parent().remove();
  attachment = $(item).parent().data('attachment');
  url = $(item).parent().data('url');

  let items = $('div.item').filter(function() {
    return $(this).data('url') !== undefined;
  });

  counter.html("Можно добавить еще картинок:" + (10 - items.length));

  if(items.length == '0') {

    $('#post_pictures_calendar div:first').addClass('fs-upload-target');

  }
}


function icon(type, el, view){
  let icons = { 'waiting' : 'far fa-clock', 'success' : 'fas fa-check', 'fail' : 'fas fa-times' };
  if(view !== 'dayGridMonth'){
    $(el).find('div.fc-time').append("<span> <i class='" + icons[type] + "'></i></span>"); 
  }
  else{
    $(el).find('div.fc-content').prepend("<span> <i class='" + icons[type] + "'></i></span>"); 
  }
}

function tooltip(description, type, el) {

  if(type == 'fail') {

    $(el).attr('data-toggle', 'tooltip').attr('data-placement', 'top').attr('title', description);

    $(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });

  }

}

function round(date, duration, method) {
  return moment(Math[method]((+date) / (+duration)) * (+duration)); 
}

function addPicture(url, attachment, object_id, pic_counter) {
  const pic = "<div class='item' data-url='"+ url +"' data-attachment='"+ attachment +"' data-object_id='"+ object_id +"'><span class='close_pic' onclick='attachementDelete(this)'>&times;</span><a href='"+ url +"' data-lightbox='image-1'><div style='background: url("+ url +") #000 no-repeat center;' class='bg-img'></div></a></div>";
  post_pictures.append(pic);
  let items = $('div.item').filter(function() {
    return $(this).data('url') !== undefined;
  });
  counter.html("Можно добавить еще картинок:" + (10 - items.length));
}