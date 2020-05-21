@if(Session::has('status'))
    {{Session::get('status')}}
@endif
@isset($token)
    {{ $token}}
@endisset
