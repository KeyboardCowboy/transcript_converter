<?php
/**
 * @file
 * Convert an html document containing a transcription table to a description
 * list.
 */

// Get the source file.
if (!empty($argv[1])) {
  $source_file = $argv[1];
}
else {
  print "Please provide a source file.";
  return;
}

// Validate the filename.
if (!file_exists($source_file)) {
  print "Source file does not exist.";
  return;
}

// Load the transcription document.
$dom = new DOMDocument();
$dom->loadHTMLFile($source_file);

// Create an output DOM element.
$new_doc = new DOMDocument();
$new_doc->formatOutput = TRUE;
$new_doc->preserveWhiteSpace = FALSE;
$output = $new_doc->createElement('dl');

// $tables is a DOMNodeList.
$tables = $dom->getElementsByTagName('table');

if ($tables->length === 1) {
  // $table is a DOMElement.
  $table = $tables->item(0);

  // $rows is a DOMNodeList.
  $rows = $table->getElementsByTagName('tr');
  foreach ($rows as $row) {
    // $row is a DOMElement.
    // $cells is a DOMNodeList.
    $cells = $row->getElementsByTagName('td');

    if ($cells->length === 2) {
      $speaker = trim($cells->item(0)->textContent);
      $content = $cells->item(1)->textContent;
      $content = htmlentities($content);

      // Convert &nbsp; to spaces.
      $content = str_replace('&nbsp;', ' ', $content);

      // Clear out any line breaks and extra white space.
      $content = preg_replace('/\n(\r)?|\s+$/', '', $content);
      
      // Add the speaker as a dt.
      if (!empty($speaker)) {
        $speaker_element = new DOMElement('dt', $speaker);
        $output->appendChild($speaker_element);
      }

      // Add the content as a dd.
      if (!empty($content)) {
        $content_element = new DOMElement('dd', $content);
        $output->appendChild($content_element);
      }
    }
    else {
      print "Error in row format.  Wrong number of columns.";
      continue;
    }
  }

  // Save the DL element to the new doc.
  $new_doc->appendChild($output);

  // Save the new doc file.
  $fileinfo = pathinfo($source_file);
  $dest_file = $fileinfo['dirname'] . '/' . $fileinfo['filename'] . '.converted.' . $fileinfo['extension'];
  if ($new_doc->saveHTMLFile($dest_file)) {
    // Check for a second parameter to print the text or open it in a browser.
    if (!empty($argv[2])) {
      switch ($argv[2]) {
        case 'open':
          exec('open ' . $dest_file);
          break;

        case 'print':
          print file_get_contents($dest_file);
          break;
      }
    }
  }
  else {
    print "Error writing file $dest_file";
    return;
  }
}
else {
  print "Unable to discern table.";
  return;
}
