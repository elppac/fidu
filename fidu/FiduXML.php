<?php
class FiduXml{
	var $doc;
	var $filename;
	var $xpath;
	var $itemList = null;
	function FiduXml( ){
		$this->load( dirname(dirname(__FILE__)).'/data/template.xml' );
	}
	function load( $path ){
		$this->filename = $path;
		$this->doc = new DOMDocument();
		$this->doc->preserveWhiteSpace = false;
		$this->doc->load( $path );
		
		$this->xpath = new DOMXpath(  $this->doc );
	}
	function query( $queryString ){
		//$xml->xpath->query("//item[@queryId='name 11']");
		return $xpath->query( $queryString );
	}
	function getItemList(){
		if( $this->itemList == null ){
			$itemDom = $this->doc->getElementsByTagName("item");
			$this->itemList = array();
			foreach( $itemDom as $item ){ 
				$name = $item->getElementsByTagName("name")->item(0)->nodeValue;
				$data = $item->getElementsByTagName("data")->item(0)->nodeValue;
				$id = $item->getElementsByTagName("id")->item(0)->nodeValue;
				array_push( $this->itemList, array(
					'name' => $name,
					'data' => $data,
					'id' => $id
				));
			}
		}
		return $this->itemList;
	}
	function getItem($id, $name){
		foreach( $this->getItemList()  as $item ){
			if( $item['id'] == $id && $item['name'] == $name ){
				return $item;
			}
		}
		return null;
	}
	function getData($id, $name){
		$item = $this->getItem($id, $name);
		if( $item !=null){
			return $item['data'];
		}
		return null;
	}
	function getDataLikeJson( $id, $name){
		$dataString = $this->getData( $id, $name);
		if( $dataString ==null){
			return null;
		}
		if (get_magic_quotes_gpc()){
			$dataString = stripslashes($dataString);
		}
		$data = json_decode($dataString);
		 
		$json = array(); 
		if( is_array($data) ){
			foreach( $data as $item ){
				$json[$item[0]] = $item[1];
			}
		}
		return $json;
	}
	function hasItem ( $id, $name ){
		if( $this->getItem( $id, $name ) == null ){
			return false;
		}else{
			return true;
		}
	}
	function reload (){
		$this->itemList = null;
	}
	function _newItem( $id, $name, $data ){
		$itemElement = $this->doc -> createElement( 'item' );
		$itemElement->setAttribute("queryId", $name.$id);
		
		$idElement = $this->doc -> createElement( 'id' );
		$idNode = $this->doc -> createTextNode($id);
		$idElement -> appendChild( $idNode );
		
		$nameElement = $this->doc -> createElement( 'name' );
		$nameNode = $this->doc -> createTextNode($name);
		$nameElement -> appendChild( $nameNode );
		
		$dataElement = $this->doc -> createElement( 'data' );
		
		$dataNode = $this->doc -> createCDATASection($data);
		$dataElement -> appendChild( $dataNode );
		
		$itemElement -> appendChild( $idElement );
		$itemElement -> appendChild( $nameElement );
		$itemElement -> appendChild( $dataElement );
		return $itemElement; 
	}
	function editItem( $id, $name, $data ){
		if( $this->hasItem( $id, $name ) ){	
			$itemElement = $this-> _newItem( $id, $name, $data );
			return true;
		}else{
			return false;
		}
	}
	function addItem( $id, $name, $data ){
		if( $this->hasItem( $id, $name ) ){
			return false;
		}else{
			$itemElement = $this-> _newItem( $id, $name, $data );
			$items = $this->doc->getElementsByTagName("items")->item(0);
			$items ->appendChild( $itemElement );
			$this->doc ->formatOutput = true;
			$this->doc -> save( $this->filename );
			//$this->reload();
			return true;
		}
	}
}
?>