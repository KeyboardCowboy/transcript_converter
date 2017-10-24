# Table Transcription Converter

Convert an HTML table into a description list for audio transcriptions.

## Requirements
- An HTML file containing a single table.
- The table must have two columns: one for the speaker, and one for their content.
- The speaker column may be empty if there are more than one rows with speaker content.

## Installation
1. Place `TableTrans.php` anywhere in your file system.

## Commands
`$ /path/to/php TableTrans.php </path/to/transcript.html> [print|open]`

### Required Parameters
`The path to the html file containing the transcript table.`

### Optional Parameters
- `print` - Will print the resulting file to the screen.
- `open` - Will open the resulting file.

If the command runs successfully, a file will be created next to the original with a `.converted` suffix prepended to the extension. 
