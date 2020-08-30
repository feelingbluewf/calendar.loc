<style type="text/css">
  .bg-red {
    background-color: #d9534f;
  }
</style>
<div class="container">
  <table id="table" class="table table-striped custab">
    <thead>
      <tr>
        <th>Название группы</th>
        <th>Кол-во постов в ленте</th>
        <th>Статус</th>
        <th>Действие</th>
      </tr>
    </thead>
    @foreach ($groups as $group)
    <tbody id="{{ $group->group_id }}">
      <tr>
        <td><a href="https://vk.com/{{ $group->group_screen_name }}" target="_blank">{{ $group->group_name }}</a></td>
        <td class="quantity">{{ $group->quantity }}</td>
        <?php
        $status = $group->status;
        $error = $group->error;
        if($status == '1'){
          $status = 'Активный';
          $style_name = 'label-success';
          $opacity_play = 'opacity';
          $opacity_pause = '';
        }
        elseif($error != 'OK' && $status == '0'){
          $status = $error;
          $style_name = 'bg-red';
          $opacity_play = '';
          $opacity_pause = 'opacity';
        }
        else {
          $status = 'Приостановлено';
          $style_name = 'label-warning';
          $opacity_play = '';
          $opacity_pause = 'opacity';
        }
        ?>
        <td><span class="label {{ $style_name }}">{{ $status }}</span></td>
        <td>
          <i data-group_id='{{ $group->group_id }}' title="Возобновить" class="fas fa-play {{ $opacity_play }}"></i>
          <i data-group_id='{{ $group->group_id }}' title="Остановить" class="fas fa-pause margin-left {{ $opacity_pause }}"></i>
          <i data-group_id='{{ $group->group_id }}' data-unique_id='{{ $group->unique_id }}' data-group_name='{{ $group->group_name }}' data-quantity='{{ $group->quantity }}' title="Удалить" class="fas fa-trash margin-left"></i>
        </td>
      </tr>
    </tbody>
    @endforeach
  </table>
</div>

