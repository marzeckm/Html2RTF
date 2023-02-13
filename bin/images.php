<?php 
    class RtfImages{
        static function convertImages($richText){
            $tempRTF = self::findImages($richText);
            $tempRTF[0] = self::convertImageToRtf($tempRTF[0]);
            $richText = self::replacePlaceholderByRtfImage($tempRTF);
            return $richText;
        }

        static function findImages($richText){
            // Finds all image tags in HTML code and replaces them with placeholders
            preg_match_all('/<img\s+.*?src=[\'"]([^\'"]+)[\'"][^>]*>/i', $richText, $matches);
            foreach ($matches[0] as $key => $match) {
                $richText = (str_replace($match, "{{image$key}}", $richText));
            }
            return [$matches[1], $richText];
        }

        static function convertImageToRtf($images){
            // Tries to get the in the `src` attribute defindes picture and downlaods ist and transofrms it into hex 
            foreach ($images as $key => $image) {
                try{
                    $image_size = getimagesize($image);
                    $image_type = ((str_contains($image, ".png")) ? "png" : "jpeg");
                    if($image_size[0] < 10000 && $image_size[1] < 10000){
                        $image = file_get_contents($image);
                        $images[$key] = [bin2hex($image), $image_size, $image_type];
                    }else{
                        $images[$key] = ["", ["0", "0"], $image_type];
                    }
                }catch(e){
                    print_r("Fehler bei Versuch Bild zu erhalten.");
                }
            }
            return $images;
        }

        static function replacePlaceholderByRtfImage($tempRTF){
            // Replaces the Placeholder in the HTML code iwth the RTF equivalent of the picture
            foreach($tempRTF[0] as $key => $tempImage){
                $tempRTF[1] = str_replace("{{image".$key."}}", "{\\pict\\picw".$tempImage[1][0]."\\pich".$tempImage[1][1]."\\picwgoal4500\\pichgoal3000\\".$tempImage[2]."blip ".$tempImage[0]." }", $tempRTF[1]);
            }
            return $tempRTF[1];
        }
    }
?>