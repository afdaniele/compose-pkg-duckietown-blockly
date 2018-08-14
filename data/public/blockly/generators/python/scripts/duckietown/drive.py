import rospy
from std_msgs.msg import String

# Blockly variables
# > forward_speed
# > turn_degrees
# > duration


def talker():
    pub = rospy.Publisher('chatter', String, queue_size=1)

    hello_str = "I received the command: (V:%.2f, T:%.2f, D:%.2f)" % ( forward_speed, turn_degrees, duration )
    rospy.loginfo(hello_str)
    pub.publish(hello_str)

if __name__ == '__main__':
    try:
        talker()
    except rospy.ROSInterruptException:
        pass
