<x-base.navigation/>
<main>
    <div style="display:flex; flex-direction:row; flex-wrap:wrap;">
    @foreach ($products as $product)
        <x-card.product.preview 
            :id="$product->id"
            :name="$product->name"
            :description="$product->description"
            :img="$product->img"
            :cost="$product->cost"
            :limit="$product->limit"
        />
    @endforeach
    </div>
</main>