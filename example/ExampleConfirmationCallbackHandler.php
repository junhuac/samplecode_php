<?php
/* Example authorization request handler */

class ExampleConfirmationCallbackHandler extends PaynearmeCallback {
	public function handleRequest() {
    PnmLogger::debug('Entered /confirm');

    # Do our confirmation here...
    $status = $this->params['status'];

    # You must lookup the pnm_order_identifier in your business system and
    # prevent double posting. In the event of a duplicate, ignore the
    # posting (do not reply) if you have already responded at least once
    # for the pnm_order_identifier in question.
    # No stub code is provided for this check, and is left to the
    # responsibility of the implementor.
    #
    # $this->pnm_order_identifier will be of interest.

    $xml = new CallbackXmlBuilder('payment_confirmation_response');
    $confirm = $xml->createElement('confirmation');
    $xml->appendChild($confirm);

    if ($status == 'decline') {
      PnmLogger::warn('This order is declined - do not post a financial event, but respond normally!');
    }

    $confirm->appendChild(
      $xml->createElement('pnm_order_identifier', $this->pnm_order_identifier));

    # Now that you have responded to a /confirm, you need to keep a record
    # of this pnm_order_identifier and DO NOT respond to any other
    # /confirm requests for that pnm_order_identifier.

    PnmLogger::debug('End of /confirm, returning XML');
    $special = $this->handle_special_condition($this->site_order_annotation);
    if ($special == null) {
      return $xml->to_xml();
    } else {
      return $special;
    }
  }
}
?>
