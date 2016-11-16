<?php

include_once '../conf/config.inc.php'; // app config
include_once '../lib/_functions.inc.php'; // app functions

// import array containing color scale
include_once 'colorScale.inc.php';
$colors = getColorScale();

// query db
$sql = 'SELECT * FROM vs30_us
  WHERE `lat` != 0 AND `lng` != 0
  ORDER BY `name` ASC';

try {
  $rsPoints = $DB->prepare($sql);
  $rsPoints->execute();
} catch(Exception $e) {
  print '<p class="alert error">ERROR 2: ' . $e->getMessage() . '</p>';
}

$points = array(
  'type' => 'FeatureCollection',
  'metadata' => array(
    'count' => $rsPoints->rowCount(),
    'generated' => date(DATE_RFC2822)
  ),
  'features' => array()
);

while ($row = $rsPoints->fetch(PDO::FETCH_ASSOC)) {
  if ($row['lng'] && $row['lat']) {
    $vs30 = purgeNull($row['vs30']);
    $color = getColor($vs30);
    $feature = array(
      'type' => 'Feature',
      'properties' => array(
        'index' => $row['index'],
        'net_sta' => $row['net_sta'],
        'name' => purgeNull($row['name']),
        'method' => $row['method'],
        'vs30' => $vs30,
        'color' => $color,
        'd_max' => purgeNull($row['d_max'])
      ),
      'geometry' => array(
        'type' => 'Point',
        'coordinates' => array(
          floatval($row['lng']),
          floatval($row['lat'])
        )
      ),
      'id' => intval($row['id'])
    );

    array_push($points['features'], $feature);
  }
}

// Create json object from array and display
header('Content-Type: application/json');
$json = json_encode($points);
print $json;

// Get color code for given depth
function getColor($num) {
  $colors = $GLOBALS['colors'];

  if ($num === '') {
    $r = $colors['No value'];
  } else {
    $r = $colors['max']; // first set color to max value; gets reset in loop if $num is within lower range
    foreach ($colors as $key => $color) {
      if (gettype($key) !== 'string' && $num < $key) {
        $r = $color;
        break;
      }
    }
  }
  return $r;
}

// don't echo 'null' in output
function purgeNull($val) {
  $r = '';
  if ($val) {
    $r = $val;
  }
  return $r;
}

?>
