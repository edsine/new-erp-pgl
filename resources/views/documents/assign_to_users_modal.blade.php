<!-- Modal -->
<div class="modal fade" id="assignToUsersModal" tabindex="-1" role="dialog" aria-labelledby="assignToUsersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        {!! Form::open(['route' => 'memos.assignToUsers']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign to Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label text-right">Select User(s)</label>
                    <select class="form-control select2" id="user_select" name="users[]" multiple="multiple">
                    </select>
                </div>

                <!-- Memo Id Field -->
                {!! Form::hidden('memo_id', null, ['id' => 'user_memo_id']) !!}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>



@push('page_scripts')
    <script>
        $(document).ready(function() {
            $("#user_select").select2({
                placeholder: "Search for user",
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: "{{ url('api/users') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: {
                                email: params.term
                            },
                            limit: 10 // Number of users per page
                        };
                    },
                    processResults: function(data, params) {
                        var options = [];
                        $.each(data.data, function(index, user) {
                            options.push({
                                id: user.id,
                                text: user.email
                            });
                        });


                        return {
                            results: options,
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
            });
        })
    </script>
@endpush
