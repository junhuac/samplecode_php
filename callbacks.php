<?php
/** 
 * PayNearMe Callbacks API - PHP implementation details
 * PHP v5.3.x+ supported
 *
 */

include_once 'callback_xml_builder.php';

class PaynearmeCallbacks {
  private $secret = '';
  private $EXCLUDED_PARAMS = array('signature', 'call', 'fp', 'print_buttons');

	function __construct($secret = '') {
    $this->secret = $secret;
	}

	function authorize($params) {
    $xml = new CallbackXmlBuilder('payment_authorization_response');
    $auth = $xml->createElement('authorization');
    $xml->appendChild($auth);

    # Do our authorization here...
    $our_sig = $this->signature($params);
    $sig = $params['signature'];

    $site_order_identifier = $params['site_order_identifier'];
    $accept = $our_sig == $sig && strpos($site_order_identifier, 'TEST') === 0;

    $auth->appendChild(
      $xml->createElement('pnm_order_identifier', $params['pnm_order_identifier']));
    $auth->appendChild(
      $xml->createElement('accept_payment', $accept ? 'yes' : 'no'));
    $auth->appendChild(
      $xml->createElement('receipt', $accept ? 'Thank you for your order!' : 'Order Declined'));
    $auth->appendChild(
      $xml->createElement('memo', $accept ? date() : "Invalid payment: $site_order_identifier"));


    return $xml->to_xml();
	}

	public function confirm($params) {

	}

	// private methods

	private function signature($params) {
		$str = '';
    ksort($params);
    foreach ($params as $k => $v) {
      if (!in_array($k, self::$EXCLUDED_PARAMS)) {
        $str .= "$k$v";
      }
    }
    return md5($str . $this->secret);
	}
}

?>