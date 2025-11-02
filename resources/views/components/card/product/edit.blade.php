<form action="{{url('product.edit')}}">
    <div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
        @csrf
        @method('PUT')
        <img src="{{$product->picture}}" alt="{{$product->name}}" style="width:100%; height: 10vw;">
        Название: <input type="text" value="{{$product->name}}" style="padding:0px">
        Описание: <input type="text" value="{{$product->description}}" style="padding:0px">
        Цена: <input type="text" value="{{$product->cost}}" style="padding:0px">
        Тип: 
        <select name="" id="">
            @foreach ($types as $item)
                <option value="" @selected($item->id == $product->type->id)>{{$item->name}}</option>
            @endforeach
        </select>
        Статус:
        <select name="" id="">
            @foreach ($statuses as $item)
                <option value="" @selected($item->id == $product->type->id)>{{$item->name}}</option>
            @endforeach
        </select>
        Лимит: <input type="text" value="{{$product->limit}}" style="padding:0px">
        <input type="button" value="Сохранить">
    </div>
</form>