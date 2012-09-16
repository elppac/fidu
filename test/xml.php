<?php
require_once '../fidu/FiduXML.php';

$xml = new FiduXml(); 
//$xml.query();
/*$xml = new DOMDocument();
$xml->load("template.xml");
$itemDom = $xml->getElementsByTagName("item");
print_r( $itemDom );
foreach($itemDom as $item){
	$name = $item->getElementsByTagName("name");
	echo $name->item(0)->nodeValue;
}*/
	 
/*if( $xml->addItem('2','name 2', '123456') ){
	print_r('add success');
}else{
	print_r('some item');
}*/

print_r($xml->getDataLikeJson('001','Hot Products') );

?>