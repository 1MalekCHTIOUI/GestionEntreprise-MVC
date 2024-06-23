<div class="table-responsive">
    <table class="table" id="history-table">

        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Table</th>
                <th scope="col">idRecord</th>
                <th scope="col">action</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historiques as $hs)
                <tr id="{{ $hs->id }}">

                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>
                        {{ $hs->table }}
                    </td>
                    <td>
                        {{ $hs->id_record }}
                    </td>
                    <td>
                        {{ $hs->action }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($hs->created_at)->format('d/m/Y H:i') }}</td>

                    <td><a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal"
                            data-bs-target="#detailsModal-{{ $hs->id }}">
                            <i class="bi bi-journal-text text-white"></i>
                        </a>

                    </td>



                </tr>
                <div class="modal fade" id="detailsModal-{{ $hs->id }}" tabindex="-1"
                    aria-labelledby="detailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <ul>
                                    <li>
                                        Table name: {{ $hs->table }}
                                    </li>
                                    <li>
                                        Record: {{ $hs->id_record }}
                                    </li>
                                    <li>
                                        Action: {{ $hs->action }}
                                    </li>
                                    <li>
                                        Date: {{ \Carbon\Carbon::parse($hs->created_at)->format('d/m/Y H:i') }}
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-6 overflow-hidden">
                                                <p><strong>Old values:</strong></p>
                                                <ul>
                                                    <ul>
                                                        @if ($hs->data_before == null)
                                                            <li>---</li>
                                                        @else
                                                            @foreach ($hs->data_before as $key => $value)
                                                                <li>{{ $key }}: {{ $value }}</li>
                                                            @endforeach
                                                        @endif

                                                    </ul>
                                                </ul>
                                            </div>
                                            <div class="col-6 overflow-hidden">
                                                <p><strong>New values:</strong></p>
                                                <ul>

                                                    <ul>
                                                        @if ($hs->data_after == null)
                                                            <li>---</li>
                                                        @else
                                                            @foreach ($hs->data_after as $key => $value)
                                                                <li>{{ $key }}: {{ $value }}</li>
                                                            @endforeach
                                                        @endif

                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
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
    {{ $historiques->links() }}
</div>


@push('scripts')
    <script></script>
@endpush
