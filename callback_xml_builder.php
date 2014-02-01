<?php
/**
 * CallbackXmlBuilder
 * Build xml responses
 */

class CallbackXmlBuilder {
  private $doc;
  private $root;
  public function __construct($root, $api='2.0') {
    $this->doc = new DOMDocument('1.0', 'UTF-8');
    $this->doc->formatOutput = true;
    $api = str_replace('.', '_', $api);
    $this->root = $this->doc->createElementNS(
      "http://www.paynearme.com/api/pnm_xmlschema_v$api", "t:$root");
    $this->doc->appendChild($this->root);
  }

  public function createElement($name, $value="") {
    $element = $this->doc->createElement("t:$name", $value);
    #$this->root->appendChild($element);
    return $element;
  }

  /**
   * Append a node to the root node of the document
   */
  public function appendChild($node) {
    $this->root->appendChild($node);
  }

  public function to_xml() {
    return $this->doc->saveXML();
  }
}
?>