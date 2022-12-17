<?php

include "simple_html_dom.php";

$url = 'https://medex.com.bd/companies/2/aci-limited/brands';
$url = 'data.html';

//
//$html = file_get_html($url);


function getAllPageLink($url)
{
    $pages = [];


    for ($i = 20; $i < 24; $i++) {
        $pages[] = "https://medex.com.bd/companies/2/aci-limited/brands?page=$i";
    }

//    $html = file_get_html($url);
//    $links = $html->find("a[class='page-link']");
//
//    foreach ($links as $link) {
//        $l = $link->getAttribute('href');
//        $pages[]['link'] = $l;
//    }
    return $pages;
}


//echo json_encode(getAllPageLink($url));

function getProductsFromUrl($url)
{

//    $html = file_get_html('data.html');
    $html = file_get_html($url);

    $divs = $html->find("div[class='col-xs-12 col-sm-6 col-lg-4']");

    $products = [];
    foreach ($divs as $div) {
        $links = $div->find('a');
        foreach ($links as $link) {

            $l = $link->getAttribute('href');
            $name = $link->find('div[class="col-xs-12 data-row-top"]', 0);
            $quantity = $link->find('div[class="col-xs-12 data-row-strength"]', 0);
            $generic_name = $link->find('div[class="col-xs-12"]', 0);
            $unit = $link->find('div[class="col-xs-12 packages-wrapper"]', 0);


            $unit_string = trim($unit->lastChild()->plaintext);
            $unit_array = explode(':', $unit_string);
            $unit_name = trim(isset($unit_array[0]) ? $unit_array[0] : ' ');
            $unit_price = trim((isset($unit_array[1]) ? $unit_array[1] : ' '));
//            try {
//                $unit_name = trim($unit_array[0]);
//                $unit_price = trim(($unit_array[1]));
//            } catch (\Exception $e) {
//                echo "Error: " . $unit_string;
//                echo "Error: " . $e->getMessage();
//
//            }


            $product['link'] = $l;
            $product['name'] = trim($name->plaintext);
            $product['type'] = trim($name->lastChild()->plaintext);
            $product['quantity'] = trim($quantity->lastChild()->plaintext);
            $product['generic_name'] = trim($generic_name->plaintext);
            $product['unit'] = str_replace('  ', '', $unit_string);
            $product['unit_name'] = $unit_name;
            $product['unit_price'] = $unit_price;

            $products[] = $product;
        }
    }

    return $products;
}

echo json_encode(getProductsFromUrl($url));

//echo "hello";


function parseAllProducts($url)
{

    $products = [];
    $links = getAllPageLink($url);

//    array_pop($links);
    echo json_encode($links);
//    $temp_products = getProductsFromUrl($url);
//    $products = array_merge($products, $temp_products);

    foreach ($links as $key => $link) {
        echo $link;
        $temp = getProductsFromUrl($link);
//        echo json_encode($temp);
        file_put_contents("output_$key.json", json_encode($temp));
//         $products = array_merge($products, $temp);
    }
    return $products;
}

//getProductsFromUrl($url);
//parseAllProducts($url);
//parseAllProducts($url);
//$output = json_encode(parseAllProducts($url));
//file_put_contents('output.json', $output);
//echo $output;