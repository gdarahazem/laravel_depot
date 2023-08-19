<?php

namespace App\DataTables;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['RÉF', 'ARTICLE', 'ATTRIBUTS', 'PRIX', 'CRÉDIT', 'PRIX EN MAIN', 'STATUS'])
            ->editColumn('RÉF', function (Product $product) {
                return $product->id;
            })
            ->editColumn('ARTICLE', function (Product $product) {
                return $product->name;
            })
            ->editColumn('ATTRIBUTS', function (Product $product) {
                $formattedAttributes = str_replace([';', '(', ')'], ['<br>', ' ', ' '], $product->attributes);
                return $formattedAttributes;
            })
            ->editColumn('PRIX', function (Product $product) {
                return $product->price . " DT";
            })
            ->editColumn('CRÉDIT', function (Product $product) {
                return $product->topay == 0 ? "Non" : "Oui";
            })
            ->editColumn('PRIX EN MAIN', function (Product $product) {
                $discountedPrice = $product->price - ($product->price * $product->percentage / 100);
                return number_format($discountedPrice, 2, '.', '') . " DT";
            })
            ->editColumn('STATUS', function (Product $product) {
                return $product->paid == 0 ? "Disponible" : "Vendu";

            })
            ->setRowClass(function ($product) {
                if ($product->paid == 1 && $product->purchased == 1) {
                    return 'bg-success';
                } elseif ($product->paid == 0 && $product->purchased == 0) {
                    return 'bg-warning';
                } else {
                    return '';
                }
            })

            ->addColumn('action', function (Product $product) {
                return view('pages.apps.products.columns._actions', compact('product'));
            })
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with("client");
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/products/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('RÉF')->name('id'),
            Column::make('ARTICLE')->name('name'),
            Column::make('ATTRIBUTS')->name('attributes'),
            Column::make('PRIX')->name('price'),
            Column::make('CRÉDIT'),
            Column::make('PRIX EN MAIN'),
            Column::make('STATUS'),

            Column::computed('action')
                ->addClass('text-end text-nowrap action-column')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
