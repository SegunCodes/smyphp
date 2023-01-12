<?php

namespace App\Providers;

class Image{

    public static function convert($base64Image, $outputFolder, $outputFile) {
        // Decode the base64-encoded image data
        $imageData = base64_decode($base64Image);

        if (!file_exists($outputFolder)) {
            mkdir($outputFolder, 0777, true);
        }
      
        // Create the full output file path
        $outputFilePath = $outputFolder . '/' . $outputFile;
      
        // Create a new image file
        $imageFile = fopen($outputFilePath, 'w');
      
        // Write the decoded image data to the image file
        fwrite($imageFile, $imageData);
      
        // Close the image file
        fclose($imageFile);
      
        return true;
    }
      
      	
}