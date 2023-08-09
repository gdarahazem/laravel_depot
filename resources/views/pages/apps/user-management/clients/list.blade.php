<x-default-layout>

    @section('title')
        Client
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.clients.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-client-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13" placeholder="Search user"
                           id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-client-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_client">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Client
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:client.add-client-modal></livewire:client.add-client-modal>
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
        <iframe id="printFrame" style="display:none;"></iframe>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['clients-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_client').modal('hide');
                    window.LaravelDataTables['clients-table'].ajax.reload();
                });
            });
        </script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

        <script>
            function generateProductList(products) {
                return `
                        <ul>
                            ${products.map(product => {
                                    let finalPrice = product.price - (product.price * product.percentage);
                                    return `<li style="font-size: 18px;">${product.name} | ${finalPrice.toFixed(2)} DT</li>`;
                                }).join('')}
                        </ul>
                    `;
            }

            function printContract(client) {
                let productsListHtml = generateProductList(client.products);
                let iframe = document.getElementById('printFrame');
                let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                let currentDate = new Date();
                let formattedDate = `${currentDate.getDate().toString().padStart(2, '0')}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getFullYear()}`;


                let content = `
            <html>
                <head>
                    <title>{{ config('app.name') }}</title> <!-- This will pull the current Laravel app name if it's set as the page title -->
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .text-center-title {
                            text-align: center;
                            font-weight: bold;
                            font-size: 50px;
                        }
                        .text-center-sub-title {
                            text-align: center;
                            font-weight: bold;
                            font-size: 25px;
                        }
                        p {
                        font-size:20px;
                        }
                        p.date-right {
                            text-align: right;
                        }
                       .signature-box {
                            margin-top: 100px;
                            width: 50%;
                            float: left;
                            text-align: center;  /* center the text for both boxes */
                        }

                        .centered-text {
                            display: block;
                            margin: 0 auto;
                        }
                        .clear {
                            clear: both;
                        }
                        .bold {
                            font-weight: bold;
                         }
                        #qrCode {
                            margin-top: 10px;
                            display: flex;
                            justify-content: center;  /* Horizontally center the child items */
                            align-items: center;      /* Vertically center the child items */
                        }
                        .centered-text {
                            text-align: center;
                        }

                    </style>
                </head>
                <body>
                 <h1 class="text-center-title">{{ config('app.name') }}</h1>
                    <h1 class="text-center-sub-title">ACTE DE DEPOT</h1>
                    <p class="date-right">Tunis le ${formattedDate}</p>
                    <p>Je soussignée ${client.fullname} Titulaire de la CIN n° : . . . . . . . . . . Tel : ${client.phonenumber} déclare avoir déposé auprès de {{ config('app.name') }}
                le(s) article(s) décrit(s) Ci-après :</p>
                ${productsListHtml}
                <p>ACTE DE DEPOT
                J'atteste par le présent document que les produits sus-indiqués m’appartiennent (non volés et non empruntés) et qu'ils ne sont
                pas issus de la contrefaçon. A défaut, je prends entièrement la responsabilité que ce soit auprès de {{ config('app.name') }} ou auprès
                    des tiers.</p>
                    <p>
                    Par le présent acte, j’autorise {{ config('app.name') }} à vendre le(s) bien(s) ci-dessus au(x) prix indiqué(s) et à percevoir une
                    commission convenue de 20% qui en sera déduite. Cet engagement est valable pendant une période de dépôt de 30 jours.
                    Durant cette période, si je souhaite mettre fin au présent engagement en récupérant un ou plusieurs ou la totalité des articles
                    déposés, je reconnais devoir au préalable indemniser {{ config('app.name') }} en lui versant le montant des commissions qui auraient
                    été perçues par la vente de cet (ces) article(s).
                    </p>
                    <p>
                    Aussi, je déclare bénéficier d'un délai de 15 jours, à compter de la fin de la période de dépôt, pour récupérer les articles qui
                    n'ont pas été vendus
                    </p>
                    <p>Passé ce délai, je désengage {{ config('app.name') }} de toute responsabilité et je reconnais perdre tout droit sur les biens non repris.
                    Tout article déposé et non récupéré à temps fera automatiquement l'objet de don auprès d’associations à but non lucratif.
                    </p>
                    <p>
                    Le prix des articles sera réduit de 50%, 35 jours après la date du dépôt.
                    </p>
                    <p>N.B: {{ config('app.name') }} se réserve le droit de retirer sans préavis un article de la vente en cas de défaut non signalé lors du
                    dépôt. Les articles déposés ne font pas tous l’objet d’une diffusion sur les réseaux sociaux</p>
                    <div class="clear">
                   <div class="signature-box">
                        <p class="centered-text">CACHET DU DÉPOSITAIRE,</p>
                        <p class="centered-text bold">{{ config('app.name') }}</p>
                    </div>
                    <div class="signature-box">
                        <p class="centered-text">SIGNATURE, “Bon pour accord”</p>
                        <p class="centered-text bold">Le déposant</p>
                    </div>
                    <div style="clear: both;"></div>
                    <p class="text-center-sub-title" style="margin-top: 150px;">Le déposant peut suivre la vente de ses articles via le lien suivant :</p>
                    <div id="qrCode"></div>
                    <p class="centered-text" style="margin-top: 20px;">
                        <a href="{{ config('app.url') }}/${client.id}">{{ config('app.url') }}/${client.id}</a>
                    </p>
                    </div>
                </body>
            </html>
        `;

                iframeDoc.open();
                iframeDoc.write(content);
                iframeDoc.close();

                // Generate QRCode outside of iframe first
                let tempDiv = document.createElement('div');
                let clientId = client.id;
                let baseUrl = "{{ config('app.url') }}";
                new QRCode(tempDiv, `${baseUrl}/${clientId}`);

                // Copy QRCode into the iframe
                let qrCodeElement = iframeDoc.getElementById("qrCode");
                while (tempDiv.firstChild) {
                    qrCodeElement.appendChild(tempDiv.firstChild);
                }

                iframe.contentWindow.print();
            }
        </script>
    @endpush


</x-default-layout>
