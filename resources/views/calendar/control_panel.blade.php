<title>Панель управления</title>
<?php session_start(); ?>
<link rel="stylesheet" type="text/css" href="{{ url('/css/panel.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.5.3/dist/sweetalert2.all.min.js"></script>
@extends('layouts.app')

@section('content')
@livewireAssets
<div class="container">
  <div class="card">
    <div class="card-header">Группы</div>
    <form>
      <div class="container">
        <div class="row col-md-6 col-md-offset-2 custyle">
          <table id="table" class="table table-striped custab">
            <thead>
              <tr>
                <th>Название группы</th>
                <th>Список групп</th>
                <th>Кол-во постов в ленте</th>
                <th>Скрыть</th>
              </tr>
            </thead>
            @foreach ($groups as $group)
            <tr>
              <td><a href="controlpanel/{{ $group->group_id }}">{{ $group->group_name }}</a></td>
              <td>
                @php $quantity = 0; @endphp
                @foreach ($group->groupList as $subGroup)
                @php $quantity = $quantity + $subGroup->quantity; @endphp
                @if($loop->last)
                {{ $subGroup->group_name }}
                @else 
                {{ $subGroup->group_name }} |
                @endif
                @endforeach
              </td>
              <td>
                @if(!empty($quantity))
                {{ $quantity }}
                @endif
              </td>
              @if($group->hide === 0)
              <td><input class="checkbox" type="checkbox" data-id='{{ $group->unique_id }}'></td>
              @else
              <td><input class="checkbox" type="checkbox" data-id='{{ $group->unique_id }}' checked></td>
              @endif
            </tr>
            @endforeach
          </table>
        </div>
      </div>
      <button type="submit" id="update" class="btn btn-primary" style="margin-left: 15;">Обновить группы</button>
    </form>
  </div>
</div>
@endsection
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    $.ajaxSetup({
      headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }
    });
    
    $('#update').on('click', function () {

      $.ajax({
        type:"POST",
        url:"/vkRequest/getUserGroups",
        success: function(groups) {

          if(groups.length == 0) {

            Swal.fire({
              position: 'center',
              icon: 'warning',
              title: 'Изменений нет',
              showConfirmButton: false,
              timer: 1200
            });

          }
          else {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Группы успешно обновлены',
              showConfirmButton: false,
              timer: 1200
            });

            var table = $('#table');
            for (i = 0; i < groups.group_id.length; i++) {
              let group = "<tr><td><a href='controlpanel/" + groups.group_id[i] + "' target=_blank>" + groups.group_name[i] + "</td><td></td><td></td><td><input class='checkbox' type='checkbox' data-id='" + groups.unique_id[i] + "'></td></tr>";
              table.append(group);
            }
          }
        },
        error: function(data) {

          Swal.fire({
            position: 'center',
            icon: 'error',
            title: data.responseJSON.message,
            showConfirmButton: false,
            timer: 1200
          });
        }
      });

      return false;

    });

    $('.checkbox').on('click', function () {

      var unique_id = $(this).attr('data-id');
      if($(this).is(":checked")){
        var hide = '1';
      }
      else{
        var hide = '0';
      }

      $.ajax({
        type:"PUT",
        url:"/controlpanel/hideGroup",
        data:{"unique_id":unique_id, "hide":hide},
        success: function(data) {
        },
        error: function() {
          alert('error');
        }
      });

    });

  });
</script>
