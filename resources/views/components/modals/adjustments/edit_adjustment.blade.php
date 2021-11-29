<div 
    class="modal fade" 
    id="editAdjustmentModal" 
    tabindex="-1" 
    role="document" 
    aria-labelledby="exampleModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAdjustmentForm" action="{{ route('dashboard.products.stocks.adjustments.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="adjustment_id">

                    @include('components.modals.adjustments.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">
                    Tutup
                </button>
                <button form="editAdjustmentForm" type="submit" class="btn btn-dark">Perbaharui</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#editAdjustmentModal').on('show.bs.modal', function (event) {
            console.log('Modal Edit shown!')

            // Get Component Event
            var button = $(event.relatedTarget)
            var modal = $(this)

            var formId = '#editAdjustmentForm'

            var adjustment = button.data('adjustment')
            console.log(button.data('adjustment'))
            $(formId + ' input[name=adjustment_id]').val(adjustment.id)
            for (var adjustmentProperty in adjustment) {
                $(formId + ' input[name=' + adjustmentProperty + ']').val(adjustment[adjustmentProperty])
            }
            $(formId + ' select[name=product_id] option[value=' + adjustment.product_id + ']').attr('selected', 'selected')
        })
        $('#editAdjustmentModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })
    })
</script>