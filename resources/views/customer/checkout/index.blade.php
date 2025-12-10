@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        {{-- Produk --}}
        <table class="w-full border-collapse mb-6">
            <thead>
                <tr>
                    <th class="border p-2 text-left">Produk</th>
                    <th class="border p-2 text-left">Harga</th>
                    <th class="border p-2 text-left">Qty</th>
                    <th class="border p-2 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($products as $p)
                    @php
                        $qty = $cart[$p->id] ?? 1;
                        $subtotal = $p->price * $qty;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="border p-2">{{ $p->name }}</td>
                        <td class="border p-2">Rp {{ number_format($p->price) }}</td>
                        <td class="border p-2">
                            <input type="number" name="qty[{{ $p->id }}]" value="{{ $qty }}" min="1" max="{{ $p->stock }}" class="border px-2 py-1 w-20">
                            <input type="hidden" name="product_id[]" value="{{ $p->id }}">
                        </td>
                        <td class="border p-2">Rp {{ number_format($subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Alamat Lengkap</label>
            <textarea name="address" class="w-full border px-3 py-2" required>{{ old('address') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold mb-1">Kota</label>
                <input type="text" name="city" class="w-full border px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Kode Pos</label>
                <input type="text" name="postal_code" class="w-full border px-3 py-2" required>
            </div>
        </div>

        {{-- Kurir --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Kurir Pengiriman</label>
            <select name="shipping" class="w-full border px-3 py-2" required>
                <option value="jne">JNE</option>
                <option value="jnt">JNT</option>
                <option value="pos">POS Indonesia</option>
            </select>
        </div>

        {{-- Tipe Pengiriman --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tipe Pengiriman</label>
            <select name="shipping_type" class="border px-3 py-2 w-full" required>
                <option value="regular">Regular (Rp 10.000)</option>
                <option value="express">Express (Rp 20.000)</option>
            </select>
        </div>

        {{-- Payment --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Metode Pembayaran</label>
            <select name="payment_method" class="border px-3 py-2 w-full" required>
                <option value="saldo">Saldo</option>
                <option value="va">Virtual Account</option>
            </select>
        </div>

        {{-- Summary --}}
        <div class="mb-4 font-bold text-lg">
            Total Produk: Rp {{ number_format($total) }} <br>
            Ongkir: <span id="shipping-cost">Rp 10.000</span> <br>
            <span class="text-xl">Total Bayar: 
                <span id="total-pay">Rp {{ number_format($total + 10000) }}</span>
            </span>
        </div>

        <input type="hidden" name="tax" value="0">

        <button type="submit" class="bg-blue-600 text-black px-6 py-2 rounded hover:bg-blue-700">
            Checkout
        </button>
    </form>
</div>

<script>
    const shippingSelect = document.querySelector('select[name="shipping_type"]');
    const shippingCostSpan = document.getElementById('shipping-cost');
    const totalPaySpan = document.getElementById('total-pay');
    let subtotal = {{ $total ?? 0 }};

    shippingSelect.addEventListener('change', function() {
        let cost = this.value === 'express' ? 20000 : 10000;
        shippingCostSpan.textContent = 'Rp ' + cost.toLocaleString();
        totalPaySpan.textContent = 'Rp ' + (subtotal + cost).toLocaleString();
    });
</script>
@endsection