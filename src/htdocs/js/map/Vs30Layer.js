/* global L */
'use strict';


var Util = require('util/Util');

require('leaflet.markercluster');


var _DEFAULTS,
    _MARKER_DEFAULTS;

_MARKER_DEFAULTS = {
  fillOpacity: 0.6,
  opacity: 0.8,
  radius: 8,
  weight: 1
};
_DEFAULTS = {
  data: {},
  markerOptions: _MARKER_DEFAULTS
};


/**
 * Factory for Earthquakes overlay
 *
 * @param options {Object}
 *     {
 *       data: {String} Geojson data
 *       markerOptions: {Object} L.Path options
 *     }
 *
 * @return {L.FeatureGroup}
 */
var Vs30Layer = function (options) {
  var _this,
      _initialize,

      _bounds,
      _markerOptions,

      _onEachFeature,
      _pointToLayer;


  _this = L.featureGroup();

  _initialize = function (options) {
    options = Util.extend({}, _DEFAULTS, options);

    _bounds = new L.LatLngBounds();
    _markerOptions = Util.extend({}, _MARKER_DEFAULTS, options.markerOptions);

    _this.cluster = new L.MarkerClusterGroup({
      showCoverageOnHover: false,
      maxClusterRadius: 60,
      disableClusteringAtZoom: 9
    });
    _this.count = options.data.metadata.count;

    L.geoJson(options.data, {
      onEachFeature: _onEachFeature,
      pointToLayer: _pointToLayer
    });
  };


  /**
   * Leaflet GeoJSON option: called on each created feature layer. Useful for
   * attaching events and popups to features.
   *
   * @param feature {Object}
   * @param layer (L.Layer)
   */
  _onEachFeature = function (feature, layer) {
    var data,
        popup,
        popupTemplate,
        props;

    props = feature.properties;
    if (parseFloat(props.d_max) === 0) {
      props.d_max = 'NA';
    } else {
      props.d_max += ' m';
    }
    if (parseFloat(props.vs30) === 0) {
      props.vs30 = 'NA';
    } else {
      props.vs30 += ' m/s';
    }

    data = {
      d_max: props.d_max,
      index: props.index,
      method: props.method || 'NA',
      name: props.name || 'NA',
      net_sta: props.net_sta || 'NA',
      vs30: props.vs30
    };

    popupTemplate = '<div class="popup">' +
        '<h2>{index}</h2>' +
        '<table>' +
          '<tr><th>Network/Station Code</th><td>{net_sta}</td></tr>' +
          '<tr><th>Station Name</th><td>{name}</td></tr>' +
          '<tr><th>Method</th><td>{method}</td></tr>' +
          '<tr><th><i>V</i><sub><i>S</i>30</sub></th><td>{vs30}</td></tr>' +
          '<tr><th>Max. Depth of Profile</th><td>{d_max}</td></tr>' +
        '</table>' +
      '</div>';
    popup = L.Util.template(popupTemplate, data);

    layer.bindPopup(popup, {
      autoPanPadding: L.point(50, 50),
      maxWidth: '265'
    });
  };

  /**
   * Leaflet GeoJSON option: used for creating layers for GeoJSON points
   *
   * @param feature {Object}
   * @param latlng {L.LatLng}
   *
   * @return marker {L.CircleMarker}
   */
  _pointToLayer = function (feature, latlng) {
    var marker;

    _bounds.extend(latlng);

    _markerOptions.color = feature.properties.color;
    _markerOptions.fillColor = feature.properties.color;

    marker = L.circleMarker(latlng, _markerOptions);

    // Add marker to layer and cluster
    _this.addLayer(marker);
    _this.cluster.addLayer(marker);

    return marker;
  };

  _this.getBounds = function () {
    return _bounds;
  };

  _initialize(options);
  options = null;
  return _this;
};


L.vs30Layer = Vs30Layer;

module.exports = Vs30Layer;
