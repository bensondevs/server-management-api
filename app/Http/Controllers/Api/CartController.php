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
     * Populate user carts
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
     * Populate user cart items
     * 
     * @param  \App\Models\Cart  $cart
     * @return Illuminate\Support\Facades\Response
     */
    public function cartItems(Cart $cart)
    {
        $items = $cart->items;
        $items = CartItemResource::collection($items);
        return response()->json(['cart_items' => $items]);
    }

    /**
     * Putting service plan to cart. This will create new cart, because
     * one cart can only contain one service plan.
     * 
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return \Illuminate\Support\Facades\Response
     */
    public function addServicePlan(ServicePlan $servicePlan)
    {
        $this->cart->create();
        $this->cart->addItem($servicePlan, 1);

        return apiResponse($this->cart);
    }

    /**
     * Putting service addon to cart.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @param  \App\Models\ServiceAddon  $addon
     * @return \Illuminate\Support\Facades\Response
     */
    public function addServiceAddon(Request $request, Cart $cart, ServiceAddon $addon)
    {
        $this->cart->setModel($cart);

        $quantity = $request->input('quantity');
        $this->cart->addItem($addon, $quantity);

        return apiResponse($this->cart);
    }

    /**
     * Set quantity of the cart item
     * 
     * @param  SetQuantityRequest  $request
     * @param  \App\Models\CartItem  $item
     * @return \Illuminate\Support\Facades\Response
     */
    public function setItemQuantity(SetQuantityRequest $request, CartItem $item)
    {
        $quantity = $request->input('quantity');
        $this->cart->setItemQuantity($item, $quantity);

        return apiResponse($this->cart);
    }

    /**
     * Remove item from cart
     * 
     * @param  \App\Models\CartItem  $item
     * @return \Illuminate\Support\Facades\Response
     */
    public function removeItem(CartItem $item)
    {
        $this->cart->removeItem($item);

        return apiResponse($this->cart);
    }

    /**
     * Destroy cart
     * 
     * @param  \App\Models\Cart  $cart
     * @return Illuminate\Support\Facades\Response
     */
    public function destroy(Cart $cart)
    {
        $this->cart->setModel($cart);
        $this->cart->destroy();

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
