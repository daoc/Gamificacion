<?php
    
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'temp/';
    include "qrlib.php";    
    $filename = $PNG_TEMP_DIR.'pregIdentificacion-2.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4;  
	QRcode::png('pregIdentificacion-2', $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
    
    ?>
