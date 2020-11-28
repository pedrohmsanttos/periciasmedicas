<?php

App::import( 'Vendor', 'PHPWord', array('file'=>'phpword' . DS . 'Autoloader.php') );
\PhpOffice\PhpWord\Autoloader::register();
$phpWord = new \PhpOffice\PhpWord\PhpWord();


$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(6);

// Adding Text element with font customized using explicitly created font style object...
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Arial');
$fontStyle->setSize(8);

$section = $phpWord->addSection();

$myTextElement = $section->addText(
    htmlspecialchars('DESPACHO DA DIRETORIA DE RECURSOS HUMANOS/ GERÊNCIA ADMINISTRATIVA DE PERÍCIAS MÉDICAS - I.R.H. EM:')
);
$myTextElement->setFontStyle($fontStyle);

/* Note: any element you append to a document must reside inside of a Section. */


/*
 * Note: it's possible to customize font style of the Text element you add in three ways:
 * - inline;
 * - using named font style (new font style object will be implicitly created);
 * - using explicitly created font style object.
 */

// Adding Text element with font customized inline...
$section->addText(
    htmlspecialchars(
        ''
    ),
    array('name' => 'Tahoma', 'size' => 10)
);

// Adding Text element with font customized using named font style...
$fontStyleName = 'oneUserDefinedStyle';
$phpWord->addFontStyle(
    $fontStyleName,
    array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
);
$section->addText(
    htmlspecialchars(
        '"The greatest accomplishment is not in never falling, '
        . 'but in rising again after you fall." '
        . '(Vince Lombardi)'
    ),
    $fontStyleName
);

// Adding Text element with font customized using explicitly created font style object...
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Arial');
$fontStyle->setSize(8);
$myTextElement = $section->addText(
    htmlspecialchars('"Believe you can and you\'re halfway there." (Theodor Roosevelt)')
);
$myTextElement->setFontStyle($fontStyle);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');

// Saving the document as ODF file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
$objWriter->save('helloWorld.odt');

// Saving the document as HTML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

$objWriter->save('helloWorld.html');

$url = Router::url('/', true).'helloWorld.odt';
echo "<a href='$url'>$url</a>";