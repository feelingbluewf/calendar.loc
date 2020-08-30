
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Профиль</title>



<link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}" rel='stylesheet' />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


@extends('layouts.app')



@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Профиль</div>
        <div class="content">
          <div class="avatar">
            @if(Auth::user()->avatar !== NULL)
            <img src="{{ Auth::user()->avatar }}">
            @else
            <img src="https://sun9-58.userapi.com/c857336/v857336273/bb0bd/2FhMZ8NKpfw.jpg">
            @endif
            <form method="POST" action="/profile/uploadAvatar" enctype="multipart/form-data" class="form-clearfix" id='form_send_file'>
              {{ csrf_field() }}
              <div class="custom-file margin-top">
                <label for="inputfile" class="custom-file-upload">
                  <i class="fa fa-cloud-upload"></i> Загрузить
                </label>
                <input class="custom-file-input hide" onchange="document.getElementById('form_send_file').submit()" style="z-index: -1;" type="file" id="inputfile" name="inputfile" value="Выбрать файл">
              </div>
            </form>
          </div>
          <div class="form-wrap">
            <form style="display: inline-block;" method="post" action="/profile/changeUserData" id="user_data_form">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
              <div>
                <label for="name">Имя:</label>
                @if(Auth::user()->name !== 'Guest')
                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}">
                @else
                <input type="text" placeholder="Укажите имя" name="name" id="name">
                @endif
              </div>
              <div>
                <label for="email">E-mail:</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" disabled>
              </div>
              <div>
                <label for="country">Страна:</label>
                <select name="country" id="country">
                  <option>Выберите страну проживания</option>
                  <option value="Австралия">Австралия</option>
                  <option value="Австрия">Австрия</option>
                  <option value="Азербайджан">Азербайджан</option>
                  <option value="Албания">Албания</option>
                  <option value="Алжир">Алжир</option>
                  <option value="Американское Самоа">Американское Самоа</option>
                  <option value="Ангилья">Ангилья</option>
                  <option value="Ангола">Ангола</option>
                  <option value="Андорра">Андорра</option>
                  <option value="Антигуа и Барбуда">Антигуа и Барбуда</option>
                  <option value="Аргентина">Аргентина</option>
                  <option value="Армения">Армения</option>
                  <option value="Аруба">Аруба</option>
                  <option value="Афганистан">Афганистан</option>
                  <option value="Багамы">Багамы</option>
                  <option value="Бангладеш">Бангладеш</option>
                  <option value="Барбадос">Барбадос</option>
                  <option value="Бахрейн">Бахрейн</option>
                  <option value="Беларусь">Беларусь</option>
                  <option value="Белиз">Белиз</option>
                  <option value="Бельгия">Бельгия</option>
                  <option value="Бенин">Бенин</option>
                  <option value="Бермуды">Бермуды</option>
                  <option value="Болгария">Болгария</option>
                  <option value="Боливия">Боливия</option>
                  <option value="Бонайре, Синт-Эстатиус и Саба">Бонайре, Синт-Эстатиус и Саба</option>
                  <option value="Босния и Герцеговина">Босния и Герцеговина</option>
                  <option value="Ботсвана">Ботсвана</option>
                  <option value="Бразилия">Бразилия</option>
                  <option value="Бруней-Даруссалам">Бруней-Даруссалам</option>
                  <option value="Буркина-Фасо">Буркина-Фасо</option>
                  <option value="Бурунди">Бурунди</option>
                  <option value="Бутан">Бутан</option>
                  <option value="Вануату">Вануату</option>
                  <option value="Ватикан">Ватикан</option>
                  <option value="Великобритания">Великобритания</option>
                  <option value="Венгрия">Венгрия</option>
                  <option value="Венесуэла">Венесуэла</option>
                  <option value="Виргинские острова, Британские">Виргинские острова, Британские</option>
                  <option value="Виргинские острова, США">Виргинские острова, США</option>
                  <option value="Восточный Тимор">Восточный Тимор</option>
                  <option value="Вьетнам">Вьетнам</option>
                  <option value="Габон">Габон</option>
                  <option value="Гаити">Гаити</option>
                  <option value="Гайана">Гайана</option>
                  <option value="Гамбия">Гамбия</option>
                  <option value="Гана">Гана</option>
                  <option value="Гваделупа">Гваделупа</option>
                  <option value="Гватемала">Гватемала</option>
                  <option value="Гвинея">Гвинея</option>
                  <option value="Гвинея-Бисау">Гвинея-Бисау</option>
                  <option value="Германия">Германия</option>
                  <option value="Гибралтар">Гибралтар</option>
                  <option value="Гондурас">Гондурас</option>
                  <option value="Гонконг">Гонконг</option>
                  <option value="Гренада">Гренада</option>
                  <option value="Гренландия">Гренландия</option>
                  <option value="Греция">Греция</option>
                  <option value="Грузия">Грузия</option>
                  <option value="Гуам">Гуам</option>
                  <option value="Дания">Дания</option>
                  <option value="Джибути">Джибути</option>
                  <option value="Доминика">Доминика</option>
                  <option value="Доминиканская Республика">Доминиканская Республика</option>
                  <option value="Египет">Египет</option>
                  <option value="Замбия">Замбия</option>
                  <option value="Западная Сахара">Западная Сахара</option>
                  <option value="Зимбабве">Зимбабве</option>
                  <option value="Израиль">Израиль</option>
                  <option value="Индия">Индия</option>
                  <option value="Индонезия">Индонезия</option>
                  <option value="Иордания">Иордания</option>
                  <option value="Ирак">Ирак</option>
                  <option value="Иран">Иран</option>
                  <option value="Ирландия">Ирландия</option>
                  <option value="Исландия">Исландия</option>
                  <option value="Испания">Испания</option>
                  <option value="Италия">Италия</option>
                  <option value="Йемен">Йемен</option>
                  <option value="Кабо-Верде">Кабо-Верде</option>
                  <option value="Казахстан">Казахстан</option>
                  <option value="Камбоджа">Камбоджа</option>
                  <option value="Камерун">Камерун</option>
                  <option value="Канада">Канада</option>
                  <option value="Катар">Катар</option>
                  <option value="Кения">Кения</option>
                  <option value="Кипр">Кипр</option>
                  <option value="Кирибати">Кирибати</option>
                  <option value="Китай">Китай</option>
                  <option value="Колумбия">Колумбия</option>
                  <option value="Коморы">Коморы</option>
                  <option value="Конго">Конго</option>
                  <option value="Конго, демократическая республика">Конго, демократическая республика</option>
                  <option value="Коста-Рика">Коста-Рика</option>
                  <option value="Кот д`Ивуар">Кот д`Ивуар</option>
                  <option value="Куба">Куба</option>
                  <option value="Кувейт">Кувейт</option>
                  <option value="Кыргызстан">Кыргызстан</option>
                  <option value="Кюрасао">Кюрасао</option>
                  <option value="Лаос">Лаос</option>
                  <option value="Латвия">Латвия</option>
                  <option value="Лесото">Лесото</option>
                  <option value="Либерия">Либерия</option>
                  <option value="Ливан">Ливан</option>
                  <option value="Ливия">Ливия</option>
                  <option value="Литва">Литва</option>
                  <option value="Лихтенштейн">Лихтенштейн</option>
                  <option value="Люксембург">Люксембург</option>
                  <option value="Маврикий">Маврикий</option>
                  <option value="Мавритания">Мавритания</option>
                  <option value="Мадагаскар">Мадагаскар</option>
                  <option value="Макао">Макао</option>
                  <option value="Македония">Македония</option>
                  <option value="Малави">Малави</option>
                  <option value="Малайзия">Малайзия</option>
                  <option value="Мали">Мали</option>
                  <option value="Мальдивы">Мальдивы</option>
                  <option value="Мальта">Мальта</option>
                  <option value="Марокко">Марокко</option>
                  <option value="Мартиника">Мартиника</option>
                  <option value="Маршалловы Острова">Маршалловы Острова</option>
                  <option value="Мексика">Мексика</option>
                  <option value="Микронезия, федеративные штаты">Микронезия, федеративные штаты</option>
                  <option value="Мозамбик">Мозамбик</option>
                  <option value="Молдова">Молдова</option>
                  <option value="Монако">Монако</option>
                  <option value="Монголия">Монголия</option>
                  <option value="Монтсеррат">Монтсеррат</option>
                  <option value="Мьянма">Мьянма</option>
                  <option value="Намибия">Намибия</option>
                  <option value="Науру">Науру</option>
                  <option value="Непал">Непал</option>
                  <option value="Нигер">Нигер</option>
                  <option value="Нигерия">Нигерия</option>
                  <option value="Нидерланды">Нидерланды</option>
                  <option value="Никарагуа">Никарагуа</option>
                  <option value="Ниуэ">Ниуэ</option>
                  <option value="Новая Зеландия">Новая Зеландия</option>
                  <option value="Новая Каледония">Новая Каледония</option>
                  <option value="Норвегия">Норвегия</option>
                  <option value="Объединенные Арабские Эмираты">Объединенные Арабские Эмираты</option>
                  <option value="Оман">Оман</option>
                  <option value="Остров Мэн">Остров Мэн</option>
                  <option value="Остров Норфолк">Остров Норфолк</option>
                  <option value="Острова Кайман">Острова Кайман</option>
                  <option value="Острова Кука">Острова Кука</option>
                  <option value="Острова Теркс и Кайкос">Острова Теркс и Кайкос</option>
                  <option value="Пакистан">Пакистан</option>
                  <option value="Палау">Палау</option>
                  <option value="Палестинская автономия">Палестинская автономия</option>
                  <option value="Панама">Панама</option>
                  <option value="Папуа - Новая Гвинея">Папуа - Новая Гвинея</option>
                  <option value="Парагвай">Парагвай</option>
                  <option value="Перу">Перу</option>
                  <option value="Питкерн">Питкерн</option>
                  <option value="Польша">Польша</option>
                  <option value="Португалия">Португалия</option>
                  <option value="Пуэрто-Рико">Пуэрто-Рико</option>
                  <option value="Реюньон">Реюньон</option>
                  <option value="Россия">Россия</option>
                  <option value="Руанда">Руанда</option>
                  <option value="Румыния">Румыния</option>
                  <option value="США">США</option>
                  <option value="Сальвадор">Сальвадор</option>
                  <option value="Самоа">Самоа</option>
                  <option value="Сан-Марино">Сан-Марино</option>
                  <option value="Сан-Томе и Принсипи">Сан-Томе и Принсипи</option>
                  <option value="Саудовская Аравия">Саудовская Аравия</option>
                  <option value="Свазиленд">Свазиленд</option>
                  <option value="Святая Елена">Святая Елена</option>
                  <option value="Северная Корея">Северная Корея</option>
                  <option value="Северные Марианские острова">Северные Марианские острова</option>
                  <option value="Сейшелы">Сейшелы</option>
                  <option value="Сенегал">Сенегал</option>
                  <option value="Сент-Винсент">Сент-Винсент</option>
                  <option value="Сент-Китс и Невис">Сент-Китс и Невис</option>
                  <option value="Сент-Люсия">Сент-Люсия</option>
                  <option value="Сент-Пьер и Микелон">Сент-Пьер и Микелон</option>
                  <option value="Сербия">Сербия</option>
                  <option value="Сингапур">Сингапур</option>
                  <option value="Синт-Мартен">Синт-Мартен</option>
                  <option value="Сирийская Арабская Республика">Сирийская Арабская Республика</option>
                  <option value="Словакия">Словакия</option>
                  <option value="Словения">Словения</option>
                  <option value="Соломоновы Острова">Соломоновы Острова</option>
                  <option value="Сомали">Сомали</option>
                  <option value="Судан">Судан</option>
                  <option value="Суринам">Суринам</option>
                  <option value="Сьерра-Леоне">Сьерра-Леоне</option>
                  <option value="Таджикистан">Таджикистан</option>
                  <option value="Таиланд">Таиланд</option>
                  <option value="Тайвань">Тайвань</option>
                  <option value="Танзания">Танзания</option>
                  <option value="Того">Того</option>
                  <option value="Токелау">Токелау</option>
                  <option value="Тонга">Тонга</option>
                  <option value="Тринидад и Тобаго">Тринидад и Тобаго</option>
                  <option value="Тувалу">Тувалу</option>
                  <option value="Тунис">Тунис</option>
                  <option value="Туркменистан">Туркменистан</option>
                  <option value="Турция">Турция</option>
                  <option value="Уганда">Уганда</option>
                  <option value="Узбекистан">Узбекистан</option>
                  <option value="Украина">Украина</option>
                  <option value="Уоллис и Футуна">Уоллис и Футуна</option>
                  <option value="Уругвай">Уругвай</option>
                  <option value="Фарерские острова">Фарерские острова</option>
                  <option value="Фиджи">Фиджи</option>
                  <option value="Филиппины">Филиппины</option>
                  <option value="Финляндия">Финляндия</option>
                  <option value="Фолклендские острова">Фолклендские острова</option>
                  <option value="Франция">Франция</option>
                  <option value="Французская Гвиана">Французская Гвиана</option>
                  <option value="Французская Полинезия">Французская Полинезия</option>
                  <option value="Хорватия">Хорватия</option>
                  <option value="Центрально-Африканская Республика">Центрально-Африканская Республика</option>
                  <option value="Чад">Чад</option>
                  <option value="Черногория">Черногория</option>
                  <option value="Чехия">Чехия</option>
                  <option value="Чили">Чили</option>
                  <option value="Швейцария">Швейцария</option>
                  <option value="Швеция">Швеция</option>
                  <option value="Шпицберген и Ян Майен">Шпицберген и Ян Майен</option>
                  <option value="Шри-Ланка">Шри-Ланка</option>
                  <option value="Эквадор">Эквадор</option>
                  <option value="Экваториальная Гвинея">Экваториальная Гвинея</option>
                  <option value="Эритрея">Эритрея</option>
                  <option value="Эстония">Эстония</option>
                  <option value="Эфиопия">Эфиопия</option>
                  <option value="Южная Корея">Южная Корея</option>
                  <option value="Южно-Африканская Республика">Южно-Африканская Республика</option>
                  <option value="Южный Судан">Южный Судан</option>
                  <option value="Ямайка">Ямайка</option>
                  <option value="Япония">Япония</option>
                </select>
                <div class="select-arrow"></div> 
              </div>
              <button style="float: right;" type="submit" class="btn btn-success margin-top">Сохранить</button> 
            </form> 
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">Данные</div>
        <div class="content">
          <div class="form-wrap">
            <!-- скрытая форма смены пароля -->
            <form method="post" action="/profile/changePassword" id="changePassword" class="form-clearfix hide">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
              <div class="custom-checkbox">
                <div class="right">
                  <input type="checkbox" class="custom-control-input" id="change_password">
                  <label class="custom-control-label" for="change_password">Сменить</label>
                </div>
                <label for="current_password">Текущий пароль:</label>
                <input type="password" id="current_password" name="current_password" autocomplete required>
              </div>
              <div>
                <label for="new_password">Новый пароль:</label>
                <input type="password" id="new_password" name="new_password" autocomplete required>
              </div>
              <div>
                <label for="new_password_repeat">Повторите пароль:</label>
                <input type="password" id="new_password_repeat" name="new_password_repeat" autocomplete required>
              </div>
              <button type="submit" class="btn btn-success margin-top">Сохранить</button> 
            </form>
            <!-- инпут пароля -->
            <div class="custom-checkbox" id="password">
              <label for="password">Пароль:</label>
              <div class="right">
                <input type="checkbox" class="custom-control-input" id="change_password">
                <label class="custom-control-label" for="change_password">Сменить</label>
              </div>
              @if(Auth::user()->password_updated_at !== NULL)
              <input type="text" value="Последний раз был изменен: {{ Auth::user()->password_updated_at }}" name="password" disabled>
              @else
              <input type="text" value="Последний раз был изменен:" name="password" disabled> 
              @endif
            </div>
            <!-- инпут токена -->
            <form class="form-clearfix" method="post" action="/profile/changeToken" id="token_form">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
              <div class="custom-checkbox margin-top">
                <div class="right">
                  <input type="checkbox" class="custom-control-input" id="change_token">
                  <label class="custom-control-label" style="color: black;" for="change_token">Сменить</label>
                </div>
                <label for="token">Токен:</label>
                @if(Auth::user()->vk_token !== NULL)
                <input type="password" id="token" name="token" value="{{ Auth::user()->vk_token }}" disabled>
                @else
                <input type="text" id="token" name="token" placeholder="Введите токен" disabled>
                @endif
                <button type="submit" id="update_token" class="btn btn-success margin-top">Сохранить</button>
              </div>
            </form>
            @if(count($errors))
            <div class="alert alert-danger">
              <ul>

                @foreach ($errors->all() as $error)

                <li>
                  {{ $error }}
                </li>

                @endforeach
              </ul>
            </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger">
              <ul>
                <li>
                  {{ session('error') }}
                </li>
              </ul>
            </div>
            @endif
            @if(session()->has('success'))
            <div class="alert alert-success">
              <ul>
                <li>
                  {{ session('success') }}
                </li>
              </ul>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div> 
  </div>
</div>
@endsection

<script type="text/javascript">
  user_id = <?php echo Auth::user()->id ?>;
  $(document).ready(function(){
    var country = '<?php echo Auth::user()->country ?>';
    if(country !== 'NULL'){
      $('#country option[value="' + country + '"]').prop('selected', true);
    }
    $('#update_token').addClass('hide');
    $('#change_token').change(function() {
      if ($('#change_token').is(':checked')) {
        $('#token').prop('disabled', false);
        $('#update_token').removeClass('hide');
      }
      else{
        $('#token').prop('disabled', true);
        $('#update_token').addClass('hide');
      }
    });

    $('#change_password').change(function() {
      if ($('#change_password').is(':checked')) {
        $('#password').addClass('hide');
        $('#changePassword').removeClass('hide');
      }
      else{
        $('#password').removeClass('hide');
        $('#changePassword').addClass('hide');
      }
    });
  });
</script>