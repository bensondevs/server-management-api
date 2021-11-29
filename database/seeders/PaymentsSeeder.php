<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;

use App\Repositories\PaymentRepository;

class PaymentsSeeder extends Seeder
{
	protected $payment;

	private $statuses = ['paid', 'unpaid'];

	public function __construct(PaymentRepository $paymentRepository)
	{
		$this->payment = $paymentRepository;
	}

	private function populateSEB($users, $orders)
	{
		// Dummy Test Data
    	$cards = [];
    	array_push($cards, [
    		'card_type' => 'MasterCard',
    		'card_number' => '5204740000001002',
    		'expiration_date' => '12/25',
    		'cvc' => '100',
    		'holder_name' => 'Jonas Savulionis',
    	]);
    	array_push($cards, [
    		'card_type' => 'Visa',
    		'card_number' => '4012001037141112',
    		'expiration_date' => '12/27',
    		'cvc' => '212',
    		'holder_name' => 'Simeon Bensona',
    	]);
    	array_push($cards, [
    		'card_type' => 'Mastercard',
    		'card_number' => '5204740000001002',
    		'expiration_date' => '12/19',
    		'cvc' => '656',
    		'holder_name' => 'Augustinas',
    	]);

        for ($index = 0; $index < 100; $index++) {
        	$this->payment->save([
        		'user_id' => $users[rand(0, count($users) - 1)]['id'],
        		'order_id' => $orders[rand(0, (count($orders) - 1))]['id'],

        		'payment_method' => 'card',
        		'amount' => rand(1, 30) * 10,
        		'status' => $this->statuses[rand(0, 1)],
        		'card_informations' => $cards[rand(0, 2)],
        	]);
        	$this->payment->setModel(new Payment);
        	
        	if ($this->payment->status == 'error')
        		dd($this->payment->queryError);
        }
	}

	public function populatePaypal($users, $orders)
	{

	}

	public function populateStripe($users, $orders)
	{

	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = User::all()->toArray();
    	$orders = Order::all()->toArray();

    	$this->populateSEB($users, $orders);
    	$this->populatePaypal($users, $orders);
    	$this->populateStripe($users, $orders);
    }
}
