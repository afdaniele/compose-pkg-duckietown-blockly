'use strict';

goog.provide('Blockly.Python.control');
goog.require('Blockly.Python');


Blockly.Python['wait'] = function(block) {
    var secs = block.getFieldValue('WAIT_SECS');
    var code = "";
    code+="import time\n"
    code+="time.sleep("+secs+")\n"
    code+="\n"
    return code;
};
