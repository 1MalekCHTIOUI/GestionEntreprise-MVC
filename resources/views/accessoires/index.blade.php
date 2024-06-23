@extends('layouts.layout')
@section('content')
    <h1 class="title mb-4">Liste des accessoires</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('accessoires.create') }}" class="btn btn-success mb-4">Ajouter un accessoire</a>
    </div>
    <div class="alert d-none" id="message" role="alert"></div>
    <div id="accessoires-container">
        @include('accessoires.liste', ['accessoires' => $accessoires])

    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchAccessoires(page);
        });

        function fetchAccessoires(page) {
            $.ajax({
                url: "/accessoires?page=" + page,
                success: function(data) {
                    $('#accessoires-container').html(data);
                }
            });
        }
    </script>
@endpush
