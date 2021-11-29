<div class="modal fade" id="editSupplierModal" tabindex="-1" role="document" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSupplierForm" action="{{ route('dashboard.suppliers.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="supplier_id">

                    @include('components.modals.suppliers.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">
                    Tutup
                </button>
                <button form="editSupplierForm" type="submit" class="btn btn-dark">Perbaharui</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#editSupplierModal').on('show.bs.modal', function (event) {
            console.log('Modal Edit shown!')

            // Get Component Event
            var button = $(event.relatedTarget)
            var modal = $(this)

            var formId = '#editSupplierForm'
            $(formId + ' input[name=supplier_id]').val(button.data('supplier_id'))
            $(formId + ' input[name=supplier_name]').val(button.data('supplier_name'))
            $(formId + ' input[name=company]').val(button.data('company'))
            $(formId + ' input[name=phone_number]').val(button.data('phone_number'))
        })
        $('#editSupplierModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })
    })
</script>