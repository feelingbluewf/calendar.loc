document.addEventListener('DOMContentLoaded', function() {
object_id = '';
$(document).on("click", ".fc-time-grid-event", function() {
	object_id = $('#object_id_calendar').val();
	//object_id = document.getElementById('object_id_calendar').value;
});
	$(".upload").upload({
      action: "../php/handler.php",
      label: '',
      maxQueue: '1',
      postData: {
          id: object_id
      },
    }).on("start", Start).on("filestart", fileStart).on("fileprogress", fileProgress).on("filecomplete", fileComplete).on("fileerror", fileError);

function Start (e, files) {
  $("#res").text('');
  console.log('Start');
  for(var i = 0; i < files.length; i++) {
    var html ='<li class=loading-str data-index="' + files[i].index + '"><span class="file">' + files[i].name + '</span><progress value="0" max="100"></progress><span class="loading"></span></li>';
    $("#res").append(html);
  }
}

function fileStart (e, file) {

  console.log('File Start');
  $("#res").find('li[data-index='+file.index+']').find('.loading').text('0%');

}

function fileProgress (e, file, percent) {
  console.log('File Progress');
  $("#res")
  .find('li[data-index='+file.index+']')
  .find('progress').attr('value', percent)
  .next().text(percent + '%');
}

function fileComplete (e, file, response) {
  if(response == '' || response.toLowerCase() == 'error') {
    $("#res").find('li[data-index='+file.index+']').addClass('upload_error').find('.loading').text('Ошибка загрузки');
  }
  else{
    $("#res").find('li[data-index='+file.index+']').remove();
    Request();
  }
}

function fileError (e, file) {
  $("#res").find('li[data-index='+file.index+']').addClass('upload_error').find('.loading').text('Файл не поддерживается');
}
});
