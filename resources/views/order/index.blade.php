<style>
    table, th, td {
      border:1px solid black;
    }
</style>
<x-base.navigation/>
<div style="display:flex; flex-direction:row; flex-wrap:wrap;">
    @foreach($products as $product)
    <div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
        <img src="{{$product['img']}}" alt="{{$product['name']}}" style="width:100%; height: 10vw;">
        <span>Название: {{$product['name']}}</span>
        <span>Описание:{{$product['description']}}</span>
        <span>Цена:{{$product['cost']}}</span>
        <span>Кол-во:{{$quantity[$product['id']]}}</span>
        <span>Общая стоимость:{{$product['cost'] * $quantity[$product['id']]}}</span>
        <form action="{{route('order.delete', $product['id'])}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="Удалить из корзины">
        </form>
    </div>
    @endforeach
</div>