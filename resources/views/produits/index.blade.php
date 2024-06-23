@extends('layouts.layout')
@section('content')
    <h1 class="title mb-4">Liste des Produits</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('produits.create') }}" class="btn btn-success mb-4">Ajouter un produit</a>
    </div>
    <div class="alert d-none" id="message" role="alert"></div>
    <div id="produits-container">
        @include('produits.liste', ['produits' => $produits])

    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchProduits(page);
        });

        function fetchProduits(page) {
            $.ajax({
                url: "/produits?page=" + page,
                success: function(data) {
                    $('#produits-container').html(data);
                }
            });
        }
    </script>
@endpush
