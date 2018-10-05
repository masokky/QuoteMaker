# QuoteMaker
QuoteMaker is simple library to create quote image

## Example Result
![Example Result](example/result.jpg)

## Installation
`composer require masokky/quotemaker`

## Usage
```php
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
```

## Available Methods
#### `setBackground($path)`
- `$path` (string) - Location of background image
#### `setBackgroundFromUnsplash($client_id,$keyword)`
You can search and use image from unsplash.com  
Before use this function, you should create app to get "client_id" for accessing the API  
Because there is a limit per hour for each "client_id", so you can add two or more "client_id" to increase the limit
- `$client_id` (array) - The access key of unsplash app
- `$keyword` (string) - Keyword to search an image, default `random`
#### `quote($text)`
- `$text` (string) - Set the quote text
#### `setQuoteFont($path)`
- `$path` (string) - Set the custom quote font*
#### `setQuoteFontSize($size)`
- `$size` (int) - Set the custom quote font size*
#### `watermark($text)`
- `$text` (string) - Set the watermark text, default `null`
#### `setWatermarkFont($path)`
- `$path` (string) - Set the custom watermark font*
#### `setWatermarkFontSize($size)`
- `$size` (int) - Set the custom watermark font size*
#### `toScreen()`
Output the result to the screen
#### `toFile($file)`
Save the result to image file

*Default see the example result

```Feel free to develop and maintain this library```
