/**
 * @license
 *
 * Copyright 2015 Erle Robotics
 * http://erlerobotics.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @fileoverview Blocks for Erle-Spider.
 * @author victor@erlerobot.com (Víctor Mayoral Vilches)
 */
'use strict';

goog.provide('Blockly.Python.duckietown');
goog.require('Blockly.Python');

Blockly.Python['move'] = function(block) {
  var forward_speed = Blockly.Python.valueToCode(block, 'FORWARD_SPEED', Blockly.Python.ORDER_ATOMIC);
  var turn_degrees = Blockly.Python.valueToCode(block, 'TURN_DEGREES', Blockly.Python.ORDER_ATOMIC);
  var duration = Blockly.Python.valueToCode(block, 'DURATION', Blockly.Python.ORDER_ATOMIC);
  // TODO: Assemble Python into code variable.
  var code = "";
  code += "forward_speed = " + forward_speed.toString() + "\n";
  code += "turn_degrees = " + turn_degrees.toString() + "\n";
  code += "duration = " + duration.toString() + "\n\n";
  code += Blockly.readPythonFile("../blockly/generators/python/scripts/duckietown/drive.py");
  // return Python code
  return code;
};
