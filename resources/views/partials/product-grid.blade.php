<div class="product-grid">
    @forelse($products as $product)
        <div class="product-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover-lift h-full flex flex-col">
            <div class="product-image-container" style="height: 40%;">
                @if($product->menuImage)
                    <img src="{{ route('serve.image', ['filename' => basename($product->menuImage)]) }}" 
                         alt="{{ $product->menuName }}" 
                         class="product-image">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                    </div>
                @endif
            </div>
            
            <div class="p-4 flex flex-col" style="height: 60%;">
                <div class="flex justify-between items-start mb-1 gap-2">
                    <h3 class="text-sm font-bold text-gray-800 flex-1 line-clamp-2">{{ $product->menuName }}</h3>
                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                        {{ ucfirst($product->menuSubcategory) }}
                    </span>
                </div>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-base font-bold text-amber-700">₱{{ number_format($product->menuPrice, 2) }}</span>
                    <button class="add-to-order-btn bg-amber-600 hover:bg-amber-700 text-white px-2 py-1 rounded-lg font-medium transition-colors duration-200 flex items-center text-xs"
                            data-name="{{ $product->menuName }}" 
                            data-price="{{ $product->menuPrice }}" 
                            data-category="{{ $product->menuCategory }}"
                            data-image="{{ $product->menuImage ? route('serve.image', ['filename' => basename($product->menuImage)]) : '' }}">
                        <i class="fas fa-plus mr-1"></i> Add
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 row-span-2 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-utensils text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-500 mb-2">No products found</h3>
                <p class="text-gray-400 text-sm">No items available in this category</p>
            </div>
        </div>
    @endforelse
</div>