<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Auth;
use Hash;
use VkRequest;

class ProfileController extends Controller
{
  public function index()
  {
    return view('calendar.profile');
  }

  public function changePassword() {
    $rules = [
      'current_password' => ['required', function ($attribute, $value, $fail){
        if (!\Hash::check($value, Auth::user()->password)) {
          return $fail(__('Неверный текущий пароль'));
        }
      }],
      'new_password' => 'required|min:8|max:16|same:new_password_repeat|different:current_password',
      'new_password_repeat' => 'required'
    ];

    $messages = [
      'min'    => 'Пароль должен содержать минимум :min символов',
      'max'    => 'Пароль может содержать максимум :max символов',
      'same'    => 'Новые пароли не совпадают',
      'different' => 'Новый пароль должен отличаться от старого',
      'required' => 'Все поля должны быть заполнены'
    ];

    $this->validate(request(), $rules, $messages);
    $new_user_password = Hash::make(request('new_password'));
    User::where('id', Auth::user()->id)->update(['password' => $new_user_password, 'password_updated_at' => date('Y-m-d H:i:s')]);

    return redirect()->back()->with('success', 'Пароль успешно изменен');

  }

  public function changeUserData() {

    $rules = [
      'name' => 'required|min:2|max:30',
      'country' => [function ($attribute, $value, $fail){
        if ($value == 'Выберите страну проживания') {
          return $fail(__('Выберите страну проживания'));
        }
      }]
    ];

    $messages = [
      'name.required' => 'Введите своё имя',
      'max' => 'Имя может содержать максимум :max символов',
      'min' => 'Имя должно содержать хотя бы :min символа'
    ];

    $this->validate(request(), $rules, $messages);
    User::where('id', Auth::user()->id)->update(['name' => request('name'), 'country' => request('country')]);

    return redirect()->back()->with('success', 'Информация успешно изменена');

  }

  public function changeToken(Request $request) {

    if(!empty($request->token)) {

      $rules = [

        'token' => ['max:120', function ($attribute, $value, $fail){
          [$checkToken, $error] = VkRequest::checkToken(request('token'));
          if($checkToken === false){
            return $fail(__($error));
          }
        }]
      ];

      $messages = [
        'max'    => 'Максимальное количество символов: :max'
      ];


      $this->validate($request, $rules, $messages);

      User::where('id', Auth::user()->id)
      ->update(['vk_token' => request('token')]);

      return redirect()->back()->with('success', 'Токен успешно сменён!');

    }
    else {
      return redirect()->back()->with('error', 'Вы оставили поле пустым, пожалуйста введите токен ВКонтакте!');
    }

  }

  public function uploadAvatar() {

    if(!empty(Auth::user()->vk_token)) {

      $rules = [
        'inputfile' => 'between:1,1024|image',
      ];

      $messages = [
        'between' => 'Максимальный размер картинки 1024КБ',
        'image' => 'Картинка должна быть формата .jpg .png .jpeg'
      ];

      $this->validate(request(), $rules, $messages);

      $directory = 'images';
      $original_file_name = request('inputfile')->getClientOriginalName();
      request('inputfile')->storeAs($directory, $original_file_name);

      [$uploadAvatar, $avatar] = VkRequest::uploadImage(Auth::user()->vk_token, $original_file_name, $directory);

      if($uploadAvatar != false){

        $sizes = $avatar[0]['sizes'];
        $photo_url = $sizes[array_key_last($sizes)]['url'];

        User::where('id', Auth::user()->id)->update(['avatar' => $photo_url]);
        Storage::delete("$directory" . '/' . "$original_file_name");

        return redirect()->back()->with('success', 'Аватар успешно изменен!');

      }
      else {

        return redirect()->back()->with('error', 'Не удалось загрузить аватар, проверьте свой токен ВКонтакте!');

      }

    }
    else {

      return redirect()->back()->with('error', 'Не удалось загрузить аватар, сначала настройте свой токен ВКонтакте!');

    }

  }
}
