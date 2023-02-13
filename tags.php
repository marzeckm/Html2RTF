<?php
    class RtfTags{
        static function convert($richText){
            $richText = self::replaceSingletonTags($richText);
            $richText = self::replaceEmptyTags($richText);
            $richText = self::replaceSemanticTags($richText);
            $richText = self::replaceOtherTags($richText);
            $tempRTF = self::replaceHyperlinks($richText);
            return $tempRTF;
        }

        static function replaceSingletonTags($richText){
            //Replaces lines and line breaks by their RTF equivalent
            $richText = preg_replace('/<(?:hr)(?:\s+[^>]*)?\s*[\/]?>/i', "{\\pard \\brdrb \\brdrs \\brdrw10 \\brsp20 \\par}\n{\\pard\\par}\n", $richText);
            $richText = preg_replace('/<(?:br)(?:\s+[^>]*)?\s*[\/]?>/i', "{\\pard\\par}\n", $richText);
            return $richText;
        }

        static function replaceEmptyTags($richText){
            //Replaces empty tags and replaces them by their RTF equivalent
            $richText = preg_replace('/<(?:p|div|section|article)(?:\s+[^>]*)?\s*[\/]>/i', "{\\pard\\par}\n", $richText);
            $richText = preg_replace('/<(?:[^>]+)\/>/', "", $richText);
            return $richText;
        }

        static function replaceSemanticTags($richText){
            //Replaces Closing that are followed by a Opening-Tag, to only add one newline for this code.
            $richText = preg_replace('/<\/(?:p|div|section|article)(?:\s+[^>]*)?>\s*<(?:p|div|section|article)(?:\s+[^>]*)?>/i', "\\par\n}{\n", $richText);
            //Replaces all Opening-Tags by their RTF equivalent
            $richText = preg_replace('/<(?:b|strong)(?:\s+[^>]*)?>/i', "{\\b\n", $richText);
            $richText = preg_replace('/<(?:i|em)(?:\s+[^>]*)?>/i', "{\\i\n", $richText);
            $richText = preg_replace('/<(?:u|ins)(?:\s+[^>]*)?>/i', "{\\ul\n", $richText);
            $richText = preg_replace('/<(?:strike|del)(?:\s+[^>]*)?>/i', "{\\strike\n", $richText);
            $richText = preg_replace('/<sup(?:\s+[^>]*)?>/i', "{\\super\n", $richText);
            $richText = preg_replace('/<sub(?:\s+[^>]*)?>/i', "{\\sub\n", $richText);
            $richText = preg_replace('/<(?:p|div|section|article)(?:\s+[^>]*)?>/i', "\\par\n{\\pard\n", $richText);
            //Replaces all the Closing-Tags by their RTF equivalent
            $richText = preg_replace('/<\/(?:p|div|section|article)(?:\s+[^>]*)?>/i', "\n\\par}", $richText);
            $richText = preg_replace('/<\/(?:b|strong|i|em|u|ins|strike|del|sup|sub)(?:\s+[^>]*)?>/i', "}\n", $richText);
            return $richText;
        }

        static function replaceOtherTags($richText){
            //Replaces all custom Tags, so they are not causing any problems in the RTF file
            return preg_replace('/<(?:[^>]+)>/', "", $richText);
        }

        static function replaceHyperlinks($richText){
            //Replaces all Hyperlinks in the HTML document by their RTF equivalent
            $richText = preg_replace('/<a(?:\s+[^>]*)?(?:\s+href=(["\'])(?:javascript:void\(0?\);?|#|return false;?|void\(0?\);?|)\1)(?:\s+[^>]*)?>/i', "{{{\n", $richText);
            $tmpRichText = $richText;
            $richText = preg_replace('/<a(?:\s+[^>]*)?(?:\s+href=(["\'])(.+)\1)(?:\s+[^>]*)?>/i', "{\\field{\\*\\fldinst{HYPERLINK\n \"$2\"\n}}{\\fldrslt{\\ul\\cf1\n", $richText);
            $hasHyperlinks = $richText !== $tmpRichText;
            $richText = preg_replace('/<a(?:\s+[^>]*)?>/i', "{{{\n", $richText);
            $richText = preg_replace('/<\/a(?:\s+[^>]*)?>/i', "\n}}}", $richText);
            return [$hasHyperlinks, $richText];
        }
    }
?>