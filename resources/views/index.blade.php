<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sample Laravel</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('offcanvas.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap5.min.css">


</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sample Aplication</a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<main class="container">
    <div class="card mt-4">
        <div class="card-body">
            <form id="form-search">
                <div class="row">
                    <div class="col-lg-3">
                        <input type="text" class="form-control" placeholder="User ID" id="input-user-id">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" placeholder="Nama" id="input-nama">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" placeholder="HP" id="input-hp">
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-primary btn-block w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h4>Data User</h4>
                </div>
                <div class="col-2 mr-auto offset-4">
                    <a href="{{ route('add') }}" class="btn btn-primary btn-block w-100">Tambah Data</a>
                </div>
            </div>
        </div>
        <table class="table" id="user-table">
            <thead>
            <tr class="fw-bold fs-6 text-muted bg-light align-middle">
                <th>ID</th>
                <th>Nama</th>
                <th>Hp</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('offcanvas.js')  }}"></script>

<script>
    $('#form-search').submit(function (e) {
        e.preventDefault();
        $table.ajax.reload()
    })

    const $table = $('#user-table').DataTable({
        searching: false,
        info: false,
        bLengthChange: false,
        ajax: {
            url: '{{ route('api.get_data') }}',
            type: "GET",
            data: function (params) {
                params.id = $('#input-user-id').val()
                params.nama = $('#input-nama').val()
                params.hp = $('#input-hp').val()
            }
        },
        columns: [
            {
                name: 'id',
                data: 'id'
            },
            {
                name: 'nama',
                data: 'nama'
            },
            {
                name: 'hp',
                data: 'hp'
            },
            {
                data: null,
                name: '',
                render: function (data) {
                    let url = '{{ route('edit', ['id' => ':id']) }}'
                    url = url.replace(':id', data.id);
                    return `
                        <a class="btn btn-secondary text-white" href="${url}"> Edit Data </a>
                        <button class="btn btn-danger" onclick="deleteData(event, ${data.id})"> Hapus Data </button>
                    `
                }
            }
        ]
    });

    function deleteData(e, id) {
        e.preventDefault()
        swal({
            title: "Perhatian",
            text: "Apakah anda yakin untuk hapus data ini?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    let deleteUrl = '{{ route("api.delete_data") }}';
                    const payload = new FormData;
                    payload.append('_method', 'DELETE');
                    payload.append('id', id);

                    $.ajax({
                        data: payload,
                        url: deleteUrl,
                        method: 'POST',
                        contentType: false,
                        processData: false,
                    })
                        .done(() => {
                            swal("Data Berhasil Dihapus")
                                .then(() => {
                                    $table.ajax.reload()
                                })
                        })
                        .fail(err => {
                            swal({
                                text: err?.responseJSON?.message || 'Data Gagal Dihapus'
                            })
                        })
                }
            });
    }
</script>

</body>
</html>
