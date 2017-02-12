<?php
namespace Home\Controller;
use Think\Controller;

use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\BillingAgreementDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

class IndexController extends Controller {
    protected $config;

    public function __construct() {
        $this->config = array(
            'mode' => C('PAYPAL_MODE'), 
            'acct1.UserName' => C('PAYPAL_USERNAME'),
            'acct1.Password' => C('PAYPAL_PASSWORD'),
            'acct1.Signature' => C('PAYPAL_SIGNATURE'),
        );
        return parent::__construct(); 
    }
    
    public function index(){
        // SetExpressCheckout
        // 币种
        $currenyCode = 'HKD';
        // 价格
        $amount = new BasicAmountType($currencyCode, 100);

        // 货品详情
        $itemDetails = new PaymentDetailsItemType();
        $itemDetails->Name = 'TopUp Counpon';
        $itemDetails->Amount = 100;
        $itemDetails->Quantity = 1;

        $itemDetails->ItemCategory = "Physical";
        $itemDetials->Tax = new BasicAmountType($currencyCode, 0);

        // 支付单详情
        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

        // 账单地址
        $address = new AddressType();
        $address->CityName = 'Hong Kong';
        $address->Name = 'Somewhere in HK';
        $address->Street1 = 'A street in HK';
        $address->StateOrProvince = 'HK';
        $address->PostalCode = '000000';
        $address->Country = 'HK';
        $address->Phone = '98546158';

        $paymentDetails->ShipToAddress = $address;
        $paymentDetails->ItemTotal = $amount;
        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, 0);
        $paymentDetails->OrderTotal = $amount;
        $paymentDetails->PaymentAction = 'Sale';

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;
        $setECReqDetails->CancelURL = C('PAYPAL_CANCEL_URL');
        $setECReqDetails->ReturnURL = C('PAYPAL_RETURN_URL');
        $setECReqDetails->NoShipping = 1;
        $setECReqDetails->ReqConfirmShipping = 0;

        // 顯示選項
        // $setECReqDetails->cppheaderimage = $_REQUEST['cppheaderimage'];
        // $setECReqDetails->cppheaderbordercolor = $_REQUEST['cppheaderbordercolor'];
        // $setECReqDetails->cppheaderbackcolor = $_REQUEST['cppheaderbackcolor'];
        // $setECReqDetails->cpppayflowcolor = $_REQUEST['cpppayflowcolor'];
        // $setECReqDetails->cppcartbordercolor = $_REQUEST['cppcartbordercolor'];
        // $setECReqDetails->cpplogoimage = $_REQUEST['cpplogoimage'];
        // $setECReqDetails->PageStyle = $_REQUEST['pageStyle'];
        // $setECReqDetails->BrandName = $_REQUEST['brandName'];
        
        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;
        $paypalService = new PayPalAPIInterfaceServiceService($this->config);
        try {
            /* wrap API method calls on the service object with a try catch */
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        } catch (Exception $ex) {
            var_dump($ex);
            exit;
        }
        if(isset($setECResponse)) {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$setECResponse->Ack</div> </td></tr>";
            echo "<tr><td>Token :</td><td><div id='Token'>$setECResponse->Token</div> </td></tr>";
            echo "</table>";
            echo '<pre>';
            print_r($setECResponse);
            echo '</pre>';
            if($setECResponse->Ack =='Success') {
                $token = $setECResponse->Token;
                // Redirect to paypal.com here
                $payPalURL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . $token;
                // echo" <a href=$payPalURL><b>* Redirect to PayPal to login </b></a><br>";
                echo("<script>window.location.href='$payPalURL'</script>");
            }
        }
    }

    public function cancel(){
        echo('支付取消');
    }

    public function return() {
        echo('支付成功'); 
    }
}
