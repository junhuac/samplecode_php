<?php
/* Example authorization request handler */

class ExampleConfirmationCallbackHandler extends PaynearmeCallback {
	public function handleRequest() {
    PnmLogger::debug('Entered /confirm');

    # Do our confirmation here...
    $status = $this->params['status'];

    $xml = new CallbackXmlBuilder('payment_confirmation_response');
    $confirm = $xml->createElement('confirmation');
    $xml->appendChild($confirm);

    if ($status == 'decline') {
      PnmLogger::warn('This order is declined - do not post a financial event, but respond normally!');
    }

    $confirm->appendChild(
      $xml->createElement('pnm_order_identifier', $this->pnm_order_identifier));

    PnmLogger::debug('End of /confirm, returning XML');
    return $xml->to_xml();
  }
}
?>
