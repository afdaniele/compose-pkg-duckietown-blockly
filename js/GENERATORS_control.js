'use strict';

goog.provide('Blockly.JavaScript.control');

goog.require('Blockly.JavaScript');


Blockly.JavaScript['wait'] = function(block) {
  var seconds = Number(block.getFieldValue('WAIT_SECS'));
  var code = 'waitForSeconds(' + seconds + ');\n';
  return code;
};
