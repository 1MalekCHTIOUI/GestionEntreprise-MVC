@extends('layouts.layout')
@section('content')
    <div class="">
        <div class="row justify-content-center" style="width:100%">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><strong>Modifier le Produit: </strong>{{ $produit->titre }} </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>


                        <form id="edit-produit-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="ref">Titre</label>
                                        <input type="text" class="form-control" id="titre" name="titre"
                                            value="{{ $produit->titre }}" placeholder="Entrer Titre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ref">Référence</label>
                                        <input type="text" class="form-control" id="ref" name="ref"
                                            value="{{ $produit->ref }}" placeholder="Entrer la référence">
                                    </div>
                                    <div class="mb-3">
                                        <label for="prixCharge">Prix de charge</label>
                                        <input type="number" class="form-control" id="prixCharge" name="prixCharge"
                                            value="{{ $produit->prixCharge }}" placeholder="Entrer le prix de charge">
                                    </div>
                                    <div class="mb-3">
                                        <label for="prixVente">Prix de vente</label>
                                        <input type="number" class="form-control" id="prixVente" name="prixVente"
                                            value="{{ $produit->prixVente }}" placeholder="Entrer le prix de vente">
                                    </div>
                                    <div class="mb-3">
                                        <label for="qte">Quantité</label>
                                        <input type="number" class="form-control" id="qte" name="qte"
                                            value="{{ $produit->qte }}" placeholder="Entrer la quantité">
                                    </div>
                                    <div class="mb-3">
                                        <label for="qteMinGros">Quantité minimum pour gros</label>
                                        <input type="number" class="form-control" id="qteMinGros" name="qteMinGros"
                                            value="{{ $produit->qteMinGros }}"
                                            placeholder="Entrer la quantité minimum pour gros">
                                    </div>
                                    <div class="mb-3">
                                        <label for="prixGros">Prix de gros</label>
                                        <input type="number" class="form-control" id="prixGros" name="prixGros"
                                            value="{{ $produit->prixGros }}" placeholder="Entrer le prix de gros">
                                    </div>
                                    <div class="mb-3">
                                        <label for="promo">Promotion</label>
                                        <input type="number" class="form-control" id="promo" name="promo"
                                            value="{{ $produit->promo }}" placeholder="Entrer la promotion">
                                    </div>
                                    <div class="mb-3">
                                        <label for="longueur">Longueur</label>
                                        <input type="number" class="form-control" id="longueur" name="longueur"
                                            value="{{ $produit->longueur }}" placeholder="Entrer la longueur">
                                    </div>
                                    <div class="mb-3">
                                        <label for="largeur">Largeur</label>
                                        <input type="number" class="form-control" id="largeur" name="largeur"
                                            value="{{ $produit->largeur }}" placeholder="Entrer la largeur">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hauteur">Hauteur</label>
                                        <input type="number" class="form-control" id="hauteur" name="hauteur"
                                            value="{{ $produit->hauteur }}" placeholder="Entrer la hauteur">
                                    </div>
                                    <div class="mb-3">
                                        <label for="profondeur">Profondeur</label>
                                        <input type="number" class="form-control" id="profondeur" name="profondeur"
                                            value="{{ $produit->profondeur }}" placeholder="Entrer la profondeur">
                                    </div>

                                </div>
                                <div class="col-md-7">


                                    <div class="mb-3">
                                        <label for="tempsProduction">Temps de production</label>
                                        <input type="number" class="form-control" id="tempsProduction"
                                            value="{{ $produit->tempsProduction }}" name="tempsProduction"
                                            placeholder="Entrer le temps de production(H)">
                                    </div>
                                    <div class="mb-3">
                                        <label for="matiers">Matériaux</label>
                                        <input type="text" class="form-control" id="matiers" name="matiers"
                                            value="{{ $produit->matiers }}" placeholder="Entrer les matériaux">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description">Description</label>

                                        <textarea class="ckeditor form-control" id="description"></textarea>

                                    </div>
                                    <div class="mb-3">
                                        <label for="descriptionTechnique">Description technique</label>

                                        <textarea class="ckeditor form-control" id="descriptionTechnique"></textarea>

                                    </div>
                                    <div class="mb-3">
                                        <label for="ficheTechnique">Fiche technique</label>
                                        <input type="file" class="form-control" name="ficheTechnique" id="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="publicationSocial">Publication sur les réseaux sociaux</label>

                                        <textarea class="ckeditor form-control" id="publicationSocial"></textarea>

                                    </div>
                                    <div class="mb-3">
                                        <label for="fraisTransport">Frais de transport</label>
                                        <input type="number" class="form-control" id="fraisTransport"
                                            value="{{ $produit->fraisTransport }}" name="fraisTransport"
                                            placeholder="Entrer les frais de transport">
                                    </div>
                                    <div class="mb-3">
                                        <label for="idCategorie">Catégorie</label>
                                        <select class="form-control" name="idCategorie" id="idCategorie">
                                            <option value="">Choisir une catégorie</option>
                                            @foreach ($categories as $categorie)
                                                <option value="{{ $categorie->id }}"
                                                    selected={{ $produit->categories->id == $categorie->id }}>
                                                    {{ $categorie->titreCateg }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="imagePrincipale">Image principale</label>
                                        <input type="file" class="form-control" id="imagePrincipale"
                                            name="imagePrincipale" onchange="previewImage(event)">
                                        <img id="current-image" src="{{ asset($produit->imagePrincipale) }}"
                                            class="rounded" alt="Current Image" style="max-width: 50%; max-height: 50%;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="active">Actif</label>
                                        <select class="form-control" name="active" id="active">
                                            <option value="1" selected={{ $produit->active == true }}>Oui</option>
                                            <option value="0" selected={{ $produit->active == false }}>Non</option>
                                        </select>
                                    </div>
                                    <div class="container mt-5">
                                        <div class="row">
                                            <div class="col-12 ">

                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="$('#imageInput').click()">Selectionner des images pour ce
                                                    produit:</button>
                                                <input type="file" id="imageInput" accept="image/*" multiple
                                                    style="display: none;">
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <ul class="list-inline" id="imageList">
                                                    @foreach ($produit->images as $image)
                                                        <li class="list-inline-item">
                                                            <img src="{{ asset($image->titreImg) }}"
                                                                class="img-thumbnail"
                                                                style="max-width: 100px; max-height: 100px; margin: 5px;">
                                                            <i class="bi bi-trash" onclick="removeImage(this)"></i>
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image->id }}">
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-row gap-2 mb-3">
                                        <p class="text-muted"><strong>Accessoires: </strong></p>
                                        {{-- <button type="button" class="btn btn-success btn-sm" id="add-subcategory">+</button> --}}
                                        <div class="mb-3">

                                            <select class="form-control" name="accessoires" id="add-accessoire">
                                                <option value="">Choisir un accessoire</option>
                                                @foreach ($accessoires as $acc)
                                                    <option value="{{ $acc }}">{{ $acc->titre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="accessoires">
                                        @foreach ($produit->accessoires as $acc)
                                            <div class="mb-3">
                                                <div class="input-group" id="{{ $acc->id }}">
                                                    <input type="text" hidden name="id"
                                                        value="{{ $acc->id }}">
                                                    <input disabled type="text" class="form-control input-group-text"
                                                        name="titreCateg" value="{{ $acc->titre }}">
                                                    <div class="input-group-prepend d-flex flex-row">
                                                        <input type="number" class="form-control input-group-text"
                                                            name="quantite" value="{{ $acc->pivot->qte }}"
                                                            placeholder="Entrer la quantité">
                                                        <input type="text" class="form-control text-center"
                                                            style="width:30%" disabled value=" / {{ $acc->qte }}">
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-danger remove-subcategory">X</button>

                                                </div>
                                                <hr class="bg-secondary border-2 border-top mb-3 mt-3 border-secondary"
                                                    style="width: 50%;margin: 0 auto" />
                                            </div>
                                        @endforeach

                                    </div>
                                </div>


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
        console.log("aa");
        let accs = [];
        let description;
        let descriptionTechnique;
        let publicationSocial;

        function removeImage(icon) {
            // Remove the parent .image-container element
            $(icon).parent().remove();


        }
        $(document).ready(function() {

            $('#imageInput').on('change', function(event) {
                const imageList = $('#imageList');
                const files = event.target.files;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail')
                            .css({
                                'max-width': '100px',
                                'max-height': '100px',
                                'margin': '5px'
                            });
                        const i = $('<i>').addClass('bi bi-trash').click(function() {
                            $(this).parent().remove();
                        });
                        const li = $('<li>').addClass('list-inline-item').append(img).append(i);
                        imageList.append(li);
                    }
                    reader.readAsDataURL(file);
                }
            });

            ClassicEditor
                .create(document.querySelector('#description'))
                .then(editor => {
                    editor.setData(@json($produit->description));
                    description = editor;
                })
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#descriptionTechnique'))
                .then(editor => {
                    editor.setData(@json($produit->descriptionTechnique));
                    descriptionTechnique = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#publicationSocial'))
                .then(editor => {
                    editor.setData(@json($produit->publicationSocial));
                    publicationSocial = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            var addedAccs = [];
            $('#add-accessoire').change(function() {
                var selectedAcc = JSON.parse($(this).val());

                if (addedAccs.find(u => u.id == selectedAcc.id)) {
                    return;
                }
                addedAccs.push(selectedAcc);
                $('#accessoires').prepend(
                    `<div class="mb-3">
                        <div class="input-group" id="${selectedAcc.id}">
                            <input type="text" hidden name="id" value="${selectedAcc.id}">
                            <input disabled type="text" class="form-control input-group-text" name="titreCateg" value="${selectedAcc.titre}">
                            <div class="input-group-prepend d-flex flex-row">
                                <input type="number" class="form-control input-group-text" name="quantite" placeholder="Entrer la quantité" >
                                <input type="text" class="form-control text-center" style="width:30%" disabled value=" / ${selectedAcc.qte}">
                            </div>
                        
                            <button type="button" class="btn btn-danger remove-subcategory">X</button>

                        </div>
                        <hr class="bg-secondary border-2 border-top mb-3 mt-3 border-secondary" style="width: 50%;margin: 0 auto" />
                    </div>
                `
                );
            });

            $('#accessoires').on('click', '.remove-subcategory', function() {
                $(this).parent().parent().remove();

            });
        });

        $('#edit-produit-form').on('submit', function(e) {
            e.preventDefault();

            console.log(description.getData());

            var formData = new FormData(this);
            formData.delete('description');
            formData.append('description', description.getData());
            formData.delete('descriptionTechnique');
            formData.append('descriptionTechnique', descriptionTechnique.getData());
            formData.delete('publicationSocial');
            formData.append('publicationSocial', publicationSocial.getData());
            formData.delete('existing_images[]');

            $('input[name="existing_images[]"]').each(function() {
                console.log('Appending value:', $(this).val());
                formData.append('existing_images[]', $(this).val());
            });

            console.log(formData.getAll('existing_images[]'));
            let files = $('#imageInput')[0].files;
            for (let i = 0; i < files.length; i++) {
                console.log(files[i]);
                formData.append('images[]', files[i]);
            }

            let temp = [];
            $('#accessoires div.input-group').each(function() {
                var id = $(this).find('input[name="id"]').val();
                var qte = $(this).find('input[name="quantite"]').val();
                temp.push({
                    "idAccessoire": id,
                    "qte": qte
                });
            });
            console.log(temp);
            formData.delete('accessoires');
            formData.append('accessoires', JSON.stringify(temp));
            formData.append('_method', 'PUT');
            console.log('Files in formData:', formData.getAll('images[]')); // Check the files in formData

            $.ajax({
                url: '/api/produits/edit/' + {{ $produit->id }},
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    window.location.href = "/produits";
                },
                error: function(error) {
                    console.log(error);
                    console.log(error.responseJSON.message);
                    $('#message').removeClass('d-none');
                    $('#message').addClass('alert-danger').html(error.responseJSON.message);
                    setTimeout(function() {
                        $('#message').addClass('d-none');
                    }, 3000);
                }
            });

        });

        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var img = document.getElementById('current-image');
                img.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endpush
