'use strict';

goog.provide('Blockly.Python.duckietown');
goog.require('Blockly.Python');


// Blockly blocks
Blockly.Python['move'] = function(block) {
    var value_forward_speed = Blockly.Python.valueToCode(block, 'FORWARD_SPEED', Blockly.Python.ORDER_ATOMIC);
    var value_turn_speed = Blockly.Python.valueToCode(block, 'TURN_SPEED', Blockly.Python.ORDER_ATOMIC);
    var value_duration = Blockly.Python.valueToCode(block, 'DURATION', Blockly.Python.ORDER_ATOMIC);
    // compile object
    var obj = {
      "forward_speed" : value_forward_speed,
      "turn_speed" : value_turn_speed,
      "duration" : value_duration
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['turn_left'] = function(block) {
    // compile object
    var obj = {
      "forward_speed" : 0,
      "turn_speed" : 15,
      "duration" : 1
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['turn_right'] = function(block) {
    // compile object
    var obj = {
      "forward_speed" : 0,
      "turn_speed" : -15,
      "duration" : 1
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['forward'] = function(block) {
    var value_duration = Blockly.Python.valueToCode(block, 'DURATION', Blockly.Python.ORDER_ATOMIC);
    // compile object
    var obj = {
      "forward_speed" : 50,
      "turn_speed" : -5,
      "duration" : value_duration
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['backward'] = function(block) {
    var value_duration = Blockly.Python.valueToCode(block, 'DURATION', Blockly.Python.ORDER_ATOMIC);
    // compile object
    var obj = {
      "forward_speed" : -50,
      "turn_speed" : 5,
      "duration" : value_duration
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['stop'] = function(block) {
    // compile object
    var obj = {
      "forward_speed" : 0,
      "turn_speed" : 0,
      "duration" : 0
    };
    // create JSON object
    return obj_to_code(obj, '/blockly_drive_json_cmd');
};

Blockly.Python['forward_blocks'] = function(block) {
    var no_blocks = Blockly.Python.valueToCode(block, 'NO_BLOCKS', Blockly.Python.ORDER_ATOMIC);
    // compile object
    var obj = {
      "no_blocks" : no_blocks
    };
    // create JSON object
    return obj_to_code_smart(obj, '/blockly_drive_json_smart', '/blockly_drive_loop_control');
};



// Utility functions and objects

var python_template_timed = `
import json
import time
# create publisher
pub = rospy.Publisher('{0}', String, queue_size=10, latch=True)
# create JSON string
{1}
# send JSON string
pub.publish(json.dumps(obj))
# wait for DURATION secs
time.sleep({2})
# stop the Bot
pub.publish(json.dumps({'forward_speed':0,'turn_speed':0,'duration':0}))
`;

var python_template_smart = `
import json
import time
# create publisher
pub = rospy.Publisher('{0}', String, queue_size=10, latch=True)
# create subscriber
pub = rospy.Subscriber('{1}', String, queue_size=1)
pub_trigger = False

def cb(msg):
    global pub_trigger
    pub_trigger = True

# create JSON string
{2}
# send JSON string
pub.publish(json.dumps(obj))

# wait until the image loop is done
while not pub_trigger:
    time.sleep(0.1)
`;

function obj_to_code(obj, topic_name){
    var py_obj = "obj = {\n";
    for(var key in obj){
        py_obj += "{0}'{1}' : eval('{2}'),\n".format(
            Blockly.Python.INDENT, key, obj[key].toString().replace(/\\([\s\S])|(')/g,"\\$1$2")
        );
    }
    py_obj += "}";
    return python_template_timed.format( topic_name, py_obj, obj["duration"] );
}

function obj_to_code_smart(obj, topic_name, control_topic_name){
    var py_obj = "obj = {\n";
    for(var key in obj){
        py_obj += "{0}'{1}' : eval('{2}'),\n".format(
            Blockly.Python.INDENT, key, obj[key].toString().replace(/\\([\s\S])|(')/g,"\\$1$2")
        );
    }
    py_obj += "}";
    return python_template_smart.format( topic_name, control_topic_name, py_obj );
}
