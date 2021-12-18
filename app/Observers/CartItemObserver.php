<?php

namespace App\Observers;

use App\Models\CartItem;

class CartItemObserver
{
    /**
     * Handle the CartItem "creating" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function creating(CartItem $cartItem)
    {
        $cartItem->id = generateUuid();
        $cartItem->countSubTotal();
    }

    /**
     * Handle the CartItem "created" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function created(CartItem $cartItem)
    {
        $cart = $cartItem->cart;
        $cart->total += $cartItem->sub_total;
    }

    /**
     * Handle the CartItem "updating" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function updating(CartItem $cartItem)
    {
        if ($cartItem->isDirty('quantity') || $cartItem->isDirty('discount')) {
            $cartItem->countSubTotal();

            $cart = $cartItem->cart;

            $prevTotal = $cartItem->getOriginal('total');
            $currTotal = $cartItem->total;

            $cart->total += $currTotal - $prevTotal;
        }
    }

    /**
     * Handle the CartItem "updated" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function updated(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "deleted" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function deleted(CartItem $cartItem)
    {
        $cart = $cartItem->cart;
        $cart->total -= $cartItem->sub_total;
    }

    /**
     * Handle the CartItem "restored" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function restored(CartItem $cartItem)
    {
        //
    }

    /**
     * Handle the CartItem "force deleted" event.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return void
     */
    public function forceDeleted(CartItem $cartItem)
    {
        //
    }
}
