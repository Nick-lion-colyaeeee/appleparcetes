<?
ini_set("max_execution_time", 0);

define("EF", dirname(__FILE__) ."/parcing_final.csv");	

$files = [
	dirname(__FILE__) ."/AFM.csv",
	dirname(__FILE__) ."/Madstore.csv",
	dirname(__FILE__) ."/AppLand.csv",
	dirname(__FILE__) ."/iMobile.csv",
	dirname(__FILE__) ."/iPhorya.csv",
];

$all_names = [];
$final = [];

function get_data($file){
	$data = [];
	$start_show = 0; 
	if (($handle = fopen($file, "r")) !== FALSE) {
	    while (($row = fgetcsv($handle, 2000, ",")) !== FALSE) {
	       if ($start_show) {
	       	array_push($data, $row);	
	       }
	       $start_show++;
	    }
	    fclose($handle);
	}
	return $data;
}

function store_data ($data, $file) {	 
	$fp = fopen($file, "w");
	fwrite($fp,"sep=,".PHP_EOL);
	foreach ($data as $fields) {
	    fputcsv($fp, $fields);
	}
	fclose($fp);
}

function standart_name($file){
	
	$products = get_data($file);
	$product_names = [];

	foreach ($products as $product) {
		$product_names[] = $product[0];
	}
	return $product_names;	
}

foreach ( $files as $file ){
	$products = standart_name($file);
	foreach ( $products as $k=>$v ){
		$all_names[] = $v;
	}
}

echo count($all_names);
$all_names = array_unique($all_names);
echo count($all_names);
sort($all_names);

foreach ($all_names as $product_name=>$name) {
	$pre_final = [];
	array_push($pre_final, $name);
	foreach ( $files as $file ){
		$products = get_data($file);
		$find_result = false;
		foreach ($products as $product) {
			if ($name == $product[0]) {
				array_push($pre_final, $product[1]);
				$find_result = true;
			}
		}
		if (!$find_result) array_push($pre_final, "");
	}
	array_push($final, $pre_final);
}
for ($i=0; $i < count($final); $i++) { 
	$for_equals = [];
	if ($final[$i][1] > 0 ) array_push($for_equals, $final[$i][1]);
	if ($final[$i][2] > 0 ) array_push($for_equals, $final[$i][2]);
	if ($final[$i][3] > 0 ) array_push($for_equals, $final[$i][3]);
	if ($final[$i][4] > 0 ) array_push($for_equals, $final[$i][4]);
	if ($final[$i][5] > 0 ) array_push($for_equals, $final[$i][5]);
	$final[$i][6] = min($for_equals);
	if ($final[$i][6] == $final[$i][1]) $final[$i][7]="AFM";
	if ($final[$i][6] == $final[$i][2]) $final[$i][7]="Madstore";
	if ($final[$i][6] == $final[$i][3]) $final[$i][7]="AppleLand";
	if ($final[$i][6] == $final[$i][4]) $final[$i][7]="iMobile";
	if ($final[$i][6] == $final[$i][5]) $final[$i][7]="iPhorya";
}
echo "<pre>";
print_r($final);
echo "</pre>";
$fp = fopen(EF, "w");
fwrite($fp,"sep=,".PHP_EOL);
fwrite($fp, ",AFM,Madstore,AppleLand,iMobile,iPhorya,Минимальная цена,Кто".PHP_EOL);
foreach ($final as $fields) {
	fputcsv($fp, $fields);
}
fclose($fp);

?>