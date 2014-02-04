<?php
/** 
 * PayNearMe Callbacks API - PHP implementation details
 * PHP v5.3.x+ supported
 *
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
    $this->test = $params['test'] == true;

    PnmLogger::info('Params: ' + print_r($params, true));

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