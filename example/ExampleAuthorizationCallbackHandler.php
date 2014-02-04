<?php
/* Example authorization request handler */

class ExampleAuthorizationCallbackHandler extends PaynearmeCallback {
	public function handleRequest() {
		PnmLogger::debug('Entered /authorize');
	    # Do our authorization here...

	    # this is where you would connect to your database, retrieve the order, and determine if this payment
	    # should be authorized. For the puprposes of this example, we authorize any site_order_identifier 
	    # beginning with "TEST" and a valid signature.
		$accept = $this->valid_signature() && strpos($this->site_order_identifier, 'TEST') === 0;

    PnmLogger::debug("Order {$this->site_order_identifier} will" 
      . (!$accept ? ' NOT ' : ' ') . 'be accepted!');

	    # Build our xml output
		$xml = new CallbackXmlBuilder('payment_authorization_response');
		$auth = $xml->createElement('authorization');
		$xml->appendChild($auth);

		$auth->appendChild(
			$xml->createElement('pnm_order_identifier', $this->pnm_order_identifier));
		$auth->appendChild(
			$xml->createElement('accept_payment', $accept ? 'yes' : 'no'));
		$auth->appendChild(
			$xml->createElement('receipt', $accept ? 'Thank you for your order!' : 'Order Declined'));
		$auth->appendChild(
			$xml->createElement('memo', 
				$accept ? date('YYYY-MM-DD HH:mm:ss') : "Invalid payment: {$this->site_order_identifier}"));

		PnmLogger::debug('End of /authorize, returning XML');
		return $xml->to_xml();
	}
}
?>
