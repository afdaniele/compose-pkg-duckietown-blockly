"use strict";


Blockly.JavaScript["wait"] = function (block) {
  var seconds = Number(block.getFieldValue("WAIT_SECS"));
  var code = "waitForSeconds(" + seconds + ");\n";
  return code;
};
