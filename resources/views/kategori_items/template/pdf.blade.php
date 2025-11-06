<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Kategori - {{ $kategori->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            color: #333;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: middle;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .product-image {
            max-width: 60px;
            max-height: 60px;
            width: auto;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .no-image {
            width: 60px;
            height: 60px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #999;
            text-align: center;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>DETAIL KATEGORI ITEM</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="label">Nama Kategori:</span>
            <span>{{ $kategori->nama }}</span>
        </div>
        <div class="info-row">
            <span class="label">Kode Kategori:</span>
            <span>{{ $kategori->kode }}</span>
        </div>
    </div>

    <h3>Daftar Item dalam Kategori Ini</h3>

    @if ($kategori->master && $kategori->master->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">Foto</th>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 22%;">Nama Item</th>
                    <th style="width: 12%;">Jenis</th>
                    <th style="width: 12%;">Harga Beli</th>
                    <th style="width: 8%;">Laba (%)</th>
                    <th style="width: 12%;">Harga Jual</th>
                    <th style="width: 12%;">Supplier</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategori->master as $index => $item)
                    @php
                        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba) / 100;
                        $hargaJual = round($hargaJual);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            @if ($item->foto_product && file_exists(storage_path('app/public/' . $item->foto_product)))
                                <img src="{{ storage_path('app/public/' . $item->foto_product) }}" class="product-image"
                                    alt="{{ $item->nama }}">
                            @else
                                <div class="no-image">No Image</div>
                            @endif
                        </td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->laba }}%</td>
                        <td class="text-right">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                        <td>{{ $item->supplier }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 15px;">
            <strong>Total Item:</strong> {{ $kategori->master->count() }} item
        </p>
    @else
        <p style="font-style: italic; color: #666;">Tidak ada item yang terdaftar dalam kategori ini.</p>
    @endif

    <div class="footer">
        <p>Dicetak oleh {{ auth()->user()->name }} pada: {{ $tanggal }} pukul {{ $waktu }}</p>
    </div>
</body>

</html>
