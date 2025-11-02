<style>
    table, th, td {
      border:1px solid black;
    }
</style>
<div>
    <x-base.navigation/>
    <main>
        <table>
            <tr>    
                @foreach(array_keys($products->first()->toArray()) as $col)
                <th>{{$col}}</th>
                @endforeach
            </tr>
            @foreach($products->toArray() as $k => $product)
            <tr>
                @foreach(array_values($product) as $value)
                    <td>{{$value}}</td>
                @endforeach
                <td><a href="{{route('product.page.edit', ($k+1))}}">Изменить</a></td>
            </tr>
            @endforeach
        </table>
        <input type="button" value="Добавить">
    </main>
    </nav>
</div>
