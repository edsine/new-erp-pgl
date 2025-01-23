<div class="card-body p-5">
    <div class="table-responsive">
        <table class="table" id="document-versions-table">
            <thead>
            <tr>
                <th>Version Number</th>
                <th>Document Url</th>
                <th>Created By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($documentVersions as $documentVersion)
                <tr>
                    <td>{{ $documentVersion->version_number }}</td>
                    <td><a target="_blank" href="{{ asset($documentVersion->document_url) }}">View</a></td>
                    <td>{{ $documentVersion->createdBy ? $documentVersion->createdBy->email : '' }}</td>
                    <td  style="width: 120px">
                        <div class='btn-group'>
                            <a href="{{ asset($documentVersion->document_url) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $documentVersions])
        </div>
    </div>
</div>
