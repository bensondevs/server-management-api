<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="{{ route('dashboard.users.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-4">
                        <figure class="avatar mr-2 avatar-xl">
                            <img id="add_avatar_preview" src="{{ asset('assets/img/avatar/avatar-1.png') }}">
                        </figure>
                    </div>

                    @include('components.modals.users.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button form="addUserForm" type="submit" class="btn btn-dark">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#addUserForm input[name=profile_picture]').change((event) => {
        console.log(event)

        $('#add_avatar_preview').attr('src', URL.createObjectURL(event.target.files[0])) 
    })
</script>