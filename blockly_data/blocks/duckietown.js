'use strict';

goog.provide('Blockly.Blocks.duckietown');

goog.require('Blockly.Blocks');


Blockly.Blocks['move'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Drive Duckiebot");
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
    this.setTooltip("Moves the Duckiebot");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['turn_left'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Turn Left");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Turn the Duckiebot to the left");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['turn_right'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Turn Right");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Turn the Duckiebot to the right");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['forward'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Move forward");
    this.appendValueInput("DURATION")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField("For (seconds)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Duckiebot forward");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['forward_blocks'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Move forward");
    this.appendValueInput("NO_BLOCKS")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField("For (blocks)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Duckiebot forward");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['backward'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Move backward");
    this.appendValueInput("DURATION")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField("For (seconds)");
    this.setInputsInline(false);
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour("%{BKY_DUCKIETOWN_HUE}");
    this.setTooltip("Move the Duckiebot backward");
    this.setHelpUrl("");
  }
};

Blockly.Blocks['stop'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("        Stop Duckiebot");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(0);
    this.setTooltip("Stop the Duckiebot");
    this.setHelpUrl("");
  }
};
