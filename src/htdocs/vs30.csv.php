<?php

include_once '../conf/config.inc.php'; // app config
include_once '../lib/_functions.inc.php'; // app functions

date_default_timezone_set('America/Los_Angeles');

// Default to entire globe
$params['latmin'] = safeParam('latmin', -90);
$params['latmax'] = safeParam('latmax', 90);
$params['lngmin'] = safeParam('lngmin', -180);
$params['lngmax'] = safeParam('lngmax', 180);

// query db
$sql = 'SELECT `index`, `lat`, `lng`, `datum`, `net_sta`, `name`, `method`,
  `vs30`, `d_max`, `contact`, `reference`, `url`, `s_geology`, `src_geology`,
  `comments`
    FROM vs30_us
    WHERE `lat` >= :latmin AND `lat` <= :latmax
      AND `lng` >= :lngmin AND `lng` <= :lngmax
      AND `lat` != 0 AND `lng` != 0
    ORDER BY `index` ASC;';

try {
  $rsPoints = $DB->prepare($sql);
  $rsPoints->execute([
    ':latmin' => $params['latmin'],
    ':latmax' => $params['latmax'],
    ':lngmin' => $params['lngmin'],
    ':lngmax' => $params['lngmax']
  ]);
} catch(Exception $e) {
  print '<p class="alert error">ERROR 2: ' . $e->getMessage() . '</p>';
}

// turn on output buffering so we can set content length header after csv file is complete
ob_start();

// stream csv file contents to output buffer
$output = fopen('php://output', 'w');

/*
fwrite($output, "# SELECT COMMENTS:\n");
fwrite($output, "# Id: Unique site identifier consisting of the initials of the primary contact (see Contact column), separated by the period symbol and followed by numeric characters.\n");
fwrite($output, "# Network/Station: Seismic network and station codes as reported by the investigator(s); network code is  separated by period and followed by station code, however, where there are no periods, character string denotes station code.\n");
fwrite($output, "# Method: Vs30 measurement technique(s) as reported by investigator(s); sites with more than one technique implies multi-technique approach applied to calculate Vs30; deabbreviation for selected techniques include Array Microtremor (AM), Cone Penetrometer Test (CPT), Multi-channel Analysis of Surface Waves (MASW), interferometric Multi-channel Analysis of Surface Waves (iMASW), Refraction Microtremor (ReMi), and Spectra Analysis of Surface Waves (SASW).\n");
fwrite($output, "# Contact: typically the lead author of the report describing the data, however, the contact can be coauthor or field technician.\n");
fwrite($output, "# URL:  website containing source data or report (last accessed 1 June 2014).\n");
fwrite($output, "# Geologic Map Unit(s)/Material(s): geologic map unit(s) or subsurface material(s) as described by investigator(s); see accompanying report for description of geologic nomenclature.\n");
*/

fputcsv($output, array('Id', 'Latitude', 'Longitude', 'Datum',
  'Network/Station Code', 'Station Name', 'Method', 'Vs30 (m/s)', 'Max Depth (m)',
  'Contact', 'Reference', 'URL', 'Geologic Map Unit(s)/Material(s)',
  'Geologic Data Source', 'Comments')
);

while ($row = $rsPoints->fetch(PDO::FETCH_ASSOC)) {
	fputcsv($output, $row);
}

$content_length = ob_get_length();

// Set type to csv file and trigger download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=vs30data.csv');
header("Content-Length: " . $content_length);

// send content (output buffer)
ob_end_flush();

?>
