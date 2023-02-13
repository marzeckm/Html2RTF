# HtmlToRtf Converter

A tool for converting HTML content into RTF (Rich Text Format) which can be edited and read by word processing software like Wordpad, Microsoft Word, LibreOffice Writer, and others.

## Features
- Supports conversion of semantic HTML tags into RTF text tags.
- Removes unsupported or empty tags.
- Converts HTML image tags with links in `src` attribute to RTF images in hexcode.
- Converts HTML colors in style attributes (only RGB) to RTF colors by adding them to the color table of the RTF.
- Converts HTML hyperlinks to RTF hyperlinks.
- Converts text alignments by semantic tags like `divs`, `sections`, `header`, `footer` and `articles` to RTF text alignments.
- Converts unordered and ordered lists from HTML to RTF understandable ordered and unordered lists.

## Usage
To use the converter, you have to include the rtf.php in your PHP-File
include "./bin/rtf.php";
Now you can convert the code by transfering the HTML code as String to the function convertHtmlToRtf().
$richText = HtmlToRTF::convertHtmlToRtf($html);

## Requirements
- Python 3.x
- A word processing software that can read RTF files.

## Contribute
If you want to contribute to the development of this project, feel free to submit pull requests or open issues. Let's make the HtmlToRtf Converter even better together!

## License
This project is licensed under the [MIT License](LICENSE).
