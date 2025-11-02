<x-base.navigation/>
<div style="display:flex; flex-direction:column;">
    <x-card.product.edit 
        :product="$product"
        :types="$types"
        :statuses="$statuses"
    />
</div>