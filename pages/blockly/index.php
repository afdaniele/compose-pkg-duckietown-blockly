<?php
use \system\classes\Core;
use \system\classes\Configuration;
use \system\packages\ros\ROS;
use \system\packages\duckietown\Duckietown;
use \system\packages\duckietown_duckiebot\Duckiebot;

$DEBUG = isset($_GET['debug']) && boolval($_GET['debug']);

$duckiebot_name = Duckiebot::getDuckiebotName();
?>

<style type="text/css">
body > #page_container{
  min-width: 96%;
}

body > #page_container > #page_canvas{
  margin-bottom: 0;
}

.btn-static {
  /* background-color: white; */
  border: 1px solid lightgrey;
  cursor: default;
}

.btn-static:active {
  -moz-box-shadow:    inset 0 0 0px white;
  -webkit-box-shadow: inset 0 0 0px white;
  box-shadow:         inset 0 0 0px white;
}

.panel > .panel-heading a{
  color: inherit;
  text-decoration: none;
}

.ros-topic-status{
  display: inline-block;
  margin: 0 4px;
  font-size: 15px;
  padding-right: 10px;
  border-right: 1px solid lightgrey;
}

.ros-topic-status .fa{
  font-size: 18px;
}

#ros_topic_status_container .ros-topic-status:last-child{
  padding-right: 0;
  border-right: none;
}
</style>

<!-- Include Blocky -->
<script src="<?php echo Core::getJSscriptURL('blockly_compressed.js', 'duckietown_blockly') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('blocks_compressed.js', 'duckietown_blockly') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('javascript_compressed.js', 'duckietown_blockly') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('blockly_msg_en.js', 'duckietown_blockly') ?>"></script>

<!-- Include ROS -->
<script src="<?php echo Core::getJSscriptURL('rosdb.js', 'ros') ?>"></script>

<!-- Code execution logic -->
<script src="<?php echo Core::getJSscriptURL('execution_logic.js', 'duckietown_blockly') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('custom_msg_en.js', 'duckietown_blockly') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('acorn_interpreter.js', 'duckietown_blockly') ?>"></script>


<table style="width:100%">
  <tr>
    <td style="width:500px; min-width:500px; max-width:500px">
      <div class="btn-group btn-group-justified" role="group" style="margin:20px 0">
        <a role="button" class="btn btn-default" id="launch_button" name="launch_button" onclick="ExecutionLogicModule.toggle_run();">
          <i id="launch_button_icon" class="fa fa-spinner" aria-hidden="true"></i>
          &nbsp;
          <span id="launch_button_span">Connecting...</span>
        </a>
        <a role="button" class="btn btn-danger" id="end_button" name="end_button" href="#" onclick='ExecutionLogicModule.end_execution();' style="display:none;">
          <i class="fa fa-stop" aria-hidden="true"></i>
          &nbsp;
          Stop
        </a>
        <a role="button" class="btn btn-default" id="load_from_file_button" name="load_from_file_button" href="#" onclick='ExecutionLogicModule.load_from_file();'>
          <i class="fa fa-download" aria-hidden="true"></i>
          &nbsp;
          Load code
        </a>
        <a role="button" class="btn btn-default" id="save_to_file_button" name="save_to_file_button" href="#" onclick='ExecutionLogicModule.save_to_file();'>
          <i class="fa fa-upload" aria-hidden="true"></i>
          &nbsp;
          Save code
        </a>
        <a role="button" class="btn btn-warning" id="clean_ws_button" name="clean_ws_button" href="#" onclick='clean_ws();'>
          <i class="fa fa-eraser" aria-hidden="true"></i>
          &nbsp;
          Clean
        </a>
      </div>
    </td>
    <td style="width:100%">
      <div class="panel panel-default" style="margin:20px 0 20px 40px; min-width:100px; float:right">
        <div class="panel-heading" role="tab" style="height:34px; padding-top: 6px;">
          <table>
            <tr>
              <td>
                <strong>
                  <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                  &nbsp;
                  ROS:
                  &nbsp;
                </strong>
              </td>
              <td id="ros_topic_status_container">(empty)</td>
            </tr>
          </table>
        </div>
      </div>
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <div id="wrapper">
        <div id="page-wrapper">
          <div id="blocklyArea" style="height:58vh;"></div>
        </div>
      </div>
      <div id="blocklyDiv" style="position: absolute"></div>
    </td>
  </tr>
  <!-- <tr>
    <td style="padding-top:6px">
    <p style="margin:0">Execution Log:</p>
    <textarea id="log_area" style="width:100%; height:9vh; resize:none" readonly></textarea>
    </td>
  </tr> -->

</table>

<?php
ROS::connect();
?>

<?php
include __DIR__.'/toolbox.xml';
?>

<script type="text/javascript">

  window.ros_resources = {
    camera : {
      topic_name : '<?php echo $duckiebot_name ?>/camera_node/image/compressed',
      messageType : 'sensor_msgs/CompressedImage',
      queue_size : 1,
      frequency : 8
    },
    commands : {
      topic_name : '<?php echo $duckiebot_name ?>/joy',
      messageType : 'sensor_msgs/Joy',
      queue_size : 1,
      frequency : 20
    }
  };

  window.blockly_requires = [];
  window.blockly_provides = [];

  $(document).on('<?php echo ROS::$ROSBRIDGE_CONNECTED ?>', function(evt){
    ExecutionLogicModule.set_current_status(
      ExecutionLogicModule.STATUS.COMPLETED
    );
  });

  $(document).on('<?php echo ROS::$ROSBRIDGE_ERROR ?>', function(evt, error){
    ExecutionLogicModule.set_current_status(
      ExecutionLogicModule.STATUS.COMPLETED
    );
  });

  $(document).on('<?php echo ROS::$ROSBRIDGE_CLOSED ?>', function(evt){
    ExecutionLogicModule.set_current_status(
      ExecutionLogicModule.STATUS.NOT_CONNECTED
    );
  });

  var data_status_template = `
  <div class="ros-topic-status text-center">
    <i id="{2}-data-source-status" class="fa fa-arrow-circle-{0}" aria-hidden="true" title="0.0 Hz"></i>
    <span>{1}</span>
  </div>
  `;

  var blocklyArea = document.getElementById('blocklyArea');
  var blocklyDiv = document.getElementById('blocklyDiv');
  window.blockly_ws = Blockly.inject(
    blocklyDiv,
    {
      toolbox: document.getElementById('toolbox'),
      scrollbars: true,
      rtl: false,
      zoom: {
        enabled: true,
        controls: true,
        wheel: true,
        startScale: 0.9,
        maxScale: 2.5,
        minScale: 0.5,
        scaleSpeed: 1.1
      },
      grid: {
        spacing: 25,
        length: 3,
        colour: '#ccc',
        snap: false
      },
      trashcan: true
    }
  );

  var onresize = function(e) {
    // Compute the absolute coordinates and dimensions of blocklyArea.
    var element = blocklyArea;
    var x = 0;
    var y = 0;
    do {
      x += element.offsetLeft;
      y += element.offsetTop;
      element = element.offsetParent;
    } while (element);
    // Position blocklyDiv over blocklyArea.
    blocklyDiv.style.left = x + 'px';
    blocklyDiv.style.top = y + 'px';
    blocklyDiv.style.width = blocklyArea.offsetWidth + 'px';
    blocklyDiv.style.height = blocklyArea.offsetHeight + 'px';
    Blockly.svgResize(window.blockly_ws);
  };

  window.addEventListener('resize', onresize, false);
  onresize();
  Blockly.svgResize(window.blockly_ws);

  function restorelocal(){
    var xml_text = localStorage.getItem("blocks_cache");
    try {
      var xml = Blockly.Xml.textToDom(xml_text);
      Blockly.Xml.domToWorkspace(window.blockly_ws, xml);
      window.blockly_ws.getAllBlocks().forEach(function(block){
        block.setDeletable(true);
        block.setMovable(true);
        try {
          block.setEditable(true);
        }catch(e){}
      });
      automate_localstorage();
    }catch(err){
      automate_localstorage();
    }
  }//restorelocal

  function automate_localstorage(){
    localstorage();
    setTimeout(automate_localstorage, 1000);
  }//automate_localstorage

  function localstorage() {
    // save stuff on local storage
    var xml = Blockly.Xml.workspaceToDom(window.blockly_ws);
    var xml_text = Blockly.Xml.domToText(xml);
    localStorage.setItem("blocks_cache", xml_text);
    var xml_text_stored = localStorage.getItem("blocks_cache");
  }//localstorage

  function clean_ws(){
    openYesNoModal(
      'Are you sure you want to clean the workspace?<br/>All unsaved progress will be lost.',
      ExecutionLogicModule.clean_ws,
      true /*silentMode*/
    );
  }//clean_ws

  function update_ros_status(event) {
    requires = [];
    provides = [];
    window.blockly_ws.getAllBlocks().forEach(function(block){
      if(block.hasOwnProperty('data')){
        data = JSON.parse(block.data);
        if(data.hasOwnProperty('requires')){
          requires = requires.concat(requires, data['requires']);
        }
        if(data.hasOwnProperty('provides')){
          provides = provides.concat(provides, data['provides']);
        }
      }
    });
    requires = $.unique(requires);
    provides = $.unique(provides);
    // ---
    $('#ros_topic_status_container').html('');
    // ---
    to_subscribe = $(requires).not(window.blockly_requires).get();
    to_unsubscribe = $(window.blockly_requires).not(requires).get();
    to_advertise = $(provides).not(window.blockly_provides).get();
    to_unadvertise = $(window.blockly_provides).not(provides).get();
    // ---
    for (var i in to_unsubscribe) {
      ROSDB.unsubscribe(to_unsubscribe[i]);
    }
    for (var i in to_subscribe) {
      ROSDB.subscribe(
        to_subscribe[i],
        window.ros_resources[to_subscribe[i]]['topic_name'],
        window.ros_resources[to_subscribe[i]]['messageType'],
        window.ros_resources[to_subscribe[i]]['frequency'],
        window.ros_resources[to_subscribe[i]]['queue_size']
      );
    }
    for (var i in to_unadvertise) {
      ROSDB.unadvertise(to_unadvertise[i]);
    }
    for (var i in to_advertise) {
      ROSDB.advertise(
        to_advertise[i],
        window.ros_resources[to_advertise[i]]['topic_name'],
        window.ros_resources[to_advertise[i]]['messageType'],
        window.ros_resources[to_advertise[i]]['frequency'],
        window.ros_resources[to_advertise[i]]['queue_size']
      );
    }
    // ---
    for (var i in requires) {
      $('#ros_topic_status_container').html(
        $('#ros_topic_status_container').html() +
        data_status_template.format(
          'down',
          requires[i].charAt(0).toUpperCase() + requires[i].slice(1),
          requires[i]
        )
      );
    }
    for (var i in provides) {
      $('#ros_topic_status_container').html(
        $('#ros_topic_status_container').html() +
        data_status_template.format(
          'up',
          provides[i].charAt(0).toUpperCase() + provides[i].slice(1),
          provides[i]
        )
      );
    }
    // ---
    if( (requires.length + provides.length) == 0){
      $('#ros_topic_status_container').html('(empty)');
    }
    window.blockly_requires = requires;
    window.blockly_provides = provides;
  }//update_ros_status

  function update_data_status(){
    // resources = $.merge(window.blockly_requires, window.blockly_provides);
    resources_list = [
      window.blockly_requires,
      window.blockly_provides
    ];
    hz_0_colors = [
      'red',
      'black'
    ];
    for (var j in resources_list){
      var resources = resources_list[j];
      var color = hz_0_colors[j];
      for (var i in resources) {
        var resource_name = resources[i];
        var expected_hz = window.ros_resources[resource_name]['frequency'];
        var elem = $('#{0}-data-source-status'.format(resource_name));
        var hz = window.ROSDB.hz(resource_name);
        if( hz >= 0.6 * expected_hz)
          color = 'green';
        if( hz > 0.4 * expected_hz && hz < 0.6 * expected_hz)
          color = 'orange';
        elem.css('color', color);
        elem.prop('title', '{0} Hz'.format(hz.toFixed(2)));
      }
    }
  }//update_data_status

  // restore at the beginning
  $(window).load(function() {
    restorelocal();
    window.blockly_ros_topics = {
      'subscribed' : [],
      'advertised' : []
    };
    window.blockly_ws.addChangeListener(update_ros_status);

    // TODO(andrea): to be removed
    // ExecutionLogicModule.set_current_status(
    //   ExecutionLogicModule.STATUS.COMPLETED
    // );
    // TODO(andrea): to be removed

    setInterval(update_data_status, 100);
  });



</script>



<?php
if($DEBUG){
  ?>
  <br/>
  <textarea id="debug_code_textarea" style="width:100%; height:24vh; resize:none" readonly></textarea>
  <script type="text/javascript">
  function show_code(event) {
    var code = Blockly.JavaScript.workspaceToCode(window.blockly_ws);
    document.getElementById('debug_code_textarea').value = code;
  }
  window.blockly_ws.addChangeListener(show_code);
  </script>
  <?php
}

require_once __DIR__.'/../../../core/modules/modals/yes_no_modal.php';
?>
