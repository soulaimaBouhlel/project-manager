<?php

namespace App\Services;

use DOMDocument;
use Exception;

class XmlManager
{
    /**
     * Validate XML content against a provided XSD schema.
     *
     * @param string $xmlContent
     * @param string $xsdPath
     * @return bool
     * @throws Exception
     */
    public function validateXML(string $xmlContent, string $xsdPath): bool
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;

        // Load XML content
        if (!$dom->loadXML($xmlContent)) {
            throw new Exception('Invalid XML format.');
        }

        // Validate against XSD
        if (!$dom->schemaValidate($xsdPath)) {
            throw new Exception('XML does not match the XSD schema.');
        }

        return true;
    }

    /**
     * Parse XML content into an associative array.
     *
     * @param string $xmlContent
     * @return array
     * @throws Exception
     */
    public function parseXML(string $xmlContent): array
    {
        $dom = new DOMDocument();
        if (!$dom->loadXML($xmlContent)) {
            throw new Exception('Invalid XML format.');
        }

        $xmlArray = $this->convertXmlToArray($dom->documentElement);
        return $xmlArray;
    }

    /**
     * Write data into an XML file.
     *
     * @param string $filePath
     * @param string $rootElement
     * @param array $data
     * @throws Exception
     */
    public function writeXML(string $filePath, string $rootElement, array $data)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        // Create root element
        $root = $dom->createElement($rootElement);
        $dom->appendChild($root);

        // Recursively add data
        $this->buildXmlElements($dom, $root, $data);

        // Save to file
        if (!$dom->save($filePath)) {
            throw new Exception('Failed to write XML to file.');
        }
    }

    /**
     * Convert XML element into an associative array recursively.
     *
     * @param \DOMElement $element
     * @return array|string
     */
    private function convertXmlToArray(\DOMElement $element)
    {
        $result = [];

        // If the element has children
        foreach ($element->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                $value = $this->convertXmlToArray($child);

                if (isset($result[$child->nodeName])) {
                    // Convert to array if multiple elements have the same name
                    if (!is_array($result[$child->nodeName]) || !isset($result[$child->nodeName][0])) {
                        $result[$child->nodeName] = [$result[$child->nodeName]];
                    }
                    $result[$child->nodeName][] = $value;
                } else {
                    $result[$child->nodeName] = $value;
                }
            } elseif ($child instanceof \DOMText && trim($child->nodeValue) !== '') {
                return $child->nodeValue;
            }
        }

        return $result;
    }

    /**
     * Recursively build XML elements from array data.
     *
     * @param DOMDocument $dom
     * @param \DOMElement $parent
     * @param array $data
     */
    private function buildXmlElements(DOMDocument $dom, \DOMElement $parent, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $child = $dom->createElement($key);
                    $this->buildXmlElements($dom, $child, (array) $item);
                    $parent->appendChild($child);
                }
            } else {
                $child = $dom->createElement($key, htmlspecialchars($value));
                $parent->appendChild($child);
            }
        }
    }
}
