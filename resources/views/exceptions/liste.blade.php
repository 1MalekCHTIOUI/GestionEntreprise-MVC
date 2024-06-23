<div class="table-responsive">
    <table class="table " id="exceptions-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Message</th>
                <th scope="col">Level</th>
                <th scope="col">Context</th>
                <th scope="col">Date</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($exceptions as $exc)
                <tr id="{{ $exc->id }}">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td><a href="#" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $exc->id }}">{{ \Illuminate\Support\Str::limit($exc->message, 50) }}</a>
                    </td>
                    <td>{{ $exc->level }}</td>
                    <td>
                        <ul>
                            @foreach (json_decode($exc->context, true) as $key => $value)
                                <li>{{ ucfirst($key) }}: {{ ucfirst($value) }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($exc->created_at)->format('d/m/Y H:i') }}</td>

                </tr>
                <div class="modal fade" id="exampleModal-{{ $exc->id }}" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {!! $exc->message !!}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {!! $exceptions->links() !!}
</div>

@push('scripts')
    <script></script>
@endpush
