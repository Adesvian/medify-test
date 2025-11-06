<form method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method == 'edit')
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
        </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
    </div>

    <div class="form-group">
        <label>Kategori <small class="text-muted">(Bisa pilih lebih dari satu)</small></label>
        <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
            @if ($kategoriItems->isEmpty())
                <p class="text-muted mb-0">Belum ada kategori tersedia</p>
            @else
                <div class="row">
                    @foreach ($kategoriItems as $kategori)
                        <div class="col-md-6 col-lg-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="kategori_ids[]"
                                    value="{{ $kategori->id }}" id="kategori_{{ $kategori->id }}"
                                    {{ isset($item) && in_array($kategori->id, $selectedCategories) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kategori_{{ $kategori->id }}">
                                    {{ $kategori->nama }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <small class="form-text text-muted">Centang satu atau lebih kategori untuk item ini</small>
    </div>

    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option value="">--Pilih--</option>
            <option value="Tokopaedi" {{ optional($item)->supplier == 'Tokopaedi' ? 'selected' : '' }}>Tokopaedi
            </option>
            <option value="Bukulapuk" {{ optional($item)->supplier == 'Bukulapuk' ? 'selected' : '' }}>Bukulapuk
            </option>
            <option value="TokoBagas" {{ optional($item)->supplier == 'TokoBagas' ? 'selected' : '' }}>TokoBagas
            </option>
            <option value="E Commurz" {{ optional($item)->supplier == 'E Commurz' ? 'selected' : '' }}>E Commurz
            </option>
            <option value="Blublu" {{ optional($item)->supplier == 'Blublu' ? 'selected' : '' }}>Blublu</option>
        </select>
    </div>

    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option value="">--Pilih--</option>
            <option value="Obat" {{ optional($item)->jenis == 'Obat' ? 'selected' : '' }}>Obat</option>
            <option value="Alkes" {{ optional($item)->jenis == 'Alkes' ? 'selected' : '' }}>Alkes</option>
            <option value="Matkes" {{ optional($item)->jenis == 'Matkes' ? 'selected' : '' }}>Matkes</option>
            <option value="Umum" {{ optional($item)->jenis == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="ATK" {{ optional($item)->jenis == 'ATK' ? 'selected' : '' }}>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Foto Product</label>
        <input type="file" class="form-control" name="foto_product" accept="image/*" id="foto_product">
    </div>

    <div class="preview">
        <img id="preview" src="{{ optional($item)->foto_product ? asset('storage/' . $item->foto_product) : '' }}"
            alt="Preview"
            style="max-width:200px; margin-top:10px; {{ optional($item)->foto_product ? 'display:block;' : 'display:none;' }}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>

<script>
    const foto_product = document.getElementById('foto_product');
    const preview = document.getElementById('preview');

    foto_product.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener('load', function() {
                preview.src = this.result;
                preview.style.display = 'block';
            });
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.style.display = 'none';
        }
    });
</script>
