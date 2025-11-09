<style>
    table, th, td {
      border:1px solid black;
    }
</style>
<x-base.navigation/>
<table>
    <tr>
        @foreach ($keys as $colName)
            <th>{{ __('order.' . $colName) }}</th>
        @endforeach
    </tr>
    
    @foreach ($orderData as $order)
    <tr>
        @foreach ($order as $col => $data)
            @if ($col === 'id')
                <th><a href="{{ route('order.form', $data) }}">{{ $data }}</a></th>
            @else
                <th>{{ $data }}</th>
            @endif
            
        @endforeach
    </tr>
    @endforeach
</table>