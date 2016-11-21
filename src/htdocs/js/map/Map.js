/* global L */
'use strict';


var Xhr = require('util/Xhr');

// Factories for creating map layers
require('map/GreyscaleLayer');
require('map/SatelliteLayer');
require('map/TerrainLayer');
require('map/Vs30Layer.js');


/**
 * Class: Map
 *
 * @param options {Object}
 *     Configuration options
 */
var Map = function (options) {

  var _this,
      _initialize,

      _el,
      _map,
      _vs30,

      _getMapLayers,
      _getQueryString,
      _initDownloadLink,
      _initMap,
      _loadVs30Layer,
      _updateNumStations;

  _this = {};

  _initialize = function (options) {
    options = options || {};
    _el = options.el || document.createElement('div');

    // Load Vs30 layer which calls _initMap() when finished
    _loadVs30Layer();
  };


  /**
   * Get all map layers that will be displayed on map
   *
   * @return layers {Object}
   *    {
   *      baseLayers: {Object}
   *      overlays: {Object}
   *      defaults: {Array}
   *    }
   */
  _getMapLayers = function () {
    var layers,
        greyscale,
        satellite,
        terrain;

    greyscale = L.greyscaleLayer();
    satellite = L.satelliteLayer();
    terrain = L.terrainLayer();

    layers = {};
    layers.baseLayers = {
      'Terrain': terrain,
      'Greyscale': greyscale,
      'Satellite': satellite
    };
    layers.overlays = {
      '<i>V</i><sub><i>S</i>30</sub>': _vs30.cluster
    };
    layers.defaults = [terrain, _vs30.cluster];

    return layers;
  };

  /**
   * Get querystring to limit csv table to current map extent
   *
   * @return qs {String}
   */
  _getQueryString = function () {
    var mapextent,
        qs;

    mapextent = _map.getBounds();
    qs = [
      '?latmin=', Math.round(mapextent.getSouth() * 1000) / 1000,
      '&latmax=', Math.round(mapextent.getNorth() * 1000) / 1000,
      '&lngmin=', Math.round(mapextent.getWest() * 1000) / 1000,
      '&lngmax=', Math.round(mapextent.getEast() * 1000) / 1000,
    ].join('');

    return qs;
  };

  /**
   * Create / update link for downloading only points visible on map
   */
  _initDownloadLink = function () {
    var li,
        querystring,
        uri;

    li = document.createElement('li');
    querystring = _getQueryString();
    uri = './vs30.csv.php';

    li.innerHTML = '<a href="' + uri + querystring +
      '" download class="extent">Only data points within the current map extent</a>';
    document.querySelector('.downloads').appendChild(li);

    _map.on('moveend', function() {
      var querystring = _getQueryString();

      document.querySelector('.extent').setAttribute('href', uri + querystring);
    });
  };

  /**
   * Create Leaflet map instance
   */
  _initMap = function () {
    var bounds,
        layers;

    layers = _getMapLayers();

    _map = L.map(_el, {
      layers: layers.defaults,
      scrollWheelZoom: false
    });

    // Set intial map extent to contain Vs30 overlay
    bounds = _vs30.getBounds();
    _map.fitBounds(bounds);

    L.control.layers(layers.baseLayers, layers.overlays).addTo(_map);
    L.control.scale().addTo(_map);

    _initDownloadLink();
  };

  /**
   * Load Vs30 layer from geojson data via ajax
   */
  _loadVs30Layer = function () {
    var url;

    url = 'vs30.json.php';

    Xhr.ajax({
      url: url,
      success: function (data) {
        _vs30 = L.vs30Layer({
          data: data
        });

        _initMap();
        _updateNumStations(_vs30.count);
      },
      error: function (status) {
        console.log(status);
      }
    });
  };

  _updateNumStations = function (count) {
    var span;

    span = document.querySelector('.count');
    span.innerHTML = count;
  };


  _initialize(options);
  options = null;
  return _this;
};


module.exports = Map;
