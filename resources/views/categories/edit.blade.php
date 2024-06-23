@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Modifier un Categorie</strong> </div>

                    <div class="card-body">

                        <div class="alert d-none" id="message" role="alert"></div>


                        <form id="edit-category-form">
                            @csrf
                            <div class="mb-3">
                                <label for="titre">Category Title</label>
                                <input type="text" class="form-control" id="titre" name="titre"
                                    value="{{ $category->titreCateg }}" placeholder="Enter category title">
                            </div>
                            <div class="mb-3">
                                <label for="description">Category Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter category description">{{ $category->titreCateg }}</textarea>
                            </div>

                            <hr class="bg-secondary border-2 border-top mb-3 border-secondary"
                                style="width: 70%;margin: 0 auto" />
                            <div class="d-flex align-items-center flex-row gap-2 mb-2">
                                <p class="text-muted"><strong>Parent: </strong></p>
                                {{-- <button type="button" class="btn btn-success btn-sm" id="add-subcategory">+</button> --}}
                                <div class="mb-3">

                                    {{-- <select class="form-control" name="sc" id="add-subcategory"> --}}
                                    <select class="form-control" name="sc" id="categorie">
                                        <option value="">Choisir un Parent</option>
                                        @foreach ($parents as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ $cat->id == $category->idParentCateg ? 'selected' : '' }}>
                                                {{ $cat->titreCateg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div id="subcategories">

                                    @foreach ($category->sousCategories as $sc)
                                        <div class="mb-3">

                                            <div class="input-group">
                                                <input type="text" hidden name="id" value="{{ $sc->id }}">
                                                <div class="input-group-prepend " style="width: 30%">
                                                    <input disabled type="text" name="titreCateg"
                                                        class="form-control input-group-text" value="{{ $sc->titreCateg }}">
                                                </div>
                                                <input disabled type="text" name="descriptionCateg"
                                                    class="form-control input-group-text"
                                                    value="{{ $sc->descriptionCateg }}">
                                                <button type="button" class="btn btn-danger remove-subcategory">X</button>
                                            </div>
                                        </div>
                                        @if (!$loop->last)
                                            <hr class="bg-secondary border-2 border-top mb-3 border-secondary"
                                                style="width: 50%;margin: 0 auto" />
                                        @endif
                                    @endforeach
                                </div> --}}

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
        let subs = [];
        var id = {{ $category->id }};
        $(document).ready(function() {
            $('#edit-category-form').submit(function(e) {
                e.preventDefault();
                var titre = $('#titre').val();
                var description = $('#description').val();
                var categorie = $('#categorie').val();
                // const children = $('#subcategories').children();

                // children.each(function(index, element) {
                //     let inputElements = $(element).find('input');
                //     let subcategory = {};

                //     inputElements.each(function() {
                //         let name = $(this).attr('name');
                //         let value = $(this).val();

                //         if (name && value) {
                //             subcategory[name] = value;
                //         }
                //     });

                //     if (!$.isEmptyObject(subcategory)) {
                //         subs.push(subcategory);
                //     }
                // });

                $.ajax({
                    url: "/api/categories/edit/" + id,
                    type: "PUT",
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
                            $('#message').text('Categorie modifier avec succès');
                            window.location.href = "/categories";
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('#message').removeClass('d-none').addClass('alert-danger')
                            .fadeIn();
                        $('#message').text('Categorie non modifier');
                    }
                });


            });
        });
        // var addedSubs = [];
        // $('#add-subcategory').change(function() {
        //     var selectedSubcategory = JSON.parse($(this).val());

        //     if (addedSubs.find(u => u.id == selectedSubcategory.id)) {
        //         return;
        //     }
        //     addedSubs.push(selectedSubcategory);
        //     $('#subcategories').prepend(
        //         `<div class="mb-3">
    //             <div class="input-group">
    //                 <input type="text" hidden name="id" value="${selectedSubcategory.id}">

    //                 <div class="input-group-prepend " style="width: 30%">
    //                     <input disabled type="text" class="form-control input-group-text" name="titreCateg" value="${selectedSubcategory.titreCateg}">
    //                 </div>
    //                 <input disabled type="text" class="form-control input-group-text" name="descriptionCateg" value="${selectedSubcategory.descriptionCateg}">
    //                 <button type="button" class="btn btn-danger remove-subcategory">X</button>

    //             </div>
    //             <hr class="bg-secondary border-2 border-top mb-3 mt-3 border-secondary" style="width: 50%;margin: 0 auto" />
    //         </div>


    //         `
        //     );
        // });

        // $('#subcategories').on('click', '.remove-subcategory', function() {
        //     $(this).parent().parent().remove();

        // });
    </script>
@endpush
