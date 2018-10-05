<?php
require "./vendor/autoload.php";
use masokky\QuoteMaker;
try{
	$text = "the cruelest crime is giving false hope without love";
	(new QuoteMaker)
			->setBackgroundFromUnsplash(["b353e61a07cc0068080258kc0294ks85042f2560d6223366500a2aa30ff28052"],"heart")
			->quote($text)
			->watermark("Mas Okky")
			->toFile("result.jpg");
}catch(Exception $e){
	echo $e->getMessage();
}