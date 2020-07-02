"use strict";


Blockly.Blocks["move"] = {
  init: function () {
    this.appendDummyInput().appendField("        Drive Vehicle");
    this.appendValueInput("FORWARD_SPEED")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("Forward (%)");
    this.appendValueInput("TURN_SPEED")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("Turn (%)");
    this.appendValueInput("DURATION")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("For (seconds)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Moves the Vehicle");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["turn_left"] = {
  init: function () {
    this.appendDummyInput().appendField("        Turn Left");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Turn the Vehicle to the left");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["turn_right"] = {
  init: function () {
    this.appendDummyInput().appendField("        Turn Right");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Turn the Vehicle to the right");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["forward"] = {
  init: function () {
    this.appendDummyInput().appendField("        Move forward");
    this.appendValueInput("DURATION")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("For (seconds)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Vehicle forward");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["forward_blocks"] = {
  init: function () {
    this.appendDummyInput().appendField("        Move forward");
    this.appendValueInput("NO_BLOCKS")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("For (blocks)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Vehicle forward");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["backward"] = {
  init: function () {
    this.appendDummyInput().appendField("        Move backward");
    this.appendValueInput("DURATION")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("For (seconds)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Vehicle backward");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["stop"] = {
  init: function () {
    this.appendDummyInput().appendField("        Stop Vehicle");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Stop the Vehicle");
    this.setHelpUrl("");
  },
};

Blockly.Blocks["histogram_perception"] = {
  init: function () {
    this.appendValueInput("Bottom Color")
      .setCheck("Colour")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("This color is visible");
    this.appendValueInput("TOLERANCE")
      .setCheck("Number")
      .setAlign(Blockly.ALIGN_RIGHT)
      .appendField("Tolerance 0-255");
    this.setOutput(true, "Boolean");
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip(
      "True if the given color is visible in the camera image; False otherwise."
    );
    this.setHelpUrl("");
  },
};
