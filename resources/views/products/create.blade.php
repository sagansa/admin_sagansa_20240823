@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Produk Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Info Dasar --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Varian --}}
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <div class="mb-2 form-check">
                                <input type="checkbox" class="form-check-input" id="has_variants" name="has_variants"
                                    value="1" {{ old('has_variants') ? 'checked' : '' }}>
                                <label class="form-check-label">Produk memiliki varian</label>
                            </div>
                        </div>
                    </div>

                    <div id="variants_container" style="{{ old('has_variants') ? '' : 'display: none;' }}">
                        <div class="mb-3 row">
                            <div class="col-md-12">
                                <h5>Varian Produk</h5>
                                <div id="variant_list">
                                    {{-- Variant items will be added here --}}
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" id="add_variant">
                                    + Tambah Varian
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <h5>Harga per Toko</h5>
                            @foreach ($stores as $store)
                                <div class="mb-2 form-group">
                                    <label>{{ $store->name }}</label>
                                    <input type="number" name="prices[{{ $store->id }}]"
                                        class="form-control @error('prices.' . $store->id) is-invalid @enderror"
                                        value="{{ old('prices.' . $store->id) }}" step="0.01">
                                    @error('prices.' . $store->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle variants container
                const hasVariantsCheckbox = document.getElementById('has_variants');
                const variantsContainer = document.getElementById('variants_container');

                hasVariantsCheckbox.addEventListener('change', function() {
                    variantsContainer.style.display = this.checked ? 'block' : 'none';
                });

                // Add variant
                const addVariantBtn = document.getElementById('add_variant');
                const variantList = document.getElementById('variant_list');
                let variantCount = 0;

                addVariantBtn.addEventListener('click', function() {
                    const variantItem = document.createElement('div');
                    variantItem.className = 'variant-item border p-3 mb-2';
                    variantItem.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Tipe Varian</label>
                        <select name="variants[${variantCount}][type_id]" class="form-control" required>
                            <option value="">Pilih Tipe</option>
                            @foreach ($variantTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Nilai Varian</label>
                        <select name="variants[${variantCount}][value_id]" class="form-control" required>
                            <option value="">Pilih Nilai</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                        Hapus
                    </button>
                </div>
            </div>

            <div class="mt-2 row">
                <div class="col-md-12">
                    <h6>Harga per Toko</h6>
                    @foreach ($stores as $store)
                    <div class="mb-2 form-group">
                        <label>{{ $store->name }}</label>
                        <input type="number"
                            name="variants[${variantCount}][prices][{{ $store->id }}]"
                            class="form-control"
                            step="0.01">
                    </div>
                    @endforeach
                </div>
            </div>
        `;

                    variantList.appendChild(variantItem);
                    variantCount++;

                    // Handle remove variant
                    variantItem.querySelector('.remove-variant').addEventListener('click', function() {
                        variantItem.remove();
                    });

                    // Handle variant type change
                    const typeSelect = variantItem.querySelector('select[name$="[type_id]"]');
                    const valueSelect = variantItem.querySelector('select[name$="[value_id]"]');

                    typeSelect.addEventListener('change', function() {
                        const typeId = this.value;
                        const values = @json($variantTypes->pluck('values', 'id'));

                        valueSelect.innerHTML = '<option value="">Pilih Nilai</option>';

                        if (typeId && values[typeId]) {
                            values[typeId].forEach(value => {
                                valueSelect.innerHTML += `
                        <option value="${value.id}">${value.name}</option>
                    `;
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
