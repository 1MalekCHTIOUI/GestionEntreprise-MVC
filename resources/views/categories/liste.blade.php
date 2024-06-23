<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">Parent</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $cat)
                <tr id="{{ $cat->id }}">

                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>
                        {{ $cat->titreCateg }}
                    </td>
                    <td>
                        {{ $cat->descriptionCateg }}
                    </td>
                    <td>
                        {{ optional($cat->parent)->titreCateg }}
                    </td>


                    {{-- @if ($cat->sousCategories->isNotEmpty())
                    <td>
                        <ul>
                            @foreach ($cat->sousCategories as $subcategory)
                                <li><a href="#"
                                        onclick="alert(`{{ 'Titre categorie: ' . $subcategory->titreCateg }}\n{{ 'Description categorie: ' . $subcategory->descriptionCateg }}`)">
                                        {{ $subcategory->titreCateg }}
                                    </a></li>
                            @endforeach
                        </ul>
                    </td>
                @else
                    <td class="text-center">--</td>
                @endif --}}

                    <td class="d-flex justify-content-end">
                        {{-- <a href="{{ route('categories.show', $categories->id) }}">View</a> --}}
                        <form id="deleteForm">
                            @csrf
                            <a class="btn btn-secondary" href="{{ route('categories.edit', $cat->id) }}">Edit</a>
                            {{-- <input type="hidden" name="id" id="{{ $cat->id }}" value="{{ $cat->id }}"> --}}
                            <button type="submit" class="btn btn-danger"
                                onclick="deleteCateg(event, {{ $cat }})">Supprimer</button>

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="d-flex justify-content-center">
    {{ $categories->links() }}
</div>


@push('scripts')
    <script></script>
@endpush
