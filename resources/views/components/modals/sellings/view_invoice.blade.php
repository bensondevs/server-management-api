<script>
    function changeStatus(sellingId, status) {
        $.ajax({
            type: 'PATCH',
            url: '/dashboard/selling/change_status',
            cache: false,
            data: {
                '_token': '{{ csrf_token() }}',
                '_method': 'PATCH',
                'invoice_id': sellingId,
                'status': status,
            },
            dataType: 'JSON',
            success: function (response) {
                console.log(response)

                var data = response.data
                var status = response.status
                var message = response.message
            }
        })
    }

    function save() {
        location.reload()
    }
</script>

<div class="modal fade" id="viewInvoiceModal" tabindex="-1" role="document" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editInvoiceForm" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="selling_invoice_id">

                    @include('components.modals.sellings.form_content')
                </form>

                {{-- List of Invoice Items --}}
                @include('components.tables.sellings.table_content')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">
                    Tutup
                </button>
                <button onClick="save()" type="button" class="btn btn-dark text-white" data-dismiss="modal">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        function formatRupiah(value) {
            var valueString = value.toString()

            return 'Rp. ' + valueString.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
        }
        
        $('#viewInvoiceModal').on('show.bs.modal', function (event) {
            console.log('Modal view shown!')

            // Get Component Event
            var button = $(event.relatedTarget)
            var modal = $(this)

            var formId = '#editInvoiceForm'

            var selling = button.data('selling')
            var field = ''
            $(formId + ' input[name=selling_invoice_id]').val(selling.id)
            for (var sellingProperty in selling) {
                if (sellingProperty != 'status') {
                    $(formId + ' input[name=' + sellingProperty + ']').val(selling[sellingProperty])
                } else {
                    $('#invoice_status').empty()
                    var selectOption = '<select onchange="changeStatus(`' + selling['id'] + '`, this.value)" class="form-control">'
                    selectOption += '<option value="paid" ' + (selling[sellingProperty] == 'paid' ? 'selected' : '') + '>' 
                    selectOption += 'LUNAS' 
                    selectOption += '</option>'
                    selectOption += '<option value="unpaid" ' + (selling[sellingProperty] == 'unpaid' ? 'selected' : '') + '>' 
                    selectOption += 'BELUM LUNAS' 
                    selectOption += '</option>'
                    selectOption += '</select>'

                    $('#invoice_status').append(
                        selectOption
                    )
                }
            }
            
            // Selling Items -> Sold Product List
            var sellingItems = selling.items

            // Populate invoice items table
            var tbody = $('#invoice_items_table tbody')

            // Clear content inside tbody
            tbody.empty()
            $.each(sellingItems, (key, item) => {
                var tableRow = '<tr>'
                tableRow += '<td class="text-center">' + (key + 1) + '</td>'
                tableRow += '<td class="text-center">' + item.product.product_name + '</td>'
                tableRow += '<td class="text-center">' + formatRupiah(item.current_price) + '</td>'
                tableRow += '<td class="text-center">' + item.quantity + '</td>'
                tableRow += '<td class="text-center">' + formatRupiah(item.current_price *  item.quantity) + '</td>'
                tableRow += '</tr>'

                tbody.append(tableRow)
            })
        })
        $('#viewInvoiceModal').on('hidden.bs.modal', function(){
            //remove the backdrop
            $('.modal-backdrop').remove();
        })
    })
</script>