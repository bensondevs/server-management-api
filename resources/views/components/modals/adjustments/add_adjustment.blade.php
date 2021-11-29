<div class="modal fade" id="addAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Penyesuaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAdjustmentForm" action="{{ route('dashboard.products.stocks.adjustments.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('components.modals.adjustments.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button form="addAdjustmentForm" type="submit" class="btn btn-dark">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#addAdjustmentModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })

        $('#addAdjustmentForm input[name=product_image]').change((event) => {
            var imageUrl = URL.createObjectURL(event.target.files[0])
            $('#addAdjustmentForm img[name=image_preview]').attr('src', imageUrl)
        })
    })
</script>