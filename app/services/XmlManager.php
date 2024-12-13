<?php

class XmlManager {

    private $xmlFilePath;

    public function __construct($xmlFilePath)
    {
        $this->xmlFilePath = $xmlFilePath;
    }

    /**
     * Serialize data to XML using DOMDocument
     *
     * @param array $data
     * @return string
     */
    public function serializeToXml($data)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true; // Make the output human-readable with indentation

        // Create the root element based on the type of data
        $root = $dom->createElement('root');
        $dom->appendChild($root);

        // Iterate over the data array and add elements
        foreach ($data as $key => $value) {
            $element = $dom->createElement($key, $value);
            $root->appendChild($element);
        }

        // Save XML to string and return
        return $dom->saveXML();
    }

    /**
     * Deserialize XML to an associative array
     *
     * @return array
     */
    public function deserializeFromXml()
    {
        $dom = new DOMDocument();
        if (!$dom->load($this->xmlFilePath)) {
            throw new Exception("Error loading XML file.");
        }

        // Convert the XML to an associative array
        $data = [];
        $root = $dom->documentElement;
        foreach ($root->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $data[$node->nodeName] = $node->nodeValue;
            }
        }

        return $data;
    }

    /**
     * Validate XML against a schema
     *
     * @param string $xsdPath Path to the XSD schema file
     * @return bool
     */
    public function validateXml($xsdPath)
    {
        $dom = new DOMDocument();
        if (!$dom->load($this->xmlFilePath)) {
            throw new Exception("Error loading XML file.");
        }

        // Validate the XML file against the XSD schema
        return $dom->schemaValidate($xsdPath);
    }

    /**
     * Save XML to a file
     *
     * @param string $xmlData
     * @return void
     */
    public function saveXmlToFile($xmlData)
    {
        file_put_contents($this->xmlFilePath, $xmlData);
    }
}

