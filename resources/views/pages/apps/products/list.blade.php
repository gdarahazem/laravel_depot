<x-default-layout>

    @section('title')
        Users
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection
    @push("styles")
        <style>
            .bg-orange {
                background-color: orange;
            }
        </style>
    @endpush
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13" placeholder="Search user"
                           id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_product">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add User
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:product.add-product-modal></livewire:product.add-product-modal>
                <livewire:product.show-product-modal></livewire:product.show-product-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <iframe id="printFrame" style="display:none;"></iframe>


    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['users-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_product').modal('hide');
                    window.LaravelDataTables['users-table'].ajax.reload();
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js"></script>

        <script>

            function printCodeBarre(product) {
                const iframe = document.getElementById('printFrame');
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write('<html><head><title>Print Barcode</title></head><body>');
                iframeDoc.write('<div style="text-align: center;">');
                iframeDoc.write('<svg id="barcode" style="display: block; margin: 0 auto;"></svg>');  // SVG element to hold the barcode
                iframeDoc.write('<script src="https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js"><\/script>');  // Include the JsBarcode library
                iframeDoc.write('<script>JsBarcode("#barcode", "' + product.id + '");<\/script>');

                // Add the product details below the barcode
                iframeDoc.write('<div style="margin-top: 5px; font-weight: bold;">' + product.name + '</div>');
                iframeDoc.write('<div style="margin-top: 5px; font-weight: bold;">' + product.price + ' DT</div>');
                var date = new Date(product.createdOn);
                var formattedDate = date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0');
                iframeDoc.write('<div style="margin-top: 5px; font-weight: bold;">' + formattedDate + '</div>');

                iframeDoc.write('</div></body></html>');
                iframeDoc.close();

                setTimeout(() => {
                    iframe.contentWindow.print();
                }, 100);
            }
        </script>

        <script>
            $(document).ready(function () {
                $('#products-table tbody').on('click', 'tr:not(.action-column)', function (event) {
                    // Prevent click if it's from the action column
                    if ($(event.target).closest('.action-column').length) {
                        return;
                    }

                    const rowData = window.LaravelDataTables['products-table'].row(this).data();
                    const productId = rowData.id;
                    window.livewire.emit('loadProduct', productId);
                    $('#kt_modal_show_product').modal('show'); // Show the modal
                });
            });

        </script>
            <script>
                window.addEventListener('showProductModal', event => {
                    $('#kt_modal_show_product').modal('show');
                });
            </script>
    @endpush

</x-default-layout>
