<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;

class ShowProductModal extends Component
{
    public $ref;
    public $article_name;
    public $price;
    public $credit;
    public $client_name;
    public $depot_date;
    public $status;

    public $errorMessage = '';
    protected $rules = [
        'ref' => 'required|string',
    ];
    protected $listeners = ['loadProduct' => 'fillProductAndShowModal'];
    public function render()
    {
        return view('livewire.product.show-product-modal');
    }

    public function submit()
    {
        $this->validate();

        $product = Product::where('id', $this->ref)->first();

        if ($product) {
            $this->fillProduct($product->id);
            $this->errorMessage = '';
        } else {
            $this->errorMessage = 'Product not found.';
            // Reset the data
            $this->article_name = '';
            $this->price = '';
            $this->credit = '';
            $this->client_name = '';
            $this->depot_date = '';
            $this->status = '';
        }
    }

    public function fillProductAndShowModal($id) {
        $this->fillProduct($id);

        // This is a little piece of inline JS to show the modal after fetching product data
        $this->dispatchBrowserEvent('showProductModal');
    }
    public function fillProduct($id)
    {


        $product = Product::where("id", $id)->with("client")->first();

        $this->ref = $product->id;
        $this->article_name = $product->name;
        $this->price = $product->price . " DT";
        $this->credit = $product->topay == 0 ? "Non" : "Oui";
        $this->client_name = $product->client->name;
        $this->depot_date = Carbon::parse($product->createdOn)->format('d M Y, h:i a');;
        $this->status = $product->paid == 0 ? "Disponible" : "Vendu";

    }


}
