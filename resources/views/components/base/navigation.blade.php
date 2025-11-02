<nav>
    <ul>
        <li><a href="{{url('/')}}"><b>Главная</b></a></li>
        <li><a href="{{url('orders')}}">Корзина</a></li>
        <li><a href="{{url('admin')}}">Админка</a></li>
        @if (url()->previous() != url()->current())
            <li><a href="{{url()->previous()}}">Назад</a></li>
        @endif
    </ul>
</nav>