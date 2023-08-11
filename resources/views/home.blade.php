<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    {{-- data table --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formData">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDataEditModal">
                        @csrf
                        <input type="hidden" id="idEdit" name="id">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="emailEdit" name="emailEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwordEdit" name="passwordEdit">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>

        <div class="mt-4">
            <table id="table" class="display">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>email</th>
                        <th>password</th>
                        <th>date created</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        $(function() {

            // datatable
            $("#table").DataTable({
                ajax: "{{ route('readFn') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'password'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'action'
                    }
                ]
            })

            // Create
            $("#formData").on("submit", function(e) {

                e.preventDefault();

                const data = $(this).serializeArray();

                $.ajax({
                    url: "{{ route('createFn') }}",
                    method: "post",
                    data: data,
                    success(e) {
                        // console.log(e)

                        $("#table").DataTable().ajax.reload();

                        $("input[name='email']").val("")
                        $("input[name='password']").val("")

                        $("#addModal").modal('hide');

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })

            })

            // delete
            $("tbody").on("click", "#deleteBtn", function() {

                const id = $(this).data('id')

                $.ajax({
                    url: "{{ route('deleteFn') }}",
                    method: "post",
                    data: {
                        id,
                        _token: "{{ csrf_token() }}"
                    },
                    success(e) {
                        console.log(e)

                        $("#table").DataTable().ajax.reload();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Delete success',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    }
                })
            })

            // edit modal
            $("tbody").on("click", "#editbtn ", function() {

                $("#EditModal").modal('show');


                const id = $(this).data('id')

                $.ajax({
                    url: "{{ route('updateDataFn') }}",
                    method: "get",
                    data: {
                        id,
                    },
                    success(e) {
                        console.log(e)

                        // $("#table").DataTable().ajax.reload();

                        // Swal.fire({
                        //     position: 'top-end',
                        //     icon: 'success',
                        //     title: 'Delete success',
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // })
                        $("#idEdit").val(e.id);
                        $("#emailEdit").val(e.email);
                        $("#passwordEdit").val(e.password);

                    }
                })
            })

            // update
            $("#formDataEditModal").on("submit",function(e){

                e.preventDefault()

                const data = $(this).serializeArray()

                $.ajax({
                    url:"{{ route('updateFn') }}",
                    method:"post",
                    data:data,
                    success(e){
                        // console.log(e)
                        $("#table").DataTable().ajax.reload();

                    }
                })
            })
        })
    </script>
</body>

</html>
