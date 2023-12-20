<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Donation;

class CheckoutController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|email',
            'mobile' => 'nullable|string|regex:/^[6-9][0-9]{9}$/',
            'amount' => 'required|numeric|min:'.env('MIN_DONATION_AMOUNT',1),
            'country' => 'required|numeric',
            'state' => 'required|numeric',
            'city' => 'required|numeric',
            'street_address' => 'nullable|string',
            'add_to_leaderboard' => 'nullable|string|in:yes,no',
        ]);

        try{
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email'       => $request->email,
                'line_items'           => [
                    [
                        'price_data'  => [
                            'product_data' => [
                                'name' => env('TRUST_NAME').", ".env('TRUST_CITY'),
                            ],
                            'unit_amount'  => $request->amount * 100,
                            'currency'     => env('DONATION_CURRENCY', 'INR'),
                        ],
                        'quantity'    => 1,
                        'description' => 'By ' . $request->first_name . ' ' . $request->last_name,
                    ],
                ],
                // 'client_reference_id'  => '1234',
                'mode'                 => 'payment',
                'success_url'          => url('payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => url('failed-payment') . '?session_id={CHECKOUT_SESSION_ID}&error=payment_cancelled',
                'metadata' => [
                    'locale' => 'in',
                ]
            ]);

            $donation = new Donation();
            $donation->status = 'unpaid';
            $donation->amount = $request->amount;
            $donation->mobile = $request->mobile;
            $donation->street_address = $request->street_address;
            $donation->country_id = $request->country;
            $donation->state_id = $request->state;
            $donation->city_id = $request->city;
            $donation->email = $request->email;
            $donation->name = $request->first_name. ' ' . $request->last_name;
            $donation->session_id = $session->id;
            $donation->add_to_leaderboard = $request->add_to_leaderboard ?: 'no';
            $donation->save();
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => 'Unable to process checkout. [' .$e->getMessage() .']'])->withInput();
        }

        return redirect($session->url);
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        try{
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if(!$session){
                throw new \Exception("No checkout session found.");
            }

            $donation=Donation::where('session_id',$session->id)->where('status','unpaid')->first();
            if(!$donation){
                throw new \Exception("No checkout record found.");
            }

            $donation->status ='paid';
            $donation->save ();

            return redirect('donate')->with(['success' => 'Thanks for the donation. Your donation amount has been successfully deposited to the trust account.']);

        }
        catch(\Exception $e){
            return redirect('donate')->with(['error' => 'Something went wrong. [' .$e->getMessage() .']']);
        }

    }

    public function handleFailedPayment(Request $request)
    {
        $sessionId = $request->get('session_id');
        try{
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if(!$session){
                throw new \Exception("No checkout session found.");
            }

            $donation=Donation::where('session_id',$session->id)->where('status','unpaid')->first();
            if(!$donation){
                throw new \Exception("No checkout record found.");
            }

            $donation->status ='failed';
            $donation->save();

            return redirect('donate')->with(['error' => 'Checkout process has been cancelled.']);

        }
        catch(\Exception $e){
            return redirect('donate')->with(['error' => 'Something went wrong. [' .$e->getMessage() .']']);
        }

    }
}
