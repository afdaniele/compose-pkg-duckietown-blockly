<!--========================================Logger===========================================-->
<?php function console_log($output, $with_script_tags = true){
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}?>
<!--=========================================END Logger======================================-->

<!--=====================================File Import=========================================-->
<?php
    $this_package = 'duckietown_blockly';

    use \system\classes\Configuration;
    use \system\classes\Core;
    use \system\packages\duckietown_duckiebot\Duckiebot;
    use \system\packages\ros\ROS;

    include __DIR__ . '/toolbox.xml';

    $DEBUG = isset($_GET['debug']) && boolval($_GET['debug']);
?>

<!-- Include Blocky -->
<script src="<?php echo Core::getJSscriptURL('blockly_compressed.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('blocks_compressed.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('javascript_compressed.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('blockly_messages.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('acorn_interpreter.js', $this_package) ?>"></script>


<!-- Include ROS -->
<script src="<?php echo Core::getJSscriptURL('rosdb.js', 'ros') ?>"></script>
<script src="<?php echo Core::getJSscriptURL('roslib.min.js', 'ros') ?>"></script>
<!-- Code execution logic -->
<script src="<?php echo Core::getJSscriptURL('dt_execution_logic.js', $this_package) ?>"></script>

<!-- Blockly: Blocks -->
<script src="<?php echo Core::getJSscriptURL('dt_custom_msg_en.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('dt_BLOCKS_vehicle.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('dt_BLOCKS_control.js', $this_package) ?>"></script>

<!-- Blockly: Generators -->
<script src="<?php echo Core::getJSscriptURL('dt_GENERATORS_vehicle.js', $this_package) ?>"></script>
<script src="<?php echo Core::getJSscriptURL('dt_GENERATORS_control.js', $this_package) ?>"></script>

<!--=========================================End File Import=======================================-->


<!--====================================ROS Happy Sauce!============================================-->
<?php //! Robot Initialization
    $vehicle_name = Duckiebot::getDuckiebotName();
    if (ip2long($vehicle_name) == true or $vehicle_name == "localhost") {
        console_log("[FATAL] Your Duckiebot Name seems to be an ip!");
        throw new Exception("Vehicle IP obtained wrongly!");
    }
    ROS::connect();
    console_log("[DEBUG] Obatined Duckiebot is: " . $vehicle_name);
    console_log("[INFO] Is ROS Initialized? " . (ROS::isInitialized() ? 'Yes' : 'NO!'))
?>

<script type="text/javascript"> //! Set the ROS Bridge Status Indicator!
    $(document).on('<?php echo ROS::get_event(ROS::$ROSBRIDGE_CONNECTED) ?>', function(evt) {
        console.log("[INFO] Report ROS Bridge Connected!")
        ExecutionLogicModule.set_current_status(
            ExecutionLogicModule.STATUS.COMPLETED
        );
        $('#ros_bridge_status_icon').css('color', 'green');
    });

    $(document).on('<?php echo ROS::get_event(ROS::$ROSBRIDGE_ERROR) ?>', function(evt, error) {
        console.log("[ERROR] Report ROS Bridge ERROR!")
        ExecutionLogicModule.set_current_status(
            ExecutionLogicModule.STATUS.COMPLETED
        );
        $('#ros_bridge_status_icon').css('color', 'orangered');
    });

    $(document).on('<?php echo ROS::get_event(ROS::$ROSBRIDGE_CLOSED) ?>', function(evt) {
        console.log("[ERROR] Report ROS Bridge Closed!")
        ExecutionLogicModule.set_current_status(
            ExecutionLogicModule.STATUS.NOT_CONNECTED
        );
        $('#ros_bridge_status_icon').css('color', 'black');
    });
</script>

<script type="text/javascript"> //! Set up ROS subscriber and publishers:
    window.ros_resources = {
        camera: {
            topic_name: '/<?php echo $vehicle_name ?>/camera_node/image/compressed',
            messageType: 'sensor_msgs/CompressedImage',
            queue_size: 1,
            frequency: 8
        },
        commands: {
            topic_name: '/<?php echo $vehicle_name ?>/joy_mapper_node/car_cmd',
            messageType: 'duckietown_msgs/Twist2DStamped',
            queue_size: 1,
            frequency: 10
        },
        supercamera: {
            topic_name: '/<?php echo $vehicle_name ?>/histogram_perception/histogram',
            messageType: 'std_msgs/String',
            queue_size: 1,
            frequency: 10
        },
        estop:{
            topic_name: '/<?php echo $vehicle_name ?>/wheels_driver_node/emergency_stop',
            messageType: 'duckietown_msgs/BoolStamped',
            queue_size: 1,
            frequency: 10
        }
    };
    window.blockly_requires = [];
    window.blockly_provides = [];
</script>

<script type="text/javascript"> //! ROS Updates:
    var data_status_template = `
        <div class="ros-topic-status text-center">
            <i id="{2}-data-source-status" class="fa fa-arrow-circle-{0}" aria-hidden="true" title="0.0 Hz"></i>
            <span>{1}</span>
        </div>
    `;

    function update_ros_status(event) {
        requires = [];
        provides = ["estop","commands"];
        window.blockly_ws.getAllBlocks().forEach(function(block) {
            if (block.hasOwnProperty('data')) {
                data = JSON.parse(block.data);
                if (data.hasOwnProperty('requires')) {
                    requires = requires.concat(requires, data['requires']);
                }
                if (data.hasOwnProperty('provides')) {
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
        // simplified connection indicator:
        if (requires.length!=0){
            $('#ros_topic_status_container').html(
                $('#ros_topic_status_container').html() +
                data_status_template.format(
                    'down',requires.length
                )
            );
        }
        if (provides.length!=0){
            $('#ros_topic_status_container').html(
                $('#ros_topic_status_container').html() +
                data_status_template.format(
                    'up','x'+provides.length
                )
            );
        }
        //TODO: Fix for a better way of illustration resource usage!
        // ---
        // for (var i in requires) {
        //     $('#ros_topic_status_container').html(
        //         $('#ros_topic_status_container').html() +
        //         data_status_template.format(
        //             'down',
        //             requires[i].charAt(0).toUpperCase() + requires[i].slice(1),
        //             requires[i]
        //         )
        //     );
        // }
        // for (var i in provides) {
        //     $('#ros_topic_status_container').html(
        //         $('#ros_topic_status_container').html() +
        //         data_status_template.format(
        //             'up',
        //             provides[i].charAt(0).toUpperCase() + provides[i].slice(1),
        //             provides[i]
        //         )
        //     );
        // }
        // ---
        if ((requires.length + provides.length) == 0) {
            $('#ros_topic_status_container').html('(empty)');
        }
        window.blockly_requires = requires;
        window.blockly_provides = provides;
    } //update_ros_status

    function update_data_status() {
        // resources = $.merge(window.blockly_requires, window.blockly_provides);
        resources_list = [
            window.blockly_requires,
            window.blockly_provides
        ];
        hz_0_colors = [
            'red',
            'black'
        ];
        for (var j in resources_list) {
            var resources = resources_list[j];
            var color = hz_0_colors[j];
            for (var i in resources) {
                var resource_name = resources[i];
                var expected_hz = window.ros_resources[resource_name]['frequency'];
                var elem = $('#{0}-data-source-status'.format(resource_name));
                var hz = window.ROSDB.hz(resource_name);
                if (hz >= 0.6 * expected_hz)
                    color = 'green';
                if (hz > 0.4 * expected_hz && hz < 0.6 * expected_hz)
                    color = 'orange';
                elem.css('color', color);
                elem.prop('title', '{0} Hz'.format(hz.toFixed(2)));
            }
        }
    } //update_data_status

    // restore at the beginning
    $(window).load(function() {
        restorelocal();
        window.blockly_ros_topics = {
            'subscribed': [],
            'advertised': []
        };
        window.blockly_ws.addChangeListener(update_ros_status);

        setInterval(update_data_status, 100);
    });

    function restorelocal() {
        var xml_text = localStorage.getItem("blocks_cache");
        try {
            var xml = Blockly.Xml.textToDom(xml_text);
            Blockly.Xml.domToWorkspace(window.blockly_ws, xml);
            window.blockly_ws.getAllBlocks().forEach(function(block) {
                block.setDeletable(true);
                block.setMovable(true);
                try {
                    block.setEditable(true);
                } catch (e) {}
            });
            automate_localstorage();
        } catch (err) {
            automate_localstorage();
        }
    } //restorelocal

    function automate_localstorage() {
        localstorage();
        setTimeout(automate_localstorage, 1000);
    } //automate_localstorage

    function localstorage() {
        // save stuff on local storage
        var xml = Blockly.Xml.workspaceToDom(window.blockly_ws);
        var xml_text = Blockly.Xml.domToText(xml);
        localStorage.setItem("blocks_cache", xml_text);
        var xml_text_stored = localStorage.getItem("blocks_cache");
    } //localstorage
</script>
<!--=========================================End ROS Happy Sauce!==================================-->

<!--=============================Start Page Configuration!=========================================-->
<style>
    <?php include 'CSS/main.css'; ?>
</style>
<table style="width:100%"> <!--Header Buttons-->
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
        <td style="min-width:160px">
            <?php
                include_once "components/take_over.php";
            ?>
        </td>
        <td class="text-center" style="width:40%; padding-top:10px">
            <i class="fa fa-toggle-on" aria-hidden="true"></i> Mode:
            <strong id="vehicle_driving_mode_status">ESTOPPED!</strong>
        </td>

    </tr>
    <tr>
        <td colspan="3">
            <div id="wrapper">
                <div id="page-wrapper">
                    <div id="blocklyArea" style="height:58vh;"></div>
                </div>
            </div>
            <div id="blocklyDiv" style="position: absolute"></div>
        </td>
    </tr>
    <tr>
        <td style="width:100%">
                <div class="panel panel-default" style="float:left">
                    <div class="panel-heading" role="tab" style="height:34px; padding-top: 6px; resize: auto">
                        <table>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-th" id="ros_bridge_status_icon" aria-hidden="true" style="color:red"></span>
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
      <td style="padding-top:6px">
      <p style="margin:0">Execution Log:</p>
      <textarea id="log_area" style="width:100%; height:9vh; resize:auto" readonly></textarea>
      </td>
    </tr>

</table>
<script type="text/javascript"> //! Blockly Inject
    var blocklyArea = document.getElementById('blocklyArea');
    var blocklyDiv = document.getElementById('blocklyDiv');
    window.blockly_ws = Blockly.inject(
        blocklyDiv, {
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
            trashcan: true,
            media: '<?php echo Configuration::$BASE ?>/data/<?php echo $this_package ?>/media/'
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
</script>
<script type="text/javascript"> //! Clean Workspace Button
    function clean_ws() {
        openYesNoModal(
            'Are you sure you want to clean the workspace?<br/>All unsaved progress will be lost.',
            ExecutionLogicModule.clean_ws,
            true /*silentMode*/
        );
    } //clean_ws
</script>
<!--==============================End Page Configuration!===========================================-->


<!--===================================Debugger========================================================-->
<?php
if ($DEBUG) {
    ?>
        <br />
        <textarea id="debug_code_textarea" style="width:100%; height:24vh; resize:none" readonly></textarea>
        <script type="text/javascript">
            function show_code(event) {
                let code = Blockly.JavaScript.workspaceToCode(window.blockly_ws);
                document.getElementById('debug_code_textarea').value = code;
            }

            window.blockly_ws.addChangeListener(show_code);
        </script>
    <?php
}
require_once $GLOBALS['__EMBEDDED__PACKAGES__DIR__'] . '/core/modules/modals/yes_no_modal.php';
?>
<!--===================================END Debugger====================================================-->