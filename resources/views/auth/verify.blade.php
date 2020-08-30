<title>Подтверждение почты</title>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Пожалуйста, подтвердите свой почтовый адрес') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('На вашу почту отправлено письмо для подтверждения регистрации') }}
                        </div>
                    @endif

                    {{ __('Пожалуйста, проверьте почту.') }}
                    {{ __('Если вы не получили сообщение на почту') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('нажмите сюда, чтобы получить заново') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
