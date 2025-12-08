<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-lg p-6">

                {{-- Product Image --}}
                @if ($product->product_images && count($product->product_images) > 0)
                    <img 
                        src="{{ asset('storage/' . $product->product_images[0]->image_path) }}" 
                        alt="{{ $product->name }}" 
                        class="w-full max-h-96 object-cover rounded-md mb-6"
                    >
                @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-md mb-6">
                        <span class="text-gray-500">No Image Available</span>
                    </div>
                @endif

                {{-- Product Name --}}
                <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>

                {{-- Price --}}
                <p class="text-xl font-semibold text-green-600 mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                {{-- Store Name --}}
                @if ($product->store)
                    <p class="text-sm text-gray-600 mb-4">
                        Sold by: <span class="font-medium">{{ $product->store->name }}</span>
                    </p>
                @endif

                {{-- Description --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $product->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Buy Button --}}
                <form action="/checkout" method="GET">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button 
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                        Buy Now
                    </button>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>