<?php
use \system\classes\Core;
use \system\classes\Configuration;
use \system\classes\Database;
use \system\packages\ros\ROS;
use \system\packages\duckietown_duckiebot\Duckiebot;
?>

<span style="float: right; font-size: 12pt">Take over&nbsp;
  <input type="checkbox"
      data-toggle="toggle"
      data-onstyle="primary"
      data-offstyle="warning"
      data-class="fast"
      data-size="small"
      name="vehicle_driving_mode_toggle"
      id="vehicle_driving_mode_toggle">
</span>

<?php
// TODO: get these from ROS param
$v_gain = 0.5;
$omega_gain = 8.3;
$sensitivity = 0.5;
$output_commands_hz = 10.0;
$vehicle_name = Duckiebot::getDuckiebotName();

// apply sensitivity
$omega_gain *= $sensitivity;
?>

<script type="text/javascript">

  // define the list of keys that can be used to drive the vehicle
  window.mission_control_Keys = {
    SPACE: 32,
    UP_ARROW: 38,
    LEFT_ARROW: 37,
    DOWN_ARROW: 40,
    RIGHT_ARROW: 39,
    W: 87,
    A: 65,
    S: 83,
    D: 68,
  };

  // define buffer of pressed keys
  window.mission_control_keyMap = {};
  // set all the keys to False (i.e., not-pressed)
  for (let item in window.mission_control_Keys) {
    if (isNaN(Number(item))) {
      window.mission_control_keyMap[window.mission_control_Keys[item]] = false;
    }
  }

  // capture keyboard events (and update buffer accordingly)
  function key_cb(e){
    if (window.mission_control_Mode != 'manual')
      return;
    // space and arrow keys
    if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
      e.preventDefault();
      window.mission_control_keyMap[e.keyCode] = e.type == "keydown";
    }
  }//key_cb

  // define the callback function that turns the key_map into a ROS message
  function publish_command(){
    if (window.mission_control_Mode != 'manual')
      return;
    if (window.mission_control_cmdVel == undefined)
      return;
    keys = window.mission_control_Keys;
    key_map = window.mission_control_keyMap;
    // compute linear/angular speeds
    v_gain = <?php echo $v_gain ?>;
    omega_gain = <?php echo $omega_gain ?>;
    v_val = Math.min(key_map[keys.UP_ARROW] + key_map[keys.W], 1) - Math.min(key_map[keys.DOWN_ARROW] + key_map[keys.S], 1);
    omega_val = Math.min(key_map[keys.LEFT_ARROW] + key_map[keys.A], 1) - Math.min(key_map[keys.RIGHT_ARROW] + key_map[keys.D], 1);
    // create a message
    var car_cmd = new ROSLIB.Message({
      v : v_val * v_gain,
      omega : omega_val * omega_gain
    });
    // publish message
    window.mission_control_cmdVel.publish(car_cmd);
  }//publish_command

  // publish command at regular rate
  $(document).on('<?php echo ROS::$ROSBRIDGE_CONNECTED ?>', function(e){
    // define the output topic
    window.mission_control_cmdVel = new ROSLIB.Topic({
      ros : window.ros,
      name : '/<?php echo $vehicle_name ?>/car_interface/car_cmd',
      messageType : 'duckietown_msgs/Twist2DStamped',
      queue_size : 1
    });
    // attach listeners to key events
    window.addEventListener("keyup", key_cb, false);
    window.addEventListener("keydown", key_cb, false);
    // start publishing commands to the vehicle
    setInterval(publish_command, <?php echo intval(1000.0 / $output_commands_hz) ?>);
  });

  $(document).ready(function() {
    window.mission_control_Mode = 'autonomous';
  });

  $('#vehicle_driving_mode_toggle').change(function(){
    if ($(this).prop('checked')){
      // change the page background
      $('body').css('background-image', 'linear-gradient(to top, #F7F7F6, #FFC800, #F7F7F6)');
      $('#vehicle_driving_mode_status').html('Manual');
      window.mission_control_Mode = 'manual';
    }else{
      $('body').css('background-image', 'none');
      $('#vehicle_driving_mode_status').html('Autonomous');
      window.mission_control_Mode = 'autonomous';
    }
  });

</script>
