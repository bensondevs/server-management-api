<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Carts\{
    AddCartRequest,
    SetCartQuantityRequest
};
use App\Http\Resources\CartResource;

use App\Models\Cart;
use App\Repositories\CartRepository;

class CartController extends Controller
{
    /**
     * Cart repository class container
     * 
     * @var \App\Repositories\CartRepository|null
     */
    private $cart;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\CartRepository  $cart
     * @return void
     */ 
    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Populate user cart
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function carts()
    {
        $carts = Cart::forUser(auth()->user())->get();
        $carts = CartResource::collection($carts);
        return response()->json(['carts' => $carts]);
    }

    /**
     * Add item to cart
     * 
     * @param  AddCartRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function add(AddCartRequest $request)
    {
        $this->cart->setUser(auth()->user());
        $cartable = $request->getCartable();
        $quantity = $request->input('quantity');
        $this->cart->add($cartable, $quantity);

        return apiResponse($this->cart);
    }

    /**
     * Set quantity for the cart
     * 
     * @param  SetCartQuantityRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function setQuantity(SetCartQuantityRequest $request, Cart $cart)
    {
        $this->cart->setModel($cart);

        $quantity = $request->input('quantity');
        $this->cart->setQuantity($quantity);

        return apiResponse($this->cart);
    }

    /**
     * Remove item from cart
     * 
     * @param  \App\Models\Cart  $cart
     * @return Illuminate\Support\Facades\Response
     */
    public function remove(Cart $cart)
    {
        $this->cart->setModel($cart);
        $this->cart->remove();

        return apiResponse($this->cart);
    }

    /**
     * Checkout cart
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function checkout()
    {
        $this->cart->checkout();
        return apiResponse($this->cart);
    }
}
