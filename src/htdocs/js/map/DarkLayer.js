/* global L */
'use strict';


var Util = require('util/Util');


/**
 * Factory for Dark base layer
 *
 * @param options {Object}
 *     L.TileLayer options
 *
 * @return {L.TileLayer}
 */
var DarkLayer = function (options) {
  options = Util.extend({
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">' +
      'OpenStreetMap</a> &copy; <a href="https://cartodb.com/attributions">' +
      'CartoDB</a>',
    maxZoom: 19,
    subdomains: 'abcd'
  }, options);

  return L.tileLayer(
    'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}@2x.png',
    options
  );
};


L.darkLayer = DarkLayer;

module.exports = DarkLayer;
