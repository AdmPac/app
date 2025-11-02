<div>
    <x-base.navigation/>
    <main>
        <div style="display:flex; flex-direction:row; flex-wrap:wrap;">
            @foreach ($products as $product)
                @if ($product->status->id == 1)
                    <x-card.product.preview 
                        :name="$product->name"
                        :description="$product->description"
                        :picture="$product->img"
                        :cost="$product->cost"
                        :limit="$product->limit"
                    />
                @endif
            @endforeach
        </div>
    </main>
    
    </div>
</div>
