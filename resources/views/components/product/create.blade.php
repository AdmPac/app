<x-base.navigation/>
<form method="POST" action="{{route('product.store')}}">
    <div style="display: flex; flex-direction:column; height: 300px; width: 300px; margin: 10px">
        @csrf
        Ссылка на изображение: <input name="img" type="text" style="padding:0px">
        Название: <input name="name" type="text" style="padding:0px">
        Описание: <input name="description" type="text" style="padding:0px">
        Цена: <input name="cost" type="text" style="padding:0px">
        Тип: 
        <select name="type_id" id="">
            @foreach ($types as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
        Статус:
        <select name="status_id" id="">
            @foreach ($statuses as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
        Лимит: <input name="limit" type="text" style="padding:0px">
        <input type="submit" value="Сохранить">
    </div>
</form>