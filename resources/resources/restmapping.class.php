<?php
/*
 *  This file is part of Restos software
 * 
 *  Restos is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  Restos is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with Restos.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class to manage the resources mapping
 *
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.WebServices.Restos
 * @version 0.1
 */
class RestMapping {
    
    /**
     * 
     * Object or objects array for mapping
     * @var object or array
     */
    protected $_data;
    
    /**
     * 
     * Name of root xml element
     * @var string
     */
    protected $_resourceLabel;
    
    /**
     * 
     * Name of root xml element if $_data is array
     * @var string
     */
    protected $_resourcesGroupLabel;
    
    /**
     * 
     * The XML Document to save the answer
     * @var DOMDocument
     */
    public $XmlDocument;
    
    /**
     * 
     * The Object to save the answer in JSon request
     * @var object
     */
    public $ObjectContent;
    
    /**
     * 
     * The HTML string to save the answer
     * @var string
     */
    public $Html;
    
    /**
     * 
     * Contruct
     * @param object or array $data
     * @param string $resource
     * @param string $resources_group
     */
    public function __construct ($data, $resource = 'resource', $resources_group = 'resources'){
        $this->_data = $data;
        $this->_resourceLabel = $resource;
        $this->_resourcesGroupLabel = $resources_group;
        $this->XmlDocument = new DOMDocument('1.0', 'UTF-8');
        $this->ObjectContent = new stdClass();
    }
    
    public function getMapping($type) {
        switch (strtoupper($type)){
            case 'XML':
                return $this->getXml();
            case 'JSON':
                return $this->getJson();
            case 'HTML':
                return $this->getHtml();
            default:
                return null;
        }
    }
    
    /**
     * 
     * Create a XML document to response
     * @return DOMDocument
     */
    public function  getXml () {
        $namespaces = array();
        
        if(!is_array($this->_data)){
            $root = $this->getXmlElement($this->_resourceLabel, $this->XmlDocument, $this->_data, $namespaces);
            $this->XmlDocument->appendChild($root);
        }
        else {
            $nodes = $this->getXmlNodeCollection($this->_resourceLabel, $this->XmlDocument, $this->_data, $namespaces);
            
            $root = $this->XmlDocument->createElement($this->_resourcesGroupLabel);
            $root = $this->XmlDocument->appendChild($root);
            
            foreach($nodes as $node){
                $root->appendChild($node);
            }
        }

        
        //$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', Restos::URINamespace($this->_resourcesGroupLabel));
        $root->setAttribute('xmlns', Restos::URINamespace($this->_resourcesGroupLabel));
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
        
        foreach ($namespaces as $namespace) {
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:' . $namespace->PrefixNamespace, $namespace->URI);
        }

        return $this->XmlDocument;
    }

    /**
     * 
     * Create xml elements collection
     * 
     * @param string $element_type Tag for the element name
     * @param DomDocument $document
     * @param array $collection
     * @param array $namespaces
     * @return array or DOMElement
     */
    protected function getXmlNodeCollection($element_type, $document, $collection, &$namespaces, $isResource = false) {

        $nodes = array();

        $is_asociative = true;
        foreach(array_keys($collection) as $key){
            if (is_numeric($key)){
                $is_asociative = false;
                break;
            }
        }

        $index = 0;
        foreach($collection as $key => $data){
            
            $tag_name = !$is_asociative ? $element_type : $key;
            if (is_object($data)) {
                $element = $this->getXmlElement($tag_name, $document, $data, $namespaces);
                
                if (!empty($data->about)) {
                    $element->setAttributeNode(new DOMAttr('rdf:about', $data->about));
                }
                
                $nodes[] = $element;
            }
            else if (is_array($data)){
                if (count($data) > 0) {

                    $inter_nodes = $this->getXmlNodeCollection($tag_name, $document, $data, $namespaces, $isResource);

                    foreach($inter_nodes as $inter){
                       $nodes[] = $inter;
                    }
                }
            }
            else {
                if ($isResource) {
                    $element = $document->createElement($tag_name);
                    $element->setAttributeNode(new DOMAttr('rdf:resource', $data));
                    $nodes[] = $element;
                }
                else {
                    $nodes[] = $document->createElement($tag_name);
                    $nodes[$index]->appendChild($document->createCDATASection($data));
                }
            }
            $index++;
        }

        if ($is_asociative) {
            $group_nodes = $document->createElement($element_type);
            
            foreach ($nodes as $node) {
                $group_nodes->appendChild($node);
            }
            return array($group_nodes);
        }
        return $nodes;
    }
    
    /**
     * 
     * Create a xml element according to own properties
     * 
     * @param string $element_type Tag for the element name
     * @param DomDocument $document
     * @param object $entity
     * @param array $namespaces
     */
    protected function getXmlElement($element_type, $document, $entity, &$namespaces) {
        
        $global_prefix = '';
        if (property_exists($entity, 'TargetNamespace')) {
            $namespaces[] = $entity->TargetNamespace;
            $global_prefix = $entity->TargetNamespace->PrefixNamespace . ':';
        }
        
        if (property_exists($entity, 'Namespaces')) {
            $namespaces = array_merge($namespaces, $entity->Namespaces);
        }

        $element = $document->createElement($element_type);
        
        $reflection_user = new ReflectionClass($entity);
        $properties = $reflection_user->getProperties();
        
        foreach ($properties as $property){
            $prop_name = $property->getName();
            
            if (!in_array($prop_name, $entity->CoreProperties)) {

                $prefix = $global_prefix;
                
                //If the entity class define a namespaces collection
                if (property_exists($entity, 'Namespaces')) { 
                    foreach ($entity->Namespaces as $some_namespace) {
                        if (in_array($prop_name, $some_namespace->Properties)) {
                            $prefix = $some_namespace->PrefixNamespace . ':';
                            break;
                        }
                    }
                }

                $value = $entity->$prop_name;
                if (!empty($value) || $value === false || $value === 0) {
                    if ($prop_name != 'about' && $prop_name != 'seeAlso') {
                        $isResource = false;
                        if (method_exists($entity, 'isResource') && $entity->isResource($prop_name)) {
                             $isResource = true;
                        }

                        if (is_array($value)) {
                            if (count($value) > 0) {
                                
                                $nodes = $this->getXmlNodeCollection($prefix . $prop_name, $document, $value, $namespaces, $isResource);
            
                                foreach($nodes as $node){
                                    $element->appendChild($node);
                                }
                            }
                        }
                        else if (is_object($value)) {
                            $node = $this->getXmlElement($prefix . $prop_name, $document, $value, $namespaces);
                            $element->appendChild($node);
                        }
                        else {
                            if ($isResource) {
                                $node = $document->createElement($prefix . $prop_name);
                                $node->setAttributeNode(new DOMAttr('rdf:resource', $value));
                            }
                            else {
                                if ($value === false) {
                                    $value = "false";
                                }
                                $node = $document->createElement($prefix . $prop_name);
                                $node->appendChild($document->createCDATASection($value));
                            }
                            $element->appendChild($node);
                        }
                    }
                }
            }
        }

        if ($entity->seeAlso !== false) {
            if (!empty($entity->seeAlso)) {
                $node = $document->createElement('rdfs:seeAlso');
                $node->setAttributeNode(new DOMAttr('rdf:resource', $entity->seeAlso));
                $element->appendChild($node);
            }
            else if (!empty($entity->about)) {
                $node = $document->createElement('rdfs:seeAlso');
                $node->setAttributeNode(new DOMAttr('rdf:resource', Restos::URIRest($this->_resourcesGroupLabel . "/" . $entity->about)));
                $element->appendChild($node);
            }
        }
        

        return $element;
    }
    
    /**
     * 
     * Create an object or array to response with data for json encode
     * @return object or array
     */
    public function  getJson () {

        if(!is_array($this->_data)){
            $name_property = $this->_resourceLabel;
            //$this->ObjectContent->$name_property = $this->getObjectElement($this->_data);
            $this->ObjectContent = $this->getObjectElement($this->_data);
        }
        else {
            $name_property = $this->_resourcesGroupLabel;

            $this->ObjectContent = $this->getObjectCollection($this->_data);
        }

        return $this->ObjectContent;
    }

    /**
     * 
     * Create array of from collection
     * 
     * @param array $collection
     * @return array or object
     */
    protected function getObjectCollection($collection) {

        $nodes = array();

        foreach($collection as $key => $data){
            
            if (is_object($data)) {
                $nodes[$key] = $this->getObjectElement($data);
            }
            else if (is_array($data)){
                if (count($data) > 0) {
                    $nodes[$key] = $this->getObjectCollection($data);
                }
            }
            else {
                $nodes[$key] = $data;
            }
        }

        return $nodes;
    }
    
    /**
     * 
     * Create an object according to own properties
     * 
     * @param object $entity
     * @return object
     */
    protected function getObjectElement($entity) {
        
        $element = new stdClass();
        
        $reflection_user = new ReflectionClass($entity);
        $properties = $reflection_user->getProperties();
        
        foreach ($properties as $property){
            $prop_name = $property->getName();
            
            if (!in_array($prop_name, $entity->CoreProperties)) {

                $value = $entity->$prop_name;
                if (!empty($value) || $value === false || $value === 0) {
                    if ($prop_name != 'seeAlso' && ($prop_name != 'about' || $value !== false)) {
                        if (is_array($value)) {
                            if (count($value) > 0) {
                                $element->$prop_name = $this->getObjectCollection($value);
                            }
                        }
                        else if (is_object($value)) {
                            $element->$prop_name = $this->getObjectElement($value);
                        }
                        else {
                            $element->$prop_name = $value;
                        }
                    }
                }
            }
        }

        if ($entity->seeAlso !== false) {
            if (!empty($entity->seeAlso)) {
                $element->seeAlso = $entity->seeAlso;
            }
            else if (!empty($entity->about)) {
                $element->seeAlso = Restos::URIRest($this->_resourcesGroupLabel . "/" . $entity->about);
            }
        }

        return $element;
    }
    
    /**
     * 
     * Create a HTML document to response
     * @return string
     */
    public function getHtml() {      
        if(!is_array($this->_data)){
            $this->Html = $this->getHtmlElement($this->_data);
        }
        else {
            $this->Html = $this->getHtmlCollection($this->_data);
        }

        return $this->Html;
    }
    
    /**
     * 
     * Create a HTML for a collection
     * 
     * @param array $collection
     * @return string
     */
    protected function getHtmlCollection($collection) {
        $element = '<table width="100%">';
        
        foreach($collection as $key => $data){
            if (is_object($data)) {
                $element .= '<tr><td valign="top">' . $this->getHtmlElement($data) . '</tr></td>';
            }
            else if (is_array($data)){
                if (count($data) > 0) {
                    $element .= '<tr><td valign="top">' . $this->getHtmlCollection($data) . '</tr></td>';
                }
            }
            else {
                $element .= $data . "<br />";
            }
        }
        
        $element .= '</table>';
        
        return $element;
    }
    
    /**
     * 
     * Create a HTML for an object according to own properties
     * 
     * @param object $entity
     * @return string
     */
    protected function getHtmlElement($entity) {
        
        $element = '<table border=1 width="100%">';
        
        $reflection_user = new ReflectionClass($entity);
        $properties = $reflection_user->getProperties();
        
        foreach ($properties as $property){
            $prop_name = $property->getName();
            
            if (!in_array($prop_name, $entity->CoreProperties)) {

                $value = $entity->$prop_name;
                
                if (!empty($value) || $value === false || $value === 0) {
                    if ($prop_name != 'seeAlso' && ($prop_name != 'about' || $value !== false)) {
                        if (is_array($value)) {
                            if (count($value) > 0) {
                                $element .= '<tr><td valign="top"><b>' . $prop_name . ':</b></td><td valign="top">' . $this->getHtmlCollection($value) . "</td></tr>";
                            }
                        }
                        else if (is_object($value)) {
                            $element .= '<tr><td valign="top"><b>' . $prop_name . ':</b></td><td valign="top">' . $this->getHtmlElement($value) . "</td></tr>";
                        }
                        else {
                            $element .= '<tr><td valign="top"><b>' . $prop_name . ':</b></td><td valign="top">' . $value . "</td></tr>";
                        }
                    }
                }
            }
        }
        
        if ($entity->seeAlso !== false) {
            if (!empty($entity->seeAlso)) {
                $element .= '<tr><td valign="top"><b>seeAlso:</b></td><td valign="top">' . $entity->seeAlso . "</td></tr>";
            }
            else if (!empty($entity->about)) {
                $element .= '<tr><td valign="top"><b>seeAlso:</b></td><td valign="top">' . Restos::URIRest($this->_resourcesGroupLabel . "/" . $entity->about) . "</td></tr>";
            }
        }
        
        $element .= '</table>';

        return $element;
    }
}