<?php
/**
 * Copyright (c) 2020 Ramdhan Firmansyah
 * File              : main.php
 * @author           : Cvar1984 <gedzsarjuncomuniti@gmail.com>
 * Date              : 19.03.2020
 * Last Modified Date: 19.03.2020
 * Last Modified By  : Cvar1984 <gedzsarjuncomuniti@gmail.com>
 */

require __DIR__ . '/vendor/autoload.php';
use masokky\QuoteMaker;
$climate = new League\CLImate\CLImate;
$climate->arguments->add(
    [
        'quote' => [
            'prefix' => 'q',
            'longPrefix' => 'quote',
            'description' => 'Your quote sentence',
            'defaultValue' => 'the cruelest crime is giving false hope without love',
            'required' => true,
        ],
        'watermarkText' => [
            'prefix' => 'w',
            'longPrefix' => 'watermark',
            'description' => 'Watermark or bottom text',
            'defaultValue' => 'MasOkky',
            'required' => true,
        ],
        'outputName' => [
            'prefix' => 'o',
            'longPrefix' => 'output',
            'description' => 'Output filename',
            'defaultValue' => 'result',
            'required' => true,
        ],
        'watermarkFont' => [
            'prefix' => 'wf',
            'longPrefix' => 'watermark-font',
            'description' => 'Your watermark font path',
            'required' => false,
        ],
        'watermarkFontSize' => [
            'prefix' => 'wfs',
            'longPrefix' => 'watermark-font-size',
            'description' => 'Your watermark font size',
            'castTo' => 'int',
            'required' => false,
        ],
        'quoteFont' => [
            'prefix' => 'qf',
            'longPrefix' => 'quote-font',
            'description' => 'Your quote font path',
            'required' => false,
        ],
        'quoteFontSize' => [
            'prefix' => 'qfs',
            'longPrefix' => 'quote-font-size',
            'description' => 'Your quote font size',
            'required' => false,
        ],
        'background' => [
            'prefix' => 'b',
            'longPrefix' => 'background',
            'description' => 'Your background path',
            'required' => true,
        ],
    ]
);

try {
    $climate->arguments->parse();
} catch (Exception $e) {
    $climate->usage();
}
$watermark = $climate->arguments->get('watermarkText');
$watermarkFont = $climate->arguments->get('watermarkFont');
$watermarkFontSize = $climate->arguments->get('watermarkFontSize');
$output = $climate->arguments->get('outputName');
$background = $climate->arguments->get('background');
$quote = $climate->arguments->get('quote');
$quoteFont = $climate->arguments->get('quoteFont');
$quoteFontSize = $climate->arguments->get('quoteFontSize');
$background = $climate->arguments->get('background');

try {
    (new QuoteMaker)
        ->quoteText($quote)
        //->setQuoteFont($quoteFont)
        //->setQuoteFontSize($quoteFontSize)
        //->setWatermarkFont($watermarkFont)
        //->setWatermarkFontSize($watermarkFontSize)
        ->setBackground($background)
        ->watermarkText($watermark)
        ->toFile($output . '.png');
} catch (Exception $e) {
    echo $e->getMessage();
}
