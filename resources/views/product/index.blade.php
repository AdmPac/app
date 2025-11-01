<div>
    <nav>
        <ul>
            <li><a href="/">Главная</a></li>
            <li><a href="orders">Корзина</a></li>
        </ul>
        <main>
            <div style="display:flex; flex-direction:row; flex-wrap:wrap;">
                @foreach ($products as $product)
                    <x-card.product.preview 
                        :name="$product->name"
                        :description="$product->description"
                        :picture="$product->img"
                        :cost="$product->cost"
                        :limit="$product->limit"
                    />
                @endforeach
            </div>
        </main>
        
        </div>
    </nav>
</div>
