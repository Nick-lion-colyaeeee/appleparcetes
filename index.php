<?php

ini_set('max_execution_time', 0);

include_once 'simple_html_dom.php';

// define('STORE_FILE', dirname(__FILE__) .'/iMobile.csv');	// file for collecting analitycal information from parser
// define('STORE_FILE', dirname(__FILE__) .'/AppLand.csv');	// file for collecting analitycal information from parser
// define('STORE_FILE', dirname(__FILE__) .'/Madstore.csv');	// file for collecting analitycal information from parser
// define('STORE_FILE', dirname(__FILE__) .'/AFM.csv');	// file for collecting analitycal information from parser
define('STORE_FILE', dirname(__FILE__) .'/iPhorya.csv');	// file for collecting analitycal information from parser

function getPage($url){

	$ch = curl_init (); 
	curl_setopt ($ch , CURLOPT_URL , $url);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0"); 
	curl_setopt ($ch , CURLOPT_RETURNTRANSFER , 1);
	$html = curl_exec($ch); 
	curl_close($ch); 

	return $html;

}

function parser_imobile() {

	$catalog = [];
	$parsing_links = [
		'http://www.iphone-nn.ru/catalog/iphone_5s/',
		'http://www.iphone-nn.ru/catalog/iphone_6S/',
		'http://www.iphone-nn.ru/catalog/iphone_6S_plus/',
		'http://www.iphone-nn.ru/catalog/iphone_5se/',
		'http://www.iphone-nn.ru/catalog/iphone_7/',
		'http://www.iphone-nn.ru/catalog/iphone_7_plus/',
		'http://www.iphone-nn.ru/catalog/ipad_air_2/',
		'http://www.iphone-nn.ru/catalog/ipad_mini_4/',
		'http://www.iphone-nn.ru/catalog/ipad_pro/',
		'http://www.iphone-nn.ru/catalog/ipad_pro_9_7/'
		];

	foreach ($parsing_links as $link) {
		
		$html = new simple_html_dom();
		$html->load(getPage($link));
		$items_grid = $html->find('.grid-item');
			foreach ($items_grid as $item) {
				$product =[];
				$item_name = array_push($product, str_replace(' Гб', 'gb', iconv("Windows-1251", "UTF-8", $item->find('h1',0)->plaintext)));
				$item_price = array_push($product, intval(substr(preg_replace("/[^a-zA-ZА-Яа-я0-9]/", "",$item->find('h2',0)->plaintext), 0,-4)));
				array_push($catalog, $product);
			}

		$html->clear(); 
		unset($html);
	}

	return $catalog;
}

function parser_AppLand() {

	$catalog = [];
	$parsing_links = [
		'http://apple-nn.ru/catalogue/iphones/',
		'http://apple-nn.ru/catalogue/apple-iphone-5s/',
		'http://apple-nn.ru/catalogue/apple-iphone-se/',
		'http://apple-nn.ru/catalogue/iphone_6/iphone6-sale/',
		'http://apple-nn.ru/catalogue/iphone-6S/apple-iphone-6S/',
		'http://apple-nn.ru/catalogue/iphone-6S/apple-iphone-6S-plus/',
		'http://apple-nn.ru/catalogue/iphone-7-iphone-7-plus/apple-iphone-7/',
		'http://apple-nn.ru/catalogue/iphone-7-iphone-7-plus/apple-iphone-7-plus/',
		'http://apple-nn.ru/catalogue/ipad/ipad-air-2/',
		'http://apple-nn.ru/catalogue/ipad-mini/ipad-mini-4/',
		'http://apple-nn.ru/catalogue/ipad/ipad-pro/',
		'http://apple-nn.ru/catalogue/ipad/appleipadpro97inch/'
	];

	foreach ($parsing_links as $link) {
		
		$html = new simple_html_dom();
		$html->load(getPage($link));
		$items_grid = $html->find('.cat_item');
			foreach ($items_grid as $item) {
				$product =[];
				$item_name = array_push($product, str_replace('Gb', 'gb', ltrim($item->find('.ci_title',0)->plaintext)));
				$item_price = array_push($product, intval(substr(preg_replace("/[^a-zA-ZА-Яа-я0-9]/", "",$item->find('.cip',0)->plaintext), 0,-4)));
				array_push($catalog, $product);
			}

		$html->clear(); 
		unset($html);
	}

	return $catalog;
}

function parser_Madstore() {

	$catalog = [];
	$parsing_links = [
		'http://madstore.ru/product/devices/i%D1%80hone/iphone5s/?_route_=product%2Fdevices%2Fi%D1%80hone%2Fiphone5s%2F&product_count=60',
		'http://madstore.ru/product/devices/i%D1%80hone/iphone-se/?_route_=product%2Fdevices%2Fiрhone%2Fiphone-se%2F&product_count=60',
		'http://madstore.ru/product/devices/i%D1%80hone/iphone-6S/?_route_=product%2Fdevices%2Fiрhone%2Fiphone-6S%2F&product_count=60',
		'http://madstore.ru/product/devices/i%D1%80hone/iphone-7/?_route_=product%2Fdevices%2Fiрhone%2Fiphone-7%2F&product_count=60',
		'http://madstore.ru/product/devices/ipad/ipadair2/?_route_=product%2Fdevices%2Fipad%2Fipadair2%2F&product_count=60',
		'http://madstore.ru/product/devices/ipad/ipadair/?_route_=product%2Fdevices%2Fipad%2Fipadair%2F&product_count=60',
	];

	foreach ($parsing_links as $link) {
		
		$html = new simple_html_dom();
		$html->load(getPage($link));
		$items_grid = $html->find('.product');
			foreach ($items_grid as $item) {
				$product =[];
				$item_name = array_push($product, str_replace(' Ростест', '', ltrim($item->find('h3',0)->plaintext)));
				$item_price = array_push($product, intval(
					substr(preg_replace("/[^a-zA-ZА-Яа-я0-9]/", "",$item->find('.woocommerce-Price-amount',0)->plaintext), 0,-4)));
				array_push($catalog, $product);
			}

		$html->clear(); 
		unset($html);
	}

	return $catalog;
}

function parser_iPhorya() {

	$catalog = [];
	$parsing_links = [
		'http://www.iphoriya.ru/catalog/2/iphone.html',
		'http://www.iphoriya.ru/catalog/4/ipad.html',
	];

	foreach ($parsing_links as $link) {
		
		$html = new simple_html_dom();
		$html->load(getPage($link));
		$items_grid = $html->find('.div_prod');
			foreach ($items_grid as $item) {
				$product =[];
				$item_name = array_push($product, str_replace(' Ростест', '', ltrim($item->find('.div_prod_link',0)->plaintext)));
				$item_price = array_push($product, intval(
					substr(preg_replace("/[^a-zA-ZА-Яа-я0-9]/", "",$item->find('.prod_price',0)->plaintext), 0,-4)));
				array_push($catalog, $product);
			}

		$html->clear(); 
		unset($html);
	}

	return $catalog;
}

function parser_AFM() {

	$catalog = [];
	for ($k=3; $k<=4; $k++){
		for ($i=1; $i < 7; $i++) { 
			$product_pages[]=json_decode(file_get_contents("http://afmcenter.ru/api/products/?category_id=$k&direction=desc&order=order&page=$i"), true);
		}
	}


	foreach ($product_pages as $page) {
		
		foreach ($page['data'] as $item){
			$product =[];
			$item_name = array_push($product, $item['name']);
			$item_price = array_push($product, $item['price']);
			array_push($catalog, $product);
		}
	}

	return $catalog;
}

/*Saving all parsed data in to the database */	
function storeData ($data_array) {
	 
	$fp = fopen(STORE_FILE, 'w');
	fwrite($fp,'sep=,'.PHP_EOL);
	// fwrite($fp,' ,iMobile' . PHP_EOL);
	foreach ($data_array as $fields) {
	    fputcsv($fp, $fields);
	}

	fclose($fp);
}

storeData(parser_iPhorya());

// echo '<pre>';
// 	print_r(parser_AFM());
// echo '</pre>';
?>
