<form method="POST" action="{{route('product.update', $product->id)}}">
    <div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
        @csrf
        @method('PUT')
        <img src="{{$product->picture}}" alt="{{$product->name}}" style="width:100%; height: 10vw;">
        Название: <input name="name" type="text" value="{{$product->name}}" style="padding:0px">
        Описание: <input name="description" type="text" value="{{$product->description}}" style="padding:0px">
        Цена: <input name="cost" type="text" value="{{$product->cost}}" style="padding:0px">
        Тип: 
        <select name="type_id" id="">
            @foreach ($types as $item)
                <option value="{{$item->id}}" @selected($item->id == $product->type->id)>{{$item->name}}</option>
            @endforeach
        </select>
        Статус:
        <select name="status_id" id="">
            @foreach ($statuses as $item)
                <option value="{{$item->id}}" @selected($item->id == $product->type->id)>{{$item->name}}</option>
            @endforeach
        </select>
        Лимит: <input name="limit" type="text" value="{{$product->limit}}" style="padding:0px">
        <input type="submit" value="Сохранить">
    </div>
</form>