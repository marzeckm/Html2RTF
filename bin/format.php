<?php 
    class RtfFormat{

        static function convertFormat($richText){
            $richText = self::addTextJustification($richText, "left", "\ql ");
            $richText = self::addTextJustification($richText, "right", "\qr ");
            $richText = self::addTextJustification($richText, "justify", "\qj ");
            $richText = self::addTextJustification($richText, "center", "\qc ");
            $richText = self::removeUnusedStyles($richText);
            return $richText;
        }

        static function addTextJustification($richText, $align, $rtfalign){            
            // Find all Justifies in the HTML code
            $richText = preg_replace('/<\/(?:p|div|section|article)(?:\s+[^>]*)?>\s*<(?:p|div|section|article)\s*style="text-align:\s*'.$align.'\s*;?">/i', "\\par\n}{\n".$rtfalign, $richText);
            $richText = preg_replace('/<(?:p|div|section|article)\s*style="text-align:\s*'.$align.'\s*;?">/i', "\n\\par\n{ ".$rtfalign, $richText);
            
            return $richText;
        }

        static function removeUnusedStyles($richtext){
            //Removes all other unused attributes that could cause damage to the RTF file.
            return preg_replace('/<(\w+)\s+(.*?)>/', '<$1>', $richtext);
        }
    }
?>