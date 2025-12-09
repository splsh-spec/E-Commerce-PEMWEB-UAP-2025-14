<form action="{{ route('admin.create.seller') }}" method="POST">
    @csrf

    <h3>Buat Akun Seller + Toko</h3>

    <label>Nama Seller</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Nama Toko</label>
    <input type="text" name="store_name" required>

    <label>About Toko</label>
    <textarea name="about"></textarea>

    <label>No. Telp</label>
    <input type="text" name="phone" required>

    <label>Kota</label>
    <input type="text" name="city" required>

    <label>Alamat Lengkap</label>
    <input type="text" name="address" required>

    <label>Kode Pos</label>
    <input type="text" name="postal_code" required>

    <button type="submit">Buat Seller</button>
</form>
