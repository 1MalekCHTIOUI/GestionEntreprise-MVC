@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Ajouter un Accessoire</strong> </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>
                        <form id="ajout-accessoire-form" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="titre">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre"
                                    placeholder="Entrer titre accessoire">
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter description accessoire"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="prixAchat">Prix d'achat</label>
                                <input type="number" class="form-control" id="prixAchat" name="prixAchat"
                                    placeholder="Enter prix d'achat">
                            </div>

                            <div class="mb-3">
                                <label for="prixVente">Prix de vente</label>
                                <input type="number" class="form-control" id="prixVente" name="prixVente"
                                    placeholder="Enter Prix de vente">
                            </div>

                            <div class="mb-3">
                                <label for="qte">Quantite</label>
                                <input type="number" class="form-control" id="qte" name="qte"
                                    placeholder="Enter quantité">

                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input class="form-control" type="file" id="image" name="image"
                                    placeholder="Enter l'image principale de l'accessoire">
                            </div>
                            <div class="mb-3">
                                <label for="active">Active</label>
                                <select class="form-control" id="active" name="active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#ajout-accessoire-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '/api/accessoires/create',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        $('#message').removeClass('d-none');
                        $('#message').addClass('alert-success').html(data.message);
                        setTimeout(function() {
                            $('#message').addClass('d-none');
                        }, 3000);
                        $('#ajout-accessoire-form').trigger('reset');
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                        $('#message').removeClass('d-none');
                        $('#message').addClass('alert-danger').html(data.responseJSON.message);
                        setTimeout(function() {
                            $('#message').addClass('d-none');
                        }, 3000);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        });
    </script>
@endpush
