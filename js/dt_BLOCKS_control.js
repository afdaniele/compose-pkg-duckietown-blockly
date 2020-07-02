"use strict";


Blockly.Blocks["wait"] = {
  init: function () {
    this.appendDummyInput()
      .appendField("Wait ")
      .appendField(new Blockly.FieldTextInput("1"), "WAIT_SECS")
      .appendField("seconds");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setColour("%{BKY_CONTROL_HUE}");
    this.setTooltip("");
    this.setHelpUrl("");
  },
};
