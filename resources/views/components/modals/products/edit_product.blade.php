<div 
    class="modal fade" 
    id="editProductModal" 
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
                <form id="editProductForm" action="{{ route('dashboard.products.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="product_id">

                    @include('components.modals.products.form_content')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">
                    Tutup
                </button>
                <button form="editProductForm" type="submit" class="btn btn-dark"><i class="fas fa-edit mr-1"></i>Perbaharui</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#editProductModal').on('show.bs.modal', function (event) {
            console.log('Modal Edit shown!')

            // Get Component Event
            var button = $(event.relatedTarget)
            var modal = $(this)

            var formId = '#editProductForm'

            var product = button.data('product')
            console.log(button.data('product'))
            $(formId + ' input[name=product_id]').val(product.id)
            $(formId + ' img[name=image_preview]').attr('src', 
                product['product_image'] ? 
                    '/' + product['product_image'] : 
                    'assets/img/p-50.png'
            )
            $(formId + ' select[name=supplier_id] option[value=' + product.supplier_id + ']').attr('selected', 'selected')
            for (var productProperty in product) {
                if ($(formId + ' input[name=' + productProperty + ']')) {
                    $(formId + ' input[name=' + productProperty + ']')
                        .attr('value', product[productProperty])
                }
            }
        })
        $('#editProductModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })

        $('#editProductForm input[name=product_image]').change((event) => {
            var imageUrl = URL.createObjectURL(event.target.files[0])
            $(formId + ' img[name=image_preview]').attr('src', imageUrl)
        })
    })
</script>