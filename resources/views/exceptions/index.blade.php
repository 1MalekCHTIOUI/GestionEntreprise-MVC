@extends('layouts.layout')
@section('content')
    <h1 class="title mb-4">Liste des exceptions</h1>
    <div class="row mb-3">
        <div class="col-md-6">
            <form>
                <div class="input-group">
                    <input type="text" id="search" class="form-control" name="search" placeholder="Search...">
                    <div class="input-group-append">
                        <select id="sort" class="form-control">
                            <option value="asc">Ascending</option>
                            <option value="desc" selected>Descending</option>
                        </select>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="exceptions-container">
        @include('exceptions.liste', ['exceptions' => $exceptions])

    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchEx(page);
        });

        function fetchEx(page) {
            $.ajax({
                url: "/exceptions?page=" + page,
                success: function(data) {
                    $('#exceptions-container').html(data);
                }
            });
        }

        $('#search').on('input', function(e) {
            e.preventDefault();
            let search_string = $('#search').val();
            $.ajax({
                url: "{{ route('exceptions.search') }}",
                method: 'GET',
                data: {
                    search_string: search_string
                },
                success: function(res) {
                    console.log(res);
                    $('#exceptions-table ').html(res);
                    if (res.status == 'nothing found') {
                        $('#exceptions-table ').html('<span class="text-danger">' + 'Nothing Found' +
                            '</span>');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        })
        $('#sort').on('change', function(e) {
            e.preventDefault();

            let sort = $('#sort').val();
            console.log(sort);
            $.ajax({
                url: "{{ route('exceptions.sort') }}",
                method: 'GET',
                data: {
                    sort: sort
                },
                success: function(res) {

                    $('#exceptions-table ').html(res);
                    if (res.status == 'nothing found') {
                        $('#exceptions-table ').html('<span class="text-danger">' + 'Nothing Found' +
                            '</span>');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        })
    </script>
@endpush
