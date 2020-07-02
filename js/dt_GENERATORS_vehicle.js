"use strict";


if (!Blockly.JavaScript.hasOwnProperty("__apis")) {
  Blockly.JavaScript["__apis"] = {};
}

function is_color_detected(
  color_r,
  color_g,
  color_b,
  start_row,
  end_row,
  threshold
) {
  // get latest message
  let resource_name = "supercamera";
  var msg = window.ROSDB.get(resource_name);
  if (msg == null) {
    console.log('No data received for resource "{0}"'.format(resource_name));
    return false;
  }

  // turn string into array
  var arr = msg.data.split(":");
  if (arr.length != 2) return false;

  // get image shape and data
  var arr_shape = arr[0].split(",");
  var arr_data = arr[1].split(",");

  // compute number of channels
  var width = arr_shape[0];
  var height = arr_shape[1];
  var num_channels = arr_shape[2];

  // extract RGB data
  var min_error = 255;
  var r, g, b;
  for (var i = start_row; i <= end_row; i++) {
    for (var j = 0; j < width; j++) {
      var p = width * num_channels * i + num_channels * j;
      r = arr_data[p];
      g = arr_data[p + 1];
      b = arr_data[p + 2];
      // compute cumulative error
      let error =
        Math.abs(r - color_r) + Math.abs(g - color_g) + Math.abs(b - color_b);
      error /= num_channels;
      min_error = Math.min(min_error, error);
    }
  }
  // ---
  console.log("Error: " + min_error + " <? " + threshold);
  return min_error < threshold;
}

Blockly.JavaScript["__apis"]["is_color_detected"] = is_color_detected;

function json_str(forward_speed, turn_speed) {
  var v_gain = 0.41;
  var omega_gain = 8.3;
  // ---
  var obj = {
    v: forward_speed * v_gain,
    omega: turn_speed * omega_gain,
  };
  return JSON.stringify(obj);
} //json_str

function drive_code(forward_speed, turn_speed, duration) {
  forward_speed = parseFloat(forward_speed) / 100.0;
  turn_speed = parseFloat(turn_speed) / 100.0;
  // ---
  var code =
    'publish("commands", \'' + json_str(forward_speed, turn_speed) + "');\n";
  code += "waitForSeconds(" + duration + ");\n";
  code += 'publish("commands", \'' + json_str(0, 0) + "');\n";
  code += 'pause("commands");\n';
  return code;
} //drive_code

Blockly.JavaScript["move"] = function (block) {
  var forward_speed = Blockly.JavaScript.valueToCode(
    block,
    "FORWARD_SPEED",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  var turn_speed = Blockly.JavaScript.valueToCode(
    block,
    "TURN_SPEED",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  var duration = Blockly.JavaScript.valueToCode(
    block,
    "DURATION",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  // ---
  return drive_code(forward_speed, turn_speed, duration);
};

Blockly.JavaScript["forward"] = function (block) {
  var duration = Blockly.JavaScript.valueToCode(
    block,
    "DURATION",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  // ---
  return drive_code(5.0, 0.0, duration);
};

Blockly.JavaScript["histogram_perception"] = function (block) {
  let color = Blockly.JavaScript.valueToCode(
    block,
    "Bottom Color",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  let tolerance = Blockly.JavaScript.valueToCode(
    block,
    "TOLERANCE",
    Blockly.JavaScript.ORDER_ATOMIC
  );
  let code = "is_color_detected({0}, {1}, {2}, {3}, {4}, {5})";

  function hexToRgb(hex) {
    var result = /^'#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})'$/i.exec(hex);
    return result
      ? {
          r: parseInt(result[1], 16),
          g: parseInt(result[2], 16),
          b: parseInt(result[3], 16),
        }
      : null;
  }

  color = hexToRgb(color);
  code = code.format(color.r, color.g, color.b, 1, 4, tolerance);

  return [code, Blockly.JavaScript.ORDER_NONE];
};
