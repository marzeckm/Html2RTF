<?php 
    include dirname(__FILE__)."/lists.php";
    include dirname(__FILE__)."/tags.php";
    include dirname(__FILE__)."/colors.php";
    include dirname(__FILE__)."/format.php";
    include dirname(__FILE__)."/images.php";

    class HtmlToRTF{
        static function convertHtmlToRtf($html) {
            if (!(is_string($html) && $html)) {
                return null;
            }
            
            //Wandelt die HTML-Datei in UTF-8 um.
            $html = str_replace("&nbsp;", " ", $html);
            $tmpRichText = $richText = $html;
            $hasHyperlinks = false;

            //Austauschen der Bilder durch RTF-Bilder
            $richText = RtfImages::convertImages($richText);
    
            //Austauschen der Farben
            $tempRTF = (RtfColors::convertColors($richText));
            $tempColorCodes = $tempRTF[0];
            $richText = $tempRTF[1];

            //Austauschen der Text-Formate
            $richText = RtfFormat::convertFormat($richText);
    
            // Unordered and ordered lists
            $richText = RtfListen::convertLists($richText);

            //Replaces all other HTML-Tags by their RTF equivalent
            $tempRTF = RtfTags::convert($richText);
            $hasHyperlinks = $tempRTF[0];
            $richText = $tempRTF[1];
            
            // Prefix and suffix the rich text with the necessary syntax
            return "{\\rtf1\\ansi\\ansicpg65001\n".$tempColorCodes.(($hasHyperlinks) ? "\n" : "").(htmlspecialchars_decode($richText))."\n}";
            
        }
    }

?>