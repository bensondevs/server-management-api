<div class="modal fade" id="editUserModal" tabindex="-1" role="document" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="{{ route('dashboard.users.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="user_id">

                    <div class="form-group col-4">
                        <figure class="avatar mr-2 avatar-xl">
                            <img id="edit_avatar_preview" src="">
                        </figure>
                    </div>

                    @include('components.modals.users.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">
                    Tutup
                </button>
                <button form="editUserForm" type="submit" class="btn btn-dark">Perbaharui</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#editUserModal').on('show.bs.modal', function (event) {
            console.log('Modal Edit shown!')

            // Get Component Event
            var button = $(event.relatedTarget)
            var modal = $(this)

            // Get Modal Data
            var userId = button.data('user_id')
            var name = button.data('name')
            var email = button.data('email')
            var role = button.data('role')
            var currentProfilePicture = button.data('profile_picture')
            var birthDate = button.data('birth_date')
            var gender = button.data('gender')
            var phone_number = button.data('phone_number')
            var address = button.data('address')
            console.log(currentProfilePicture)

            var formId = '#editUserForm'
            $(formId + ' input[name=user_id]').val(userId)
            $(formId + ' input[name=name]').val(name)
            $(formId + ' input[name=email]').val(email)
            $(formId + ' select[name=role] option[value=' + role + ']').attr('selected', 'selected')
            $(formId + ' input[name=birth_date]').val(birthDate)
            $(formId + ' select[name=gender] option[value=' + gender + ']').attr('selected', 'selected')
            $(formId + ' select option[value=' + gender + ']').attr('selected', 'selected')
            $(formId + ' input[name=phone_number]').val(phone_number)
            $(formId + ' input[name=address]').val(address)

            currentProfilePicture ? 
                $('#edit_avatar_preview').attr('src', '/' + currentProfilePicture) :
                null
        })

        $('#editUserForm input[name=profile_picture]').change((event) => {
            $('#edit_avatar_preview').attr('src', URL.createObjectURL(event.target.files[0]))
        })
    })
</script>