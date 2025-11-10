<nav>
    <ul>
        <li><a href="{{route('product.index')}}"><b>Главная</b></a></li>
        <li><a href="{{route('order.index')}}">Корзина</a></li>
        <li><a href="{{route('order.all')}}">Доставки</a></li>
        @if(auth()->check() && Auth::user()->is_admin)
            <li><a href="{{route('admin.index')}}">Админка</a></li>
        @endif
        @if(auth()->check())
            <li>Профиль [{{Auth::user()->name}}]</li>
            <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выход</a></li>
        @else
            <li><a href="{{url('login')}}">Авторизация</a></li>
        @endif
    </ul>
</nav>
<form id="logout-form" action="{{ route('authorize.logout') }}" method="POST" style="display: none;">
    @csrf
</form>