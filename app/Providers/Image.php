<?php

namespace App\Providers;

class Image{

    public static function convert($base64Image, $outputFolder, $outputFile) {
        // Decode the base64-encoded image data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        if(!$imageData){
            return false;
        }
        // Create an image from the binary data
        $img = imagecreatefromstring($imageData);
        if(!$img){
            return false;
        }

        if (!file_exists($outputFolder)) {
            mkdir($outputFolder, 0777, true);
        }
      
        // Create the full output file path
        $outputFilePath = $outputFolder . '/' . $outputFile;
      
        // Create a new image file
        $imageFile = fopen($outputFilePath, 'wb');
      
        // Write the decoded image data to the image file
        fwrite($imageFile, $imageData);
      
        // Close the image file
        fclose($imageFile);
      
        return true;
    }
      
      	
}