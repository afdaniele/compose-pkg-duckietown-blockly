<?php
use \system\classes\Configuration;
?>

<style type="text/css">
    body > #page_container{
        min-width: 100%;
    }
</style>

    <!-- Bootstrap Core CSS -->
    <!-- <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- MetisMenu CSS -->
    <!-- <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <!-- <link href="../dist/css/sb-admin-2.css" rel="stylesheet"> -->

    <!-- Custom Fonts -->
    <!-- <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->

    <!-- Include Blocky -->
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/blockly_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/blocks_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/javascript_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/python_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/php_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/msg/js/en.js"></script>

    <!-- Ubuntu fonts -->
    <!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"> -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/ubuntu.css">
    <style>body { font-family: Ubuntu, sans-serif; }</style> -->



<!-- <body onload="restorelocal()"> -->

    <div id="wrapper">

        <!-- Navigation -->
        <!-- <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0"> -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a id="builder" href="#"><i class="fa fa-cubes fa-fw"></i> Builder<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a id="launch_button" name="launch_button" href="#" onclick="ExecutionLogicModule.launch_code($('input[name=group_py]:checked').val());">Launch</a>
                                </li>
                                <li>
                                    <a id="end_button" name="end_button" href="#" onclick='ExecutionLogicModule.end_execution();' style="display:none;">End execution</a>
                                </li>
                                <li>
                                    <a id="refresh_button" name="refresh_button" href="blockly.html" style="display:none;">Refresh</a>
                                </li>
                                <li>
                                    <a id="load_from_file_button" name="load_from_file_button" href="#" onclick='ExecutionLogicModule.load_from_file();'>Load From File</a>
                                </li>
                                <li>
                                    <a id="save_to_file_button" name="save_to_file_button" href="#" onclick='ExecutionLogicModule.save_to_file();'>Save To File</a>
                                </li>
                                <li>
                                    <a id="clean_ws_button" name="clean_ws_button" href="#" onclick='ExecutionLogicModule.clean_ws();'>Clean workspace</a>
                                </li>
                                <li>
                                    <div id="pythondiv" style="color:#75A8D3; text-align:center" >
                                        <span>Python:</span>
                                        <input id="python3" type="radio" value="3" name="group_py" />3
                                        <input id="python2" type="radio" value="2" name="group_py" checked/>2
                                    </div>
                                </li>

                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        <!-- </nav> -->

        <!-- Page Content -->
        <div id="page-wrapper">

            <div id="blocklyArea" style="height:100vh;"></div>

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <div id="blocklyDiv" style="position: absolute"></div>

    <xml id="toolbox" style="display: none">
        <category id="catLogic" name="Logic">
          <block type="controls_if"></block>
          <block type="logic_compare"></block>
          <block type="logic_operation"></block>
          <block type="logic_negate"></block>
          <block type="logic_boolean"></block>
          <block type="logic_null"></block>
          <block type="logic_ternary"></block>
        </category>
        <category id="catLoops" name="Loops">
          <block type="controls_repeat_ext">
            <value name="TIMES">
              <block type="math_number">
                <field name="NUM">10</field>
              </block>
            </value>
          </block>
          <block type="controls_whileUntil"></block>
          <block type="controls_for">
            <value name="FROM">
              <block type="math_number">
                <field name="NUM">1</field>
              </block>
            </value>
            <value name="TO">
              <block type="math_number">
                <field name="NUM">10</field>
              </block>
            </value>
            <value name="BY">
              <block type="math_number">
                <field name="NUM">1</field>
              </block>
            </value>
          </block>
          <block type="controls_forEach"></block>
          <block type="for_time"></block>
          <block type="controls_flow_statements"></block>
        </category>
        <category id="catMath" name="Math">
          <block type="math_number"></block>
          <block type="math_arithmetic"></block>
          <block type="math_single"></block>
          <block type="math_trig"></block>
          <block type="math_constant"></block>
          <block type="math_number_property"></block>
          <block type="math_change">
            <value name="DELTA">
              <block type="math_number">
                <field name="NUM">1</field>
              </block>
            </value>
          </block>
          <block type="math_round"></block>
          <block type="math_on_list"></block>
          <block type="math_modulo"></block>
          <block type="math_constrain">
            <value name="LOW">
              <block type="math_number">
                <field name="NUM">1</field>
              </block>
            </value>
            <value name="HIGH">
              <block type="math_number">
                <field name="NUM">100</field>
              </block>
            </value>
          </block>
          <block type="math_random_int">
            <value name="FROM">
              <block type="math_number">
                <field name="NUM">1</field>
              </block>
            </value>
            <value name="TO">
              <block type="math_number">
                <field name="NUM">100</field>
              </block>
            </value>
          </block>
          <block type="math_random_float"></block>
        </category>
        <category id="catLists" name="Lists">
          <block type="lists_create_with">
            <mutation items="0"></mutation>
          </block>
          <block type="lists_create_with"></block>
          <block type="lists_repeat">
            <value name="NUM">
              <block type="math_number">
                <field name="NUM">5</field>
              </block>
            </value>
          </block>
          <block type="lists_length"></block>
          <block type="lists_isEmpty"></block>
          <block type="lists_indexOf">
            <value name="VALUE">
              <block type="variables_get">
                <field name="VAR" class="listVar">...</field>
              </block>
            </value>
          </block>
          <block type="lists_getIndex">
            <value name="VALUE">
              <block type="variables_get">
                <field name="VAR" class="listVar">...</field>
              </block>
            </value>
          </block>
          <block type="lists_setIndex">
            <value name="LIST">
              <block type="variables_get">
                <field name="VAR" class="listVar">...</field>
              </block>
            </value>
          </block>
          <block type="lists_getSublist">
            <value name="LIST">
              <block type="variables_get">
                <field name="VAR" class="listVar">...</field>
              </block>
            </value>
          </block>
          <block type="lists_split">
            <value name="DELIM">
              <block type="text">
                <field name="TEXT">,</field>
              </block>
            </value>
          </block>
        </category>
        <category id="catVariables" custom="VARIABLE" name="Variables"></category>
        <category id="catFunctions" custom="PROCEDURE" name="Functions"></category>
        <category id="code" name="Code">
              <block type="run_code"></block>
        </category>
        <category id="control" name="Control">
              <block type="wait"></block>
        </category>

        <sep></sep>

        <category id="duckietown" name="Duckietown" colour="0">
            <block type="move">
                <value name="FORWARD_SPEED">
                    <block type="math_number">
                        <field name="NUM">10</field>
                    </block>
                </value>
                <value name="TURN_DEGREES">
                    <block type="math_number">
                        <field name="NUM">5</field>
                    </block>
                </value>
                <value name="DURATION">
                    <block type="math_number">
                        <field name="NUM">2</field>
                    </block>
                </value>
            </block>
        </category>
    </xml>


    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Code execution logic -->
    <script src="./js/execution_logic.js"></script>

<!-- ACE editor configuration -->
<script type="text/javascript">
  document.getElementById('editor').style.fontSize='16px';
</script>

<script>
  var blocklyArea = document.getElementById('blocklyArea');
  var blocklyDiv = document.getElementById('blocklyDiv');
  var workspace = Blockly.inject(blocklyDiv,
    {toolbox: document.getElementById('toolbox'),
       scrollbars: true,
       rtl: false,
       zoom:
           {enabled: true,
            controls: true,
            wheel: true,
            maxScale: 4,
            minScale: .25,
            scaleSpeed: 1.1
           },
       grid:
           {spacing: 25,
            length: 3,
            colour: '#ccc',
            snap: false},
       trashcan: true});

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
  blocklyDiv.style.height = blocklyArea.offsetHeight  + 'px';
  // blocklyDiv.style.height = blocklyArea.offsetHeight - 51 - 42 + 171 + 'px';
  };
  window.addEventListener('resize', onresize, false);
  onresize();
</script>

<script type="text/javascript">

  // restore at the beginning.
  function restorelocal(){
    var xml_text = localStorage.getItem("blocks_cache");
    try {
      var xml = Blockly.Xml.textToDom(xml_text);
      Blockly.Xml.domToWorkspace(workspace, xml);
      automate_localstorage();
    }
    catch(err) {
        // alert(err);
        console.log(err);
        // console.log("This is generally because there's no tree information");
        automate_localstorage();
    }

    // Launch websockets
    ExecutionLogicModule.launch_websockets();

    // Recursively store everything after x miliseconds
    // automate_localstorage();
  }

  function automate_localstorage(){
      localstorage();
      setTimeout(automate_localstorage, 1000);
  }

  // Save stuff on local storage
  function localstorage() {
    var xml = Blockly.Xml.workspaceToDom(workspace);
    var xml_text = Blockly.Xml.domToText(xml);
    localStorage.setItem("blocks_cache", xml_text);
    var xml_text_stored = localStorage.getItem("blocks_cache");
  }

</script>
