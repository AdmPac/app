<div>
    <nav>
        <ul>
            <li><a href="/">Главная</a></li>
            <li><a href="orders">Корзина</a></li>
        </ul>
        
        <main>
            <div style="display:flex; flex-direction:row; flex-wrap:wrap;">
                <x-card.product.preview 
                    :name="'test'"
                    :description="'test'"
                    :cost=1000.00
                    :category="'test'"
                    :picture="'test'"
                />
            </div>
        </main>
        
        </div>
    </nav>
</div>
