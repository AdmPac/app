<nav>
    <ul>
        <li><a href="{{url('/')}}"><b>Главная</b></a></li>
        <li><a href="{{url('orders')}}">Корзина</a></li>
        @if(auth()->check() && Auth::user()->is_admin)
            <li><a href="{{url('admin')}}">Админка</a></li>
        @endif
        @if(auth()->check())
            <li><a href="{{url('authorize')}}">Профиль [{{Auth::user()->name}}]</a></li>
            <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выход</a></li>
        @else
            <li><a href="{{url('authorize')}}">Авторизация</a></li>
        @endif
    </ul>
</nav>
<form id="logout-form" action="{{ route('authorize.logout') }}" method="POST" style="display: none;">
    @csrf
</form>