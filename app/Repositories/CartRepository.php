<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Cart, CartItem, Order, OrderItem };

class CartRepository extends BaseRepository
{
	/**
	 * User owner of the cart
	 * 
	 * @var \App\Models\User
	 */
	private $user;

	/**
	 * Current cart item container
	 * 
	 * @var \App\Models\CartItem|null
	 */
	private $item;

	/**
	 * Repository class constructor method
	 * 
	 * @param  \App\Models\User|null  $user
	 * @return void
	 */
	public function __construct($user = null)
	{
		$this->setInitModel(new Cart);

		$this->setItem(new CartItem);

		if (auth()->check()) {
			$user = $user ?: auth()->user();
			$this->setUser($user);
		}
	}

	/**
	 * Get user of the caty
	 * 
	 * @return  \App\Models\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set user of the cart
	 * 
	 * @param   \App\Models\User  $user
	 * @return  void
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Get current cart item
	 * 
	 * @return \App\Models\CartItem
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * Set current cart item
	 * 
	 * @param  \App\Models\CartItem  $item
	 * @return void
	 */
	public function setItem(CartItem $item)
	{
		$this->item = $item;
	}

	/**
	 * Create new cart
	 * 
	 * @return \App\Models\Cart
	 */
	public function create()
	{
		try {
			$cart = $this->getModel();
			$cart->user_id = $this->getUser()->id;
			$cart->save();

			$this->setModel($cart);

			$this->setSuccess('Successfully create new cart.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create new cart.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add item to cart
	 * 
	 * @param  mixed  $cartItemable
	 * @param  int  $quantity
	 * @return \App\Models\Cart
	 */
	public function addItem($cartItemable, int $quantity)
	{
		try {
			$cart = $this->getModel();
			$cartItem = CartItem::create([
				'user_id' => $this->getUser()->id,
				'cart_id' => $cart->id,
				'cart_itemable_type' => get_class($cartItemable),
				'cart_itemable_id' => $cartItemable->id,
				'quantity' => $quantity,
			]);

			if (get_class($cartItemable) == ServicePlan::class) {
				$cart->cart_name = $cartItemable->plan_name;
				$cart->save();
			}

			$this->setModel($cart);

			$this->setSuccess('Successfully add cart.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add cart.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set quantity of the cart
	 * 
	 * @param  \App\Models\CartItem  $item
	 * @param  int  $quantity
	 * @return \App\Models\Cart
	 */
	public function setItemQuantity(CartItem $item, int $quantity)
	{
		try {
			if ($quantity > 0) {
				$item->quantity = $quantity;
				$item->save();

				$this->setItem($cart);
			} else {
				$item->delete();
			}

			$this->setSuccess('Successfully set quantity for cart!');		
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set quantity for the cart.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Remove item from cart
	 * 
	 * @return  bool
	 */
	public function removeItem(CartItem $item)
	{
		try {
			$item->delete();

			$this->setSuccess('Successfully remove cart item.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to remove cart item.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Checkout cart and make it as order
	 * 
	 * @return  \App\Models\Order
	 */
	public function checkout()
	{
		try {
			$user = $this->getUser();
			$carts = Cart::where('user_id', $user->id)->get();

			/* Begin DB Transaction */
			DB::beginTransaction();
			/* Begin DB Transaction */

			// Create order
			$order = Order::create([
				'user_id' => $user->id,
				'currency' => $user->currency,
				'total' => 0,
				'vat_size_percentage' => 0,
				'grand_total' => 0,
			]);

			// Massively add raw order item
			$rawOrderItem = [];
			foreach ($carts as $cart) {
				if (! $cartable = $cart->cartable) {
					continue;
				}

				array_push($rawOrderItem, [
					'id' => generateUuid(),
					'order_id' => $order->id,
					'itemable_type' => $cart->cartable_type,
					'itemable_id' => $cart->cartable_id,
					'currency' => $user->currency,
					'quantity' => $cart->quantity,
					'price' => $cart->cartable->getPrice(),
					'discount' => 0,
					'total' => $cart->cartable->getPrice() * $cart->quantity,
				]);
			}
			OrderItem::insert($rawOrderItem);

			// Set the actual grand total
			$order->grand_total = $order->countGrandTotal();
			$order->save();

			/**
			 * Destory all user carts
			 */
			$user->carts()->delete();

			/* Commit DB execution */
			DB::commit();
			/* Commit DB execution */

			$this->setSuccess('Successfully checkout cart!');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to checkout cart.', $error);

			/* Rollback if error */
			DB::rollback();
			/* Rollback if error */
		}

		return $order;
	}

	/**
	 * Destroy cart
	 * 
	 * @return bool
	 */
	public function destroy()
	{
		try {
			$cart = $this->getModel();
			$cart->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully destroy cart.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to destroy cart.', $error);
		}

		return $this->returnResponse();
	}
}
