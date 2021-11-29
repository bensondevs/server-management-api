<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" action="{{ route('dashboard.products.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('components.modals.products.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button form="addProductForm" type="submit" class="btn btn-dark"><i class="fas fa-plus mr-1"></i>Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#addProductModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })

        $('#addProductForm input[name=product_image]').change((event) => {
            var imageUrl = URL.createObjectURL(event.target.files[0])
            $('#addProductForm img[name=image_preview]').attr('src', imageUrl)
        })
    })
</script>