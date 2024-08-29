<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\View\View;
use App\Models\RemainingStock;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RemainingStockProductsDetail extends Component
{
    use AuthorizesRequests;

    public RemainingStock $remainingStock;
    public Product $product;
    public $productsForSelect = [];
    public $product_id = null;
    public $quantity;

    public $showingModal = false;
    public $modalTitle = 'New Product';

    protected $rules = [
        'product_id' => ['required', 'exists:products,id'],
        'quantity' => ['nullable', 'numeric'],
    ];

    public function mount(RemainingStock $remainingStock): void
    {
        $this->remainingStock = $remainingStock;
        $this->productsForSelect = Product::pluck('name', 'id');
        $this->resetProductData();
    }

    public function resetProductData(): void
    {
        $this->product = new Product();

        $this->product_id = null;
        $this->quantity = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newProduct(): void
    {
        $this->modalTitle = trans('crud.remaining_stock_products.new_title');
        $this->resetProductData();

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        $this->authorize('create', Product::class);

        $this->remainingStock->products()->attach($this->product_id, [
            'quantity' => $this->quantity,
        ]);

        $this->hideModal();
    }

    public function detach($product): void
    {
        $this->authorize('delete-any', Product::class);

        $this->remainingStock->products()->detach($product);

        $this->resetProductData();
    }

    public function render(): View
    {
        return view('livewire.remaining-stock-products-detail', [
            'remainingStockProducts' => $this->remainingStock
                ->products()
                ->withPivot(['quantity'])
                ->paginate(20),
        ]);
    }
}
