<?php
/* Example authorization request handler */

class ExampleAuthorizationCallbackHandler extends PaynearmeCallback {
	public function handleRequest() {
		PnmLogger::debug('Entered /authorize');
	    # Do our authorization here...

        # This is where you verify the information sent with the
        # request, validate it within your system, and then return a
        # response. Here we just accept payments with order identifiers
        # starting with "TEST"
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
		
        # You can set custom receipt text here (if you want) - if you
        # don't want custom text, you can omit this.
		$auth->appendChild(
			$xml->createElement('receipt', $accept ? 'Thank you for your order!' : 'Order Declined'));
		$auth->appendChild(
			$xml->createElement('memo', 
				$accept ? gmdate('Y-M-D H:i:s') : "Invalid payment: {$this->site_order_identifier}"));

		PnmLogger::debug('End of /authorize, returning XML');

		$special = $this->handle_special_condition($this->site_order_annotation);
		if ($special == null) {
			return $xml->to_xml(); # Normal behavior
		} else {
			return $special;
		}
	}
}
?>
