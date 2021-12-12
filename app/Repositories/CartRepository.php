<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Cart, Order, OrderItem };

class CartRepository extends BaseRepository
{
	/**
	 * User owner of the cart
	 * 
	 * @var \App\Models\User
	 */
	private $user;

	/**
	 * Repository class constructor method
	 * 
	 * @param  \App\Models\User|null  $user
	 * @return void
	 */
	public function __construct($user = null)
	{
		$this->setInitModel(new Cart);

		$user ? 
			$this->setUser($user) : 
			$this->setUser(auth()->user());
	}

	/**
	 * Get user of the caty
	 * 
	 * @return  \App\Models\User  $user
	 */
	public function getUser()
	{
		$this->user = $this->user ?: auth()->user();
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
	 * Add item to cart
	 * 
	 * @param  mixed  $cartable
	 * @param  int  $quantity
	 * @return \App\Models\Cart
	 */
	public function add($cartable, int $quantity)
	{
		try {
			$cart = Cart::create([
				'user_id' => $this->getUser()->id,
				'cartable_type' => get_class($cartable),
				'cartable_id' => $cartable->id,
				'quantity' => $quantity,
			]);

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
	 * @param  int  $quantity
	 * @return \App\Models\Cart
	 */
	public function setQuantity(int $quantity)
	{
		try {
			$cart = $this->getModel();
			
			if ($quantity > 0) {
				$cart->quantity = $quantity;
				$cart->save();
				$this->setModel($cart);
			} else {
				$cart->delete();
				$this->destroyModel();
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
	public function remove()
	{
		try {
			$cart = $this->getModel();
			$cart->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully remove cart.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to remove cart.', $error);
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
				$cartable = $cart->cartable;
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
}
