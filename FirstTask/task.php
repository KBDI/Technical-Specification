<?php 

include "lib/simple_html_dom.php";

class FirstTask
{

	const SITE = 'https://vashidveri72.ru';
	const CATEGORY = 'https://vashidveri72.ru/catalog/dveri_vkhodnye/';

	function getHtml($site){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_URL, $site);
		curl_setopt($curl, CURLOPT_REFERER, $site);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$str = curl_exec($curl);
		curl_close($curl);

		$html_base = new simple_html_dom();
		$html_base->load($str);

		return $html_base;
	}

	function writeFile($array){
		$file = fopen('data.json','w+');
		fwrite($file, json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		fclose($file);
	}

	function generateArrayProducts(){
		$html = $this->getHtml(self::CATEGORY);
		$mainCategory = $html->find( 'li[class*=bx_hma_one_lvl current] div[class*=bx_children_block] a');

		$arrayToJson = array();
		foreach ($mainCategory as $subCategory) {
			$data = array(
				"category" => trim($subCategory->plaintext), 
				"url" => self::SITE . $subCategory->href,
				"data" => array(),
			);

			$currentSubcategory = $this->getHtml(self::SITE . $subCategory->href);

			for ($i=0; $i < count($currentSubcategory->find('div[class*=one_section_product_cells] div[class*=name_product] a')); $i++) { 

				$nameProduct = htmlspecialchars_decode(trim($currentSubcategory->find('div[class*=one_section_product_cells] div[class*=name_product] a', $i)->plaintext));
				$priceProduct = preg_replace('/[^0-9]/', '', $currentSubcategory->find('div[class*=one_section_product_cells] div[class*=new_price]', $i)->plaintext);
				$pictureProduct = self::SITE . explode("'", $currentSubcategory->find('div[class*=one_section_product_cells] a[class*=image_product]', $i)->style)[1];

				array_push($data["data"], array(
					"name" => $nameProduct,
					"price" => $priceProduct,
					"image" => $pictureProduct,
				));
			}
			
			array_push($arrayToJson, $data);
		}

		return $arrayToJson;
	}
}

?>