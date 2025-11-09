<x-base.navigation/>
<p>Заказ № {{$orderId}}</p>
<p>Кол-во позиций: {{$allCnt}}</p>
<p>Общая стоимость: {{$allSum}}</p>
<p>Статус: {{$statusName}}</p>
@if ($isCart)
<p>Введите данные</p>
<form action="{{route('order.delivery', $orderId)}}" method="POST">
    @csrf
    <input type="text" name="phone" value="{{$phone}}" placeholder="Номер телефона">   
    <input type="text" name="address" value="{{$address}}" placeholder="Адрес">
    <input type="submit" value="Заказать">
</form>
@else
<p>Адрес доставки: {{$address}}</p>
<p>Телефон доставки: {{$phone}}</p>
@endif