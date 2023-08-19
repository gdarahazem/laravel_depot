<div class="modal fade" id="kt_modal_show_product" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_show_product_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Product</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_show_product_form" class="form" action="#" wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2" for="ref">Référence</label>
                        <input type="text" wire:model.defer="ref" name="ref" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Référence du produit"/>
                        @error('ref')
                        <span class="text-danger">{{ $message }}</span> @enderror
                    </div>


                    @if($errorMessage)
                        <div class="alert alert-danger mt-3">
                            {{ $errorMessage }}
                        </div>
                    @else
                        <h3 class="text-center mb-4">Product: {{$this->article_name}}</h3>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <strong>Price:</strong> {{$this->price}}<br>
                                <strong>Crédit:</strong> {{$this->credit}}
                            </div>
                            <div class="col-md-6">
                                <strong>Déposant:</strong> {{$this->client_name}}<br>
                                <strong>Date de depot:</strong> {{$this->depot_date}}
                            </div>
                        </div>
                        <strong>Statut:</strong> {{$this->status}}
                    @endif
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
