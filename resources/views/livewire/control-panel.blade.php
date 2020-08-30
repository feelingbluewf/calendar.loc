<div class="container">
  <div class="row col-md-6 col-md-offset-2 custyle">
    <table class="table table-striped custab">
      <thead>
        <tr>
          <th>Название группы</th>
          <th>Список групп</th>
          <th>Кол-во постов в ленте</th>
        </tr>
      </thead>
      @foreach ($groups as $group)
      <tr>
        <td><a href="controlpanel/{{ $group->group_name }}/{{ $group->group_id }}">{{ $group->group_name }}</a></td>
        <?php 
        $group_list = $group->group_list;
        if(!empty($group_list)){
          $group_list = substr($group->group_list, 0, -2);
        }
        ?>
        <td>{{ $group_list }}</td>
        <td>{{ $group->quantity }}</td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
<button type="submit" id="update" class="btn btn-primary" style="margin-left: 15;">Обновить группы</button>
