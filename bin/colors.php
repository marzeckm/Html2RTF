<?php 
    class RtfColors{
        static function convertColors($richText){
            $tempRTF = self::findAllRgbColors($richText);
            $tempColorTable = $tempRTF[0];
            $tempRTF[0] = self::createRtfColorTable($tempRTF[0]);
            $tempRTF[1] = self::replacePlaceholderByRtfColor([$tempColorTable, self::removeColorsFromHtml($tempRTF[1])]);
            return $tempRTF;
        }

        static function findAllRgbColors($richText){
            // Finds all RGB colors in the HTML code and replaces them by their RTF equivalent
            $colorTable = [];
            preg_match_all('/<span\s*style="color:\s*rgb\(([0-9]+),\s*([0-9]+),\s*([0-9]+)\)\s*;?">/i', $richText, $matches, PREG_SET_ORDER);
            
            foreach ($matches as $match) {
                $color = ["red" => $match[1], "green" => $match[2], "blue" => $match[3]];
                if (!in_array($color, $colorTable)) {
                    $colorIndex = count($colorTable);
                    $colorTable[] = $color;
                } else {
                    $colorIndex = array_search($color, $colorTable);
                }
                $richText = str_replace($match[0], "{{color$colorIndex}", $richText);
            }
            return [$colorTable, $richText];
        }

        static function createRtfColorTable($colorTable){
            // Build the RTF color table for the RTF file
            $colorTableString = "{\\colortbl ;\\red17\\green7\\blue255;";
            foreach ($colorTable as $index => $color) {
                $colorTableString .= "\\red{$color["red"]}\\green{$color["green"]}\\blue{$color["blue"]};";
            }
            $colorTableString .= "}";
            return $colorTableString;
        }

        static function removeColorsFromHtml($richText){
            // Replace all span tags with colors
            $richText = preg_replace('/<span style="color: rgb\(\d{1,3}, \d{1,3}, \d{1,3}\);">/', '{', $richText);
            $richText = str_replace('</span>', '}', $richText);
            return $richText;
        }

        static function replacePlaceholderByRtfColor($tempRTF){
            // Replaces all the color placeholders with the RTF eqivalent of their color
            foreach($tempRTF[0] as $key => $tempColor){
                $tempRTF[1] = str_replace("{color".$key."}", "\cf".($key+2)." ", $tempRTF[1]);
            }
            return $tempRTF[1];
        }
    }
?>