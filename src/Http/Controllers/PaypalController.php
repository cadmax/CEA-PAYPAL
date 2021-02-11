<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Payment as PaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Carbon\Carbon;

class PaypalController extends Controller
{
    public function __construct() {
        $paypal_conf = Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );

        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payment (Request $request) {
        $data = $request->validate([
            'item_1' => 'required'
        ]);

        // Class that represents the payer of this transaction. The payment method must be defined as “paypal”.
        $payer = new Payer();

        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        // Price converted to "real"
        $realValue = Functions::centstoreal(intval($data['item_1']));

        // Items
        $item_1->setName('Assinatura do projeto')->setCurrency('BRL')->setQuantity(1)->setPrice(floatval($realValue));

        $itens_list = new ItemList();

        $itens_list->setItems(array($item_1));

        // Payment value
        $value = new Amount();

        $value->setCurrency('BRL')->setTotal(floatval($realValue));

        // Payment contract
        $transaction = new Transaction();

        $transaction->setAmount($value)->setItemList($itens_list)->setDescription('Your transaction description');

        // Defines the URLs to which the buyer should be redirected after payment approval / cancellation.
        $redirect_urls = new RedirectUrls();

        $redirect_urls->setReturnUrl(URL::route('finishPayment'))->setCancelUrl(URL::route('finishPayment'));

        // Class representing the payment for this transaction. The intent must be set to "sale".
        $payment = new Payment();

        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            if (Config::get('app.debug')) {
                Session::put('error', 'Connection Timeout Exceeded');

                return redirect(route('paypal'));
            } else {
                Session::put('error', 'Service down, try again later.');

                return redirect(route('paypal'));
            }
        }

        $approvalUrl = $payment->getApprovalLink();

        Session::put('payment_paypal_id', $payment->getId());

        // Payment data
        $paymentDataObject = Payment::get($payment->getId(), $this->_api_context);

        $paymentData = [
            'intent' => $paymentDataObject->intent,
            'state' => $paymentDataObject->state,
            'cart' => $paymentDataObject->cart
        ];

        PaymentModel::create([
            'payment_paypal_id' => $payment->getId(),
            'date' => Carbon::now(),
            'status' => 'PENDING_APPROVAL',
            'value' => $data['item_1'],
            'info' => json_encode($paymentData)
        ]);

        return redirect($approvalUrl);
    }

    public function finish (Request $request) {
        // neste metodo é o retorno que o paypal envia pro nosso sistema, com os dados do pagamento.

        $payment_id = Session::get('payment_paypal_id');

        $paymentModel = PaymentModel::where('payment_paypal_id', $payment_id)->firstOrFail();

        Session::forget('payment_paypal_id');

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            Session::put('error', 'Transaction failed.');

            $paymentModel->update(['status' => 'CANCELED']);

            return redirect(route('paypal'));
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $payment_exec = new PaymentExecution();

        $payment_exec->setPayerId($request->get('PayerID'));

        $result = $payment->execute($payment_exec, $this->_api_context);

        if ($result->getState() === 'approved') {
            Session::put('success', 'Payment successful!');

            // Update payment status to COMPLETED
            $paymentModel->update(['status' => 'COMPLETED']);
        } else {
            // Update payment status to CANCELED
            $paymentModel->update(['status' => 'CANCELED']);

            Session::put('error', 'Transaction failed.');
        }

        return view('paypal');
    }
}
