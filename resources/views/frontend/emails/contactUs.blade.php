@component('mail::layout')
    @slot('header')
        @component('mail::header' , ['url' => url()->to('/')])
            وورکی
        @endcomponent
    @endslot
    شما یک پیام جدید از طرف یکی از کاربران سایت وورکی دارید. <br/>
    ایمیل: {{ $email }} <br/>
    پیام: {{ $message }}
    @slot('footer')
        @component('mail::footer')
            تمامی حقوقی برای وورکی محفوظ است.
        @endcomponent
    @endslot
@endcomponent