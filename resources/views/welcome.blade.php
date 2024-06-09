<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
        <!-- Styles -->
         <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
         <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
         {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
         <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
         <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
         <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
         <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    </head>
    <body class="antialiased">
        <button class="addFormButton">Add New</button>
        <button class="bulkDelete">Bulk Delete</button>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">Laravel Ajax Form</div>
                        <div class="card-body">
                            <form id="ajaxForm" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="contact_number">Contact Number:</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number">
                                </div>
                                <div class="form-group">
                                    <label for="hobby">Hobby:</label>
                                    <input type="text" class="form-control" id="hobby" name="hobby">
                                </div>
                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <input type="text" class="form-control" id="category" name="category">
                                </div>
                                <div class="form-group">
                                    <label for="profile_picture">Profile Picture:</label>
                                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <script>
    $(document).ready(function() {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

        $('.addFormButton').click(function(){
            $('.dataTables_wrapper').hide();
        });

        $('#ajaxForm').on('submit', function(e) {
        e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('form.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    alert('Form submitted successfully!');
                },
                error: function(response) {
                    console.log(response);
                    alert('Error submitting form.');
                }
            });
    });
        var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('loadData') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                    ]
                });
            });
    </script>
</html>
