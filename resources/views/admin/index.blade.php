<style>
    table, th, td {
      border:1px solid black;
    }
</style>
<x-base.navigation/>
<table>
    <tr>    
        @foreach(array_keys($products->first()->toArray()) as $col)
        <th>{{$col}}</th>
        @endforeach
    </tr>
    @foreach($products->toArray() as $product)
    <tr>
        @foreach(array_values($product) as $value)
            <td>{{$value}}</td>
        @endforeach
        <td><a href="{{route('product.page.edit', $product['id'])}}">Изменить</a></td>
        <td>
        <form method="POST" action="{{route('product.delete', $product['id'])}}">
            @csrf
            @method('DELETE')
            <input type="submit" value="Удалить">
        </form>
        </td>
    </tr>
    @endforeach
    
</table>
<h2><a href="{{route('product.create')}}">Добавить</a></h2>