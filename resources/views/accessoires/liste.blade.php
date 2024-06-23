<div class="row">
    <div class="row-md-5">

        <div class="alert d-none" id="message" role="alert"></div>
    </div>
    @foreach ($accessoires as $acc)
        <div class="col-sm-12 col-md-4 d-flex justify-content-center mb-3" id="{{ $acc->id }}">
            <div class="card" style="width: 18rem;">
                <img src="{{ asset($acc->image) }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">{{ $acc->titre }}</h5>

                        @if ($acc->active)
                            <span class="badge bg-danger align-self-center">Non disponible</span>
                        @else
                            <span class="badge bg-success align-self-center">Disponible</span>
                        @endif
                    </div>



                    <p class="card-text">{{ $acc->description }}</p>
                    <p class="card-text"><strong>Prix d'achat: </strong> {{ $acc->prixAchat }}</p>
                    <p class="card-text"><strong>Prix de vente: </strong>{{ $acc->prixVente }}</p>
                    <p class="card-text"><strong>Quantite: </strong>{{ $acc->qte }}</p>

                    <a href="{{ route('accessoires.edit', $acc->id) }}" class="btn btn-secondary"><i
                            class="bi bi-pencil"></i></a>
                    <a href="#" class="btn btn-danger" onclick="deleteAcc(event, {{ $acc->id }})"><i
                            class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    @endforeach

</div>
<div class="d-flex justify-content-center">
    {{ $accessoires->links() }}
</div>


@push('scripts')
    <script>
        function deleteAcc(event, id) {
            event.preventDefault();
            if (confirm('Voulez-vous vraiment supprimer cet accessoire?')) {
                $.ajax({
                    url: '/api/accessoires/delete/' + id,
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
                    },
                    error: function(response) {
                        console.log(response);
                        $('#message').removeClass('d-none');
                        $('#message').addClass('alert-danger').html('Erreur lors de la suppression');
                        setTimeout(function() {
                            $('#message').addClass('d-none');
                        }, 3000);
                    }
                });
            }
        }
    </script>
@endpush
