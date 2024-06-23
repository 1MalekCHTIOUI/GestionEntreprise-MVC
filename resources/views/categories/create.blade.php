@extends('layouts.layout')
{{-- @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Ajouter un Categorie</strong> </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>


                        <form id="edit-category-form">
                            <div class="mb-3">
                                <label for="titre">Category Title</label>
                                <input type="text" class="form-control" id="titre" name="titre"
                                    placeholder="Enter category title">
                            </div>
                            <div class="mb-3">
                                <label for="description">Category Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    placeholder="Enter category description">
                            </div>

                            <hr class="bg-secondary border-2 border-top mb-3 border-secondary"
                                style="width: 70%;margin: 0 auto" />
                            <div class="d-flex align-items-center flex-row gap-2 mb-2">
                                <p class="text-muted"><strong>Sous categories: </strong></p>
                                {{-- <button type="button" class="btn btn-success btn-sm" id="add-subcategory">+</button> --}}
{{-- <div class="mb-3">

                                    <select class="form-control" name="sc" id="add-subcategory">
                                        <option value="">Choisir un sous categorie</option>
                                        @foreach ($parents as $cat)
                                            <option value="{{ $cat }}">
                                                {{ $cat->titreCateg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="subcategories">


                            </div>

                            <button class="btn btn-success" onclick="onSubmit(event)">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
{{-- @endsection --}}
{{-- 
@push('scripts')
    <script>
        console.log("aa");
        let subs = [];

        onSubmit = (e) => {
            e.preventDefault();
            var titre = $('#titre').val();
            var description = $('#description').val();
            const children = $('#subcategories').children();

            children.each(function(index, element) {
                let inputElements = $(element).find('input');
                let subcategory = {};

                inputElements.each(function() {
                    let name = $(this).attr('name');
                    let value = $(this).val();

                    if (name && value) {
                        subcategory[name] = value;
                    }
                });

                if (!$.isEmptyObject(subcategory)) {
                    subs.push(subcategory);
                }
            });
            console.log(subs);
            $.ajax({
                url: "/api/categories/create",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'titreCateg': titre,
                    'descriptionCateg': description,
                    'sousCategories': subs
                },
                success: function(response) {
                    console.log(response);
                    if (response) {
                        $('#edit-category-form')[0].reset();
                        $('#message').removeClass('d-none').addClass('alert-success')
                            .fadeIn();
                        $('#message').text('Categorie ajoutée avec succès');
                    }
                },
                error: function(error) {
                    console.log(error);
                    $('#message').removeClass('d-none').addClass('alert-danger')
                        .fadeIn();
                    $('#message').text('Categorie non ajoutée');
                }
            });
        }
        $(document).ready(function() {
            var addedSubs = [];
            $('#add-subcategory').change(function() {
                var selectedSubcategory = JSON.parse($(this).val());

                if (addedSubs.find(u => u.id == selectedSubcategory.id)) {
                    return;
                }
                addedSubs.push(selectedSubcategory);
                $('#subcategories').prepend(
                    `<div class="mb-3">
                <div class="input-group">
                    <input type="text" hidden name="id" value="${selectedSubcategory.id}">

                    <div class="input-group-prepend " style="width: 30%">
                        <input disabled type="text" class="form-control input-group-text" name="titreCateg" value="${selectedSubcategory.titreCateg}">
                    </div>
                    <input disabled type="text" class="form-control input-group-text" name="descriptionCateg" value="${selectedSubcategory.descriptionCateg}">
                    <button type="button" class="btn btn-danger remove-subcategory">X</button>

                </div>
                <hr class="bg-secondary border-2 border-top mb-3 mt-3 border-secondary" style="width: 50%;margin: 0 auto" />
            </div>


            `
                );
            });

            $('#subcategories').on('click', '.remove-subcategory', function() {
                $(this).parent().parent().remove();

            });
        });
    </script>
@endpush --}}


















@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Ajouter un Categorie</strong> </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>


                        <form id="edit-category-form">
                            <div class="mb-3">
                                <label for="titre">Category Title</label>
                                <input type="text" class="form-control" id="titre" name="titre"
                                    placeholder="Enter category title">
                            </div>
                            <div class="mb-3">
                                <label for="description">Category Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    placeholder="Enter category description">
                            </div>

                            <hr class="bg-secondary border-2 border-top mb-3 border-secondary"
                                style="width: 70%;margin: 0 auto" />
                            <div class="d-flex align-items-center flex-row gap-2 mb-2">
                                <p class="text-muted"><strong>Categorie parent: </strong></p>
                                {{-- <button type="button" class="btn btn-success btn-sm" id="add-subcategory">+</button> --}}
                                <div class="mb-3">

                                    <select class="form-control" name="sc" id="categorie">
                                        <option value="">Choisir un categorie parent</option>
                                        @foreach ($parents as $cat)
                                            <option value="{{ $cat->id }}">
                                                {{ $cat->titreCateg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <button class="btn btn-success" onclick="onSubmit(event)">Ajouter</button>
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
        let subs = [];

        onSubmit = (e) => {
            e.preventDefault();
            var titre = $('#titre').val();
            var description = $('#description').val();
            var categorie = $('#categorie').val();
            console.log(categorie);
            console.log(subs);
            $.ajax({
                url: "/api/categories/create",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'titreCateg': titre,
                    'descriptionCateg': description,
                    'categorie': categorie
                },
                success: function(response) {
                    console.log(response);
                    if (response) {
                        $('#edit-category-form')[0].reset();
                        $('#message').removeClass('d-none').addClass('alert-success')
                            .fadeIn();
                        $('#message').text('Categorie ajoutée avec succès');
                    }
                },
                error: function(error) {
                    console.log(error);
                    $('#message').removeClass('d-none').addClass('alert-danger')
                        .fadeIn();
                    $('#message').text('Categorie non ajoutée');
                }
            });
        }
    </script>
@endpush
