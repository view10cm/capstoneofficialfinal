<div class="product-grid">
    @forelse($products as $product)
        <div class="product-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover-lift h-full flex flex-col">
            <!-- Product Image -->
            <div class="product-image-container">
                @if($product->menuImage)
                    <img src="{{ asset('storage/' . $product->menuImage) }}" 
                         alt="{{ $product->menuName }}" 
                         class="product-image">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                    </div>
                @endif
            </div>
            
            <div class="p-4 flex-1">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-800 truncate">{{ $product->menuName }}</h3>
                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                        {{ ucfirst($product->menuSubcategory) }}
                    </span>
                </div>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-xl font-bold text-amber-700">₱{{ number_format($product->menuPrice, 2) }}</span>
                    <button class="add-to-order-btn bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm"
                            data-name="{{ $product->menuName }}" 
                            data-price="{{ $product->menuPrice }}" 
                            data-category="{{ $product->menuCategory }}"
                            data-image="{{ $product->menuImage ? asset('storage/' . $product->menuImage) : '' }}">
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