@extends('layouts.app') {{-- atau sesuaikan dengan layout kamu --}}

@section('content')
<div class="max-w-5xl mx-auto mt-10">

    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Kartu Verifikasi Toko --}}
        <a href="{{ route('admin.verification') }}"
            class="block p-6 bg-white border rounded-xl shadow hover:shadow-md transition">
            <h2 class="text-lg font-semibold mb-2">Verifikasi Toko</h2>
            <p class="text-sm text-gray-600">
                Lihat dan verifikasi toko yang belum disetujui.
            </p>
            <div class="mt-4">
                <span class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded-lg">
                    Masuk
                </span>
            </div>
        </a>

        {{-- Kartu Manajemen User & Store --}}
        <a href="{{ route('admin.users') }}"
           class="block p-6 bg-white border rounded-xl shadow hover:shadow-md transition">
            <h2 class="text-lg font-semibold mb-2">Manajemen User & Store</h2>
            <p class="text-sm text-gray-600">
                Kelola seluruh user dan semua store pada platform.
            </p>
            <div class="mt-4">
                <span class="inline-block px-4 py-2 bg-green-600 text-white text-sm rounded-lg">
                    Masuk
                </span>
            </div>
        </a>

    </div>
    <a href="/admin/create-seller-form">Tambah Seller</a>


</div>
@endsection