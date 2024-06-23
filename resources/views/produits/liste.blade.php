<div class="container">

    <div class="row d-flex justify-content-center gap-3">
        @foreach ($produits as $prod)
            <div id="{{ $prod->id }}" class="col-sm-12 col-md-3 mb-5 bootstrap snippets bootdeys border p-4">
                <!-- product -->
                <div class="product-content product-wrap clearfix">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="product-image">
                                <img src="{{ asset($prod->imagePrincipale) }}" alt="194x228" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="product-deatil">
                                <h5 class="name">
                                    <a href="#">
                                        {{ $prod->titre }}
                                    </a>
                                </h5>
                                <p class="price-container">
                                    <span>{{ $prod->categories ? $prod->categories->titreCateg : 'No Category' }}</span>
                                </p>
                                <p class="price-container">
                                    <span>$99</span>
                                </p>
                                <span class="tag1"></span>
                            </div>
                            <div class="description">
                                {!! $prod->description !!}
                            </div>
                            <div class="row mt-3 text-center">
                                <div class="col-4 col-md-4">
                                    <a href="javascript:void(0);" onclick="deleteProd(event, {{ $prod->id }})"
                                        class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                                <div class="col-4 col-md-4">
                                    <a href="{{ route('produits.show', $prod->id) }}" class="btn btn-success">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <div class="col-4 col-md-4">
                                    <a href="{{ route('produits.edit', $prod->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end product -->
            </div>
        @endforeach
    </div>

</div>



@push('scripts')
    <script>
        function deleteProd(event, id) {
            event.preventDefault();
            if (confirm('Voulez-vous vraiment supprimer ce produit?')) {
                $.ajax({
                    url: '/api/produits/delete/' + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        $('#message').removeClass('d-none');
                        $('#message').addClass('alert-success').html(response.message);
                        $('#' + id).fadeOut(300, function() {
                            $(this).remove();
                        });
                        setTimeout(function() {
                            $('#message').addClass('d-none');
                        }, 3000);
                    }
                });
            }
        }
    </script>
@endpush
