<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sample Laravel</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('offcanvas.css') }}">


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
    <form id="form-upsert">

        <div class="card mt-4">
            <div class="card-header">
                <h4>Tambah Data User</h4>
            </div>
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <input type="text" class="form-control" placeholder="Nama" name="nama"
                               value="{{ @$user->nama }}" id="input-nama">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <input type="text" class="form-control" placeholder="HP" name="hp" value="{{ @$user->hp }}"
                               id="input-hp">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-3">
                        <a href="{{ route('index') }}" class="btn btn-secondary btn-block w-100">Kembali</a>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-primary btn-block w-100" type="submit">Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('offcanvas.js')  }}"></script>

<script>
    const id = '{{ @$user->id }}'
    $('#form-upsert').submit(function (e) {
        e.preventDefault()
        const payload = new FormData(this)
        let formUrl = ''

        if (id) {
            formUrl = '{{ route("api.update_data", ['id' => ':id']) }}'
            formUrl = formUrl.replace(':id', id)
        } else {
            formUrl = '{{ route("api.save_data") }}'
        }

        $.ajax({
            data: payload,
            url: formUrl,
            method: 'POST',
            contentType: false,
            processData: false,
        })
            .done(() => {
                let message = ''
                if (id) message = 'Data Berhasil Diubah'
                else message = 'Data Berhasil Ditambahkan'
                swal(message)
                    .then(() => {
                        window.location.href = '{{ route('index') }}'
                    })
            })
            .fail(err => {
                let message = ''
                if (id) message = 'Data Gagal Diubah'
                else message = 'Data Gagal Ditambahkan'
                swal({
                    text: err?.responseJSON?.message || message
                })
            })
    })
</script>

</body>
</html>
