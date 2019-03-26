'use strict';

goog.provide('Blockly.JavaScript.duckietown');

goog.require('Blockly.JavaScript');


Blockly.JavaScript['move'] = function(block) {
  var forward_speed = Blockly.JavaScript.valueToCode(block, 'FORWARD_SPEED', Blockly.JavaScript.ORDER_ATOMIC);
  var turn_speed = Blockly.JavaScript.valueToCode(block, 'TURN_SPEED', Blockly.JavaScript.ORDER_ATOMIC);
  var duration = Blockly.JavaScript.valueToCode(block, 'DURATION', Blockly.JavaScript.ORDER_ATOMIC);
  // ---
  forward_speed = parseFloat(forward_speed) / 100.0;
  turn_speed = parseFloat(turn_speed) / 100.0;
  // ---
  function json_str(forward_speed, turn_speed){
    var obj = {
      axes: [0, forward_speed, 0, turn_speed],
      buttons: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    };
    return JSON.stringify(obj);
  }//json_str
  var code = "publish(\"commands\", '"+json_str(forward_speed, turn_speed)+"');\n";
  code += "waitForSeconds("+duration+");\n";
  code += "publish(\"commands\", '"+json_str(0, 0)+"');\n";
  code += "pause(\"commands\");\n";
  return code;
};
