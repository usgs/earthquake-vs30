<?php
  // Author: Scott Haefner
  // Contact: Alan Yong
  // Last modified: 11/08/2016, Scott Haefner
  if (!isset($TEMPLATE)) {
    $TITLE = 'A Compilation of <i>V</i><sub><i>S</i>30</sub> Values in the United States';
    $HEAD = '<link rel="stylesheet" href="/lib/leaflet-0.7.7/leaflet.css"/>';
    $HEAD .= '<link rel="stylesheet" href="css/index.css"/>';
    $FOOT = '
      <script src="/lib/leaflet-0.7.7/leaflet.js"></script>
      <script src="js/index.js"></script>
    ';
    include 'template.inc.php';
  }

  // Create <li>'s for legend
  include_once 'colorScale.inc.php';
  $colors = getColorScale();
  $lis = '';
  $prevKey = '';

  foreach ($colors as $key => $color) {

    $description = "$prevKey&ndash;$key"; // range for middle colors (default)
    if (gettype($key) === 'string') { // either first or last color
      if ($prevKey) {
        $description = "&gt; $prevKey"; // last color
      } else {
        $description = "$key"; // first color
      }
    } else if (gettype($prevKey) === 'string') { // 2nd color
      $description = "&lt; $key";
    }

    $circle = sprintf ('<circle cx="9" cy="9" r="8" stroke="%s" stroke-width="1" fill="%s"></circle>',
      $color,
      $color
    );
    $lis .= sprintf ("<li><svg>%s</svg><span>%s</span></li>",
      $circle,
      $description
    );

    $prevKey = $key;
  }

?>

<p><i>V</i><sub><i>S</i>30</sub>, the time-averaged shear-wave velocity (<i>V<sub>S</sub></i>)
  in the upper 30 meters, is a key index adopted by the earthquake engineering
  community to account for seismic site conditions. USGS has compiled measured
  <i>V</i><sub><i>S</i>30</sub> funded by the USGS and other governmental
  agencies for <span class="count">approximately 3000</span> sites in the
  United States.</p>

<div class="map"></div>

<h2><i>V</i><sub><i>S</i>30</sub> Ranges (m/s)</h2>
<ul class="no-style scale">
  <?php print $lis; ?>
</ul>

<h2>Download <i>V</i><sub><i>S</i>30</sub> Data (.csv format)</h2>
<ul class="downloads">
  <li><a href="README.txt">README.txt</a></li>
  <li><a href="vs30.csv.php" download>All data points</a></li>
</ul>

<h2>Suggested Citation</h2>
<ul class="referencelist">
  <li>Yong, A., Thompson, E.M., Wald, D., Knudsen, K.L., Odum, J.K., Stephenson,
    W.J., and Haefner, S., 2015, Compilation of <i>V</i><sub><i>S</i>30</sub>
    Data for the United States: U.S. Geological Survey Data Series 978, 8 p.,
    <a href="http://dx.doi.org/10.3133/ds978">http://dx.doi.org/10.3133/ds978</a>.
  </li>
</ul>
