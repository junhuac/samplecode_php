<?php
/** 
 * PayNearMe Callbacks API - PHP implementation details
 * PHP v5.3.x+ supported
 *
 * This is a base class to be extended by your own callback implementations.
 * This pulls out params and provides a common interface for handling the requests.
 */

abstract class PaynearmeCallback {
  protected static $EXCLUDED_PARAMS = array('signature', 'call', 'fp', 'print_buttons');

  protected $secret = '';
  protected $params;
  protected $calculated_sig, $request_sig, $version, $timestamp, $site_order_identifier, 
          $pnm_order_identifier, $test;

	function __construct($secret = '', $params) {
    $this->secret = $secret;
    $this->params = $params;

    $this->calculated_sig = $this->signature($params);
    $this->request_sig = $params['signature'];

    $this->version = $params['version'];
    $this->timestamp = $params['timestamp'];
    $this->site_order_identifier = $params['site_order_identifier'];
    $this->pnm_order_identifier = $params['pnm_order_identifier'];
    $this->site_order_annotation = $params['site_order_annotation'];
    $this->test = $params['test'] == true;

    # Warn if the sig is invalid, DEBUG will show valid outcomes.
    PnmLogger::log($this->valid_signature() ? PnmLogger::DEBUG : PnmLogger::WARN, 
      sprintf("Signature %s was %s, expected: %s", 
        $this->request_sig, $this->valid_signature() ? 'valid' : 'invalid', $this->calculated_sig));

    if ($this->test === true) {
      PnmLogger::warn(
        "This order is a TEST. Do not handle as a financial event! ($this->site_order_identifier)");
    }
	}

  abstract public function handleRequest();

  // ----------
  // Utilities
  // ----------

  public function valid_signature() {
    return $this->calculated_sig === $this->request_sig;
  }

  # Test hackery - returns a response - if nil, handle normally
  protected function handle_special_condition($arg) {
    if (empty($arg)) {
          return null;
    } elseif (preg_match("/^confirm_delay_([0-9]+)/", $arg, $matches)) {
      PnmLogger::info("Delaying response by {$matches[1]} seconds");
      sleep($matches[1]);
    } elseif ($arg == 'confirm_bad_xml') {
      PnmLogger::info('Responding with bad/broken xml');
      return '<result';
    } elseif ($arg == 'confirm_blank') {
      PnmLogger::info('Responding with a blank response');
      return '';
    } elseif ($arg == 'confirm_redirect') {
      PnmLogger::info('Redirecting to /');
      header('Location: /');
    }
    PnmLogger::debug('Reached end of #handle_special_condition, returning null - handle rest of response as usual.');
    return null;
  }

  // ----------
	// private methods
  // ----------

	private function signature($params) {
    PnmLogger::info('Generating signature...');
		$str = '';
    ksort($params);
    foreach ($params as $k => $v) {
      if (!in_array($k, self::$EXCLUDED_PARAMS)) {
        $str .= "$k$v";
        PnmLogger::info("\tparam: $k => $v");
      }
    }
    $sig = md5($str . $this->secret);
    PnmLogger::debug('Signature: ' . $sig);
    return $sig;
	}
}

?>