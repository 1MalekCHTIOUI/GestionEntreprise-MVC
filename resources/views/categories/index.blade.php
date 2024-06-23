@extends('layouts.layout')
@section('content')
    <h1 class="title mb-4">Liste des categories</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('categories.create') }}" class="btn btn-success mb-4">Ajouter une catégorie</a>
    </div>
    <div class="alert d-none" id="message" role="alert"></div>
    <div id="categories-container">
        @include('categories.liste', ['categories' => $categories])
    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchCategories(page);
        });

        function fetchCategories(page) {
            $.ajax({
                url: "/categories?page=" + page,
                success: function(data) {
                    $('#categories-container').html(data);
                }
            });
        }
    </script>
@endpush
