<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;
    var storageUrl = "{{ asset('storage') }}";

    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [
                [0, 'desc']
            ],
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    function getData() {

        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val()
        var filter_nama = $('#filter-nama').val()
        var filter_harga_min = $('#filter-harga-min').val()
        var filter_harga_max = $('#filter-harga-max').val()
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url('master-items/search') }}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: 'kode=' + filter_kode + '&nama=' + filter_nama + '&hargamin=' + filter_harga_min +
                '&hargamax=' + filter_harga_max,
            success: function(results) {
                var data = results.data

                $.each(data, function(index, item) {
                    var harga_jual = item.harga_beli + item.harga_beli * item.laba / 100;
                    harga_jual = Math.round(harga_jual)
                    var kode = item.kode;

                    var html = `<a href="{{ url('master-items/view/') }}/` + kode +
                        `" class="btn btn-primary">View</a>`

                    var foto = item.foto_product ?
                        `<img src="${storageUrl}/${item.foto_product}" alt="${item.nama}" style="width:50px; height:auto;">` :
                        `-`;

                    var kategori = '-';
                    if (item.kategori && item.kategori.length > 0) {
                        var kategoriNames = item.kategori.map(function(kat) {
                            return kat.nama;
                        });
                        kategori = kategoriNames.join(', ');
                    }

                    var rowData = [
                        item.kode,
                        item.nama,
                        kategori,
                        item.jenis,
                        item.harga_beli,
                        harga_jual,
                        item.supplier,
                        foto,
                        html
                    ];

                    dataTableObj.row.add(rowData).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>
