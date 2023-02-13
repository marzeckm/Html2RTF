<?php
    //Conversion of Lists into RTF
    Class RtfListen{
        static function convertLists($richText){
            $richText = self::convertOrderedList($richText);
            $richText = self::convertUnorderedList($richText);
            return $richText;
        }

        static function convertUnorderedList($richText){
            // Finds and replaces all unordered lists with their RTF equivalent
            $richText = preg_replace('/<ul(?:\s+[^>]*)?>/i', "{\\pard\\plain\\f0\\fs24\n\\fi-360\\li720\\sa200\\sb100\\tx720\n", $richText);
            $richText = preg_replace('/<\/ul(?:\s+[^>]*)?>/i', "\n}\n", $richText);
            $richText = preg_replace('/<li(?:\s+[^>]*)?>/i', "{\\pard\\plain\\f0\\fs24\n\\fi-360\\li720\\sa200\\sb100\\tx720\\bullet \\tab\n", $richText);
            $richText = preg_replace('/<\/li(?:\s+[^>]*)?>/i', "\n\\par}\n", $richText);
            return $richText;
        }

        static function convertOrderedList($richText){
            // Finds and replaces all ordered lists with their RTF equivalent
            $tempRTF = self::extractOrderedLists($richText);
            $tempRTF[1] = self::replaceOrderedLists($tempRTF[1]);
            $richText = self::restoreOrderedLists($tempRTF[0], $tempRTF[1]);
            return $richText;
        }

        static function extractOrderedLists($richText) {
            // Finds all ordered lists and replaces them with placehodlers. the orderes lists are saved in the matches-array
            preg_match_all('#<ol[^>]*>(.*?)<\/ol>#s', $richText, $matches, PREG_SET_ORDER);
            foreach ($matches as $index => $match) {
                $richText = str_replace($list[0], "{{ORDERED_LIST_$index}}", $richText);
            }
            return [$richText, $matches];
        }

        static function replaceOrderedLists(array $htmlArray): array {
            // Replaces all the 
            foreach ($htmlArray as $key => $richText) {
                $richText = preg_replace(
                    ['/<ol(?:\s+[^>]*)?>/i', '/<li(?:\s+[^>]*)?>/i', '/<\/ol(?:\s+[^>]*)?>/i', '/<\/li(?:\s+[^>]*)?>/i/'],
                    ["{\\pard\\plain\\f0\\fs24\n\\fi-360\\li720\\sa200\\sb100\\tx720\n", "{\\pard\\plain\\f0\\fs24\n\\fi-360\\li720\\sa200\\sb100\\tx720 {$listItemNum}. \n", "\n}\n", "\n\\par}\n"],
                    $richText
                );
                $htmlArray[$key] = $richText;
            }
        
            return $htmlArray;
        }
    
        static function restoreOrderedLists(string $html, array $lists): string {
            // Replaces the placeholders with the ordered lists from the array
            $counter = 0;
            return str_replace(array_map(function($list) use (&$counter) {
                return "{{ORDERED_LIST_$counter}}";
            }, $lists), $lists, $html);
        }
    }    
?>