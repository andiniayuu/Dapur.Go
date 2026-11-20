<div class="form-group">

    <label>Nama Menu</label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $menu->name ?? '') }}"
        placeholder="Contoh: Paket Nasi Box Ayam"
        required>

    @error('name')
        <small class="error-text">{{ $message }}</small>
    @enderror

</div>

<div class="form-group">

    <label>Kategori</label>

    <select name="category_id" required>

        <option value="">Pilih Kategori</option>

        @foreach ($categories as $category)

            <option
                value="{{ $category->id }}"
                @selected(
                    old('category_id', $menu->category_id ?? '') == $category->id
                )>

                {{ $category->name }}

            </option>

        @endforeach

    </select>

    @error('category_id')
        <small class="error-text">{{ $message }}</small>
    @enderror

</div>

<div class="form-group">

    <label>Harga Menu (Rp)</label>

    <input
        type="number"
        name="price"
        min="0"
        step="100"
        value="{{ old('price', $menu->price ?? '') }}"
        placeholder="Contoh: 25000"
        required>

    @error('price')
        <small class="error-text">{{ $message }}</small>
    @enderror

</div>

<div class="form-group">

    <label>Deskripsi Menu</label>

    <textarea
        name="description"
        rows="4"
        placeholder="Jelaskan isi dan detail menu...">{{ old('description', $menu->description ?? '') }}</textarea>

    @error('description')
        <small class="error-text">{{ $message }}</small>
    @enderror

</div>

<div class="form-group">

    <label>Foto Menu</label>

    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp">

    <small class="form-hint">
        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
    </small>

    @if (isset($menu) && $menu->image)
        <div class="current-image">
            <p>Foto saat ini:</p>

            <img
                src="{{ asset('storage/' . $menu->image) }}"
                width="150"
                alt="Foto {{ $menu->name }}">
        </div>
    @endif

    @error('image')
        <small class="error-text">{{ $message }}</small>
    @enderror

</div>

<div class="form-group checkbox-group">

    <label>
        <input
            type="checkbox"
            name="is_available"
            value="1"
            @checked(old('is_available', $menu->is_available ?? true))>

        Menu tersedia untuk dipesan
    </label>

</div>