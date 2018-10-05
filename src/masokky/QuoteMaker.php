<?php

/**
 * QuoteMaker a simple library to create quote image
 * Inspired from @yang.terdalam instagram account
 * 
 * Source: https://github.com/masokky/QuoteMaker
 *
 * Feel free to develop and maintain this library
 *
 * Thanks to all parties that support it
 * And especially you :)
 *
 * Licensed under MIT Licensed <http://opensource.org/licenses/MIT>
 */

namespace masokky;

class QuoteMaker {
	/**
	 * Quote text
	 * @var string
	 */
	protected static $quote;

	/**
	 * Watermark text
	 * @var string
	 */
	protected static $watermark;

	/**
	 * Background keyword
	 * @var string
	 */
	protected static $background_keyword = "random";

	/**
	 * Background image
	 * @var Base64 format
	 */
	protected static $background;

	/**
	 * Default quote font
	 * @var string
	 */
	protected static $quote_font = __DIR__."/assets/font/BiminiCondensed.ttf";

	/**
	 * Default watermark font
	 * @var string
	 */
	protected static $watermark_font = __DIR__."/assets/font/Goldfinger Kingdom.ttf";

	/**
	 * Default top quote mark
	 * @var string
	 */
	protected static $top_quote_mark = __DIR__."/assets/img/quote-mark-top.png";

	/**
	 * Default bottom quote mark
	 * @var string
	 */
	protected static $bottom_quote_mark = __DIR__."/assets/img/quote-mark-bottom.png";

	/**
	 * Quote font size
	 * @var int
	 */
	protected static $quote_font_size = 30;

	/**
	 * Watermark font size
	 * @var int
	 */
	protected static $watermark_font_size = 65;

	/**
	 * Y point as zero point
	 * @var int
	 */
	protected static $y_start_at = -70;

	/**
	 * Line height
	 * @var int
	 */
	protected static $line_height = 50;

	/**
	 * Client id for unsplash.com
	 * @var array
	 */
	protected static $client_id;

	/**
	 * Base url api unsplash
	 * @var string
	 */
	protected static $unsplash_api_url = "https://api.unsplash.com/";

	/**
	 * Image object
	 * @var object
	 */
	protected static $image;

	/**
	 * Watermark object
	 * @var object
	 */
	protected static $watermark_image;
	
	public function __construct(){
		self::$image = new \claviska\SimpleImage();
		self::$watermark_image = new \claviska\SimpleImage();
	}

	/**
	 * Path of background that want to use
	 * @param string
	 */
	public function setBackground($path=null){
		if(empty($path) || !file_exists($path))
			throw new \Exception("Background not found");
		self::$image->fromFile($path);
		return $this;
	}

	/**
	 * You can search and use image from unsplash.com
	 * Before use this function, you should create app to get "client_id" for accessing the API
	 * Because there is a limit per hour for each "client_id", so you can add two or more "client_id" to increase the limit
	 *
	 * @param array of client_id, string
	 */
	public function setBackgroundFromUnsplash($client_id=null,$keyword="random"){
		if(!is_string($keyword))
			throw new \Exception("Invalid background keyword");
		$keyword = ($keyword) ? $keyword : "random";
		self::clientId($client_id);
		$invalid_client_id = true;
		foreach(self::$client_id as $client_id){
			if($keyword == "random"){
	    	    $api = "photos/random?count=1";
    		}else{
    	    	$api = "search/photos?page=".rand(1,3)."&query=".$keyword;
    		}
    		$url = self::$unsplash_api_url.$api."&client_id=".$client_id;
    		@$data = json_decode(file_get_contents($url),true);
    		if(is_array($data)){
    			$invalid_client_id = false;
    			break;
    		}
		}
		if($invalid_client_id)
			throw new \Exception("Invalid client id: Wrong client id or the limits has reached");
    	if($keyword == "random"){
    	   	$data = $data[0];
    	}else{
    	   	$data = $data["results"];
    	   	$data = $data[array_rand($data)];
    	}
    	self::$image->fromString(file_get_contents($data["urls"]["regular"]));
		return $this;
	}

	/**
	 * Set custom quote font
	 * @param path of font
	 */
	public function setQuoteFont($pathFont=null){
		if(!file_exists($pathFont))
			throw new \Exception("Quote font not found");
		self::$quote_font = $pathFont;
		return $this;
	}

	/**
	 * Set custom watermark font
	 * @param path of font
	 */
	public function setWatermarkFont($pathFont=null){
		if(!file_exists($pathFont))
			throw new \Exception("Watermark font not found");
		self::$watermark_font = $pathFont;
		return $this;
	}

	/**
	 * Set custom quote font size
	 * @param int
	 */
	public function setQuoteFontSize($size=null){
		if(!is_int($size))
			throw new \Exception("Invalid quote font size value");
		self::$quote_font_size = $size;
		return $this;
	}

	/**
	 * Set custom watermark font size
	 * @param int
	 */
	public function setWatermarkFontSize($size=null){
		if(!is_int($size))
			throw new \Exception("Invalid watermark font size value");
		self::$watermark_font_size = $size;
		return $this;
	}

	/**
	 * Set the quote text
	 * @param string
	 */
	public function quote($quoteText=null){
		if(empty($quoteText) || !is_string($quoteText))
			throw new \Exception("Invalid quote value");
		self::$quote = strtoupper(trim($quoteText));
		return $this;
	}

	/**
	 * Set the watermark text
	 * @param string
	 */
	public function watermark($watermarkText=null){
		if(!$watermarkText)
			self::$watermark = "";
		else
			self::$watermark = trim($watermarkText);
		return $this;
	}
	/**
	 * Display the result
	 */
	public function toScreen(){
		self::render();
		self::$image->toScreen();
	}

	/**
	 * Save result to file
	 * @var file path
	 */
	public function toFile($file){
		self::render();
		self::$image->toFile($file);
	}

	/**
	 * Background methods to create the image
	 */
	private function clientId($client_id=null){
		if(empty($client_id) || !is_array($client_id))
			throw new \Exception("Invalid client id: Client id is empty or not passed in array type");
		self::$client_id = $client_id;
		return $this;
	}
	private function create_template(){
		self::$image
				->resize(720,720)
				->darken(20)
				->overlay(self::$top_quote_mark,"center",1,0,-250)
				->overlay(self::$bottom_quote_mark,"center",1,0,100);
		return $this;
	}
	private function write_quote(){
		$quote = wordwrap(self::$quote,50,"\n");
		$quote = explode("\n",$quote);
		$position = $quote;
		$middle = floor(count($quote)/2);
		if(count($quote)%2==0)
			self::$y_start_at = -45;
		if(count($quote) > 6){
			self::$quote_font_size *= (0.95-((count($quote)-7)/10));
			self::$line_height *= (0.8-((count($quote)-7)/10));
		}
		for($i=0;$i<count($quote);$i++){
			$diff = $i-$middle;
			$position[$i] = self::$y_start_at+($diff*self::$line_height);
			self::$image->text($quote[$i],
							 ['fontFile'=>self::$quote_font,
							  'size'=>self::$quote_font_size,
							  'color'=>"white",
							  'anchor'=>'center',
							  'yOffset'=>($position[$i]+2),
							  'shadow'=>["color"=>"black","x"=>1,"y"=>1]]);
		}
		return $this;
	}
	private function write_watermark(){
		self::$watermark_image
				->fromNew(720,200)
				->text(self::$watermark,
						["fontFile"=>self::$watermark_font,
						 "size"=>self::$watermark_font_size,
						 "color"=>"white",
						 "anchor"=>"bottom",
						 "yOffset"=>-100])
				->rotate(-5);
		self::$image
				->overlay(self::$watermark_image,"bottom",1,-5,40);
		return $this;
	}
	private function render(){
		self::create_template()
			 ->write_quote()
			 ->write_watermark();
		return $this;
	}
}