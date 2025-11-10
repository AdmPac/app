<x-base.navigation/>
<p>Заказ № {{$returnData['orderId']}}</p>
<p>Кол-во позиций: {{$returnData['allCnt']}}</p>
<p>Общая стоимость: {{$returnData['allSum']}}</p>

@if ($returnData['isAdmin'])
<form action="{{route('order.form.edit', request()->route('id'))}}" method="post">
    @csrf
    @method('PATCH')
    <select name="status_id">
        @foreach ($returnData['allStatus'] as $status)
            <option value="{{$status['id']}}" @selected($status['id'] === $returnData['statusId'])>{{$status['value']}}</option>
        @endforeach
    </select>
    <input type="submit" value="Сохранить">
</form>
@else
    <p>Статус: {{$returnData['statusName']}}</p>
@endif

@if ($returnData['isCart'])
<p>Введите данные</p>
<form action="{{route('order.delivery', $returnData['orderId'])}}" method="POST">
    @csrf
    <input type="text" name="phone" value="{{$returnData['phone']}}" placeholder="Номер телефона">   
    <input type="text" name="address" value="{{$returnData['address']}}" placeholder="Адрес">
    <input type="submit" value="Заказать">
</form>
@else
<p>Адрес доставки: {{$returnData['address']}}</p>
<p>Телефон доставки: {{$returnData['phone']}}</p>
@endif