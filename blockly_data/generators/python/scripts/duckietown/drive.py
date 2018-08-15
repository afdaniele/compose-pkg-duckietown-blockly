# Blockly variables
# > forward_speed
# > turn_speed
# > duration
#
# TEST INPUT
# forward_speed = 1.0
# turn_speed = 2.0
# duration = 3.0

import rospy
import json
from std_msgs.msg import String
from numbers import Number

def send_msg():
    global forward_speed, turn_speed, duration
    pub = rospy.Publisher('blockly_drive_json_cmd', String, queue_size=1)
    # make sure the args are numbers
    if not isinstance(forward_speed, Number):
        forward_speed = 0.0
    if not isinstance(turn_speed, Number):
        turn_speed = 0.0
    if not isinstance(duration, Number):
        duration = 0.0
    # put command in a JSON string
    json_str = json.dumps({
        'forward_speed' : float(forward_speed),
        'turn_speed' : float(turn_speed),
        'duration' : float(duration)
    })
    # send JSON string
    pub.publish(json_str)

if __name__ == '__main__':
    try:
        send_msg()
    except rospy.ROSInterruptException:
        pass
