<style>
    table, th, td {
      border:1px solid black;
    }
</style>
<x-base.navigation/>
<div style="display:flex; flex-direction:row; flex-wrap:wrap;">
    @if (!count($products)) 
    <h1>Корзина пуста <a href="{{route('product.index')}}">Заказать</a></h1>
    @endif
    @foreach($products as $product)
    <div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
        <img src="{{$product['img']}}" alt="{{$product['name']}}" style="width:100%; height: 10vw;">
        <span>Название: {{$product['name']}}</span>
        <span>Описание:{{$product['description']}}</span>
        <span>Цена:{{$product['cost']}}</span>
        <span>Кол-во:{{$product['quantity']}}</span>
        <span>Общая стоимость:{{$product['cost'] * $product['quantity']}}</span>
        <form action="{{route('order.delete', ['orderId' => $product['order_id'] ?? 0, 'id' => $product['id']])}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="Удалить из корзины">
        </form>
    </div>
    @endforeach
</div>
<br><br><br><br><br><br><br><br>
@if (count($products)) 
Общая стоимость корзины: {{$allSum}}
@endif