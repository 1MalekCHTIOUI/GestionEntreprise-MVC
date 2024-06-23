@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Modifier un Accessoire</strong> </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>
                        <form id="edit-accessoire-form" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="titre">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre"
                                    value="{{ $accessoire->titre }}" placeholder="Entrer titre accessoire">
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter description accessoire">{{ $accessoire->description }}"</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="prixAchat">Prix d'achat</label>
                                <input type="number" class="form-control" id="prixAchat" name="prixAchat"
                                    value="{{ $accessoire->prixAchat }}" placeholder="Enter prix d'achat">
                            </div>

                            <div class="mb-3">
                                <label for="prixVente">Prix de vente</label>
                                <input type="number" class="form-control" id="prixVente" name="prixVente"
                                    value="{{ $accessoire->prixVente }}" placeholder="Enter Prix de vente">
                            </div>

                            <div class="mb-3">
                                <label for="qte">Quantite</label>
                                <input type="number" class="form-control" id="qte" name="qte"
                                    value="{{ $accessoire->qte }}" placeholder="Enter quantité">

                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input class="form-control" type="file" id="image" name="image"
                                    onchange="previewImage(event)">
                                <img id="current-image" src="{{ asset($accessoire->image) }}" class="rounded"
                                    alt="Current Image" style="max-width: 50%; max-height: 50%;">


                            </div>
                            <div class="mb-3">
                                <label for="active">Active</label>
                                <select class="form-control" id="active" name="active">
                                    <option value="1" selected="{{ !$accessoire->active }}">Yes</option>
                                    <option value="0" selected="{{ $accessoire->active }}">No</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var img = document.getElementById('current-image');
                img.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
        $(document).ready(function() {
            $('#edit-accessoire-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('_method', 'PUT');
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }


                $.ajax({
                    url: '/api/accessoires/edit/' + {{ $accessoire->id }},
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        $('#message').removeClass('d-none');
                        $('#message').addClass('alert-success').html(data.message);
                        setTimeout(function() {
                            $('#message').addClass('d-none');
                        }, 3000);
                    },
                    error: function(error) {
                        console.log(error);
                        $('#message').removeClass('d-none');

                        $('#message').addClass('alert-danger').html(error.responseJSON.message);
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
