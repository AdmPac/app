<div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
    <img src="{{$img}}" alt="{{$name}}" style="width:100%; height: 10vw;">
    <span style="padding:0px">Название: {{$name}}</span>
    <span style="padding:0px">Описание:{{$description}}</span>
    <span style="padding:0px">Цена:{{$cost}}</span>
    <form action="{{route('order.update', $id)}}" method="POST">
        @csrf
        <input type="text" required name="quantity" placeholder="Кол-во до {{$limit}}">
        <input type="submit" value="Добавить в корзину">
    </form>
</div>