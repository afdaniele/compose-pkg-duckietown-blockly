<?php
use \system\classes\Core;
use \system\classes\Configuration;
?>

<style type="text/css">
    body > #page_container{
        min-width: 100%;
    }
</style>

    <!-- Include Blocky -->
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/blockly_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/blocks_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/javascript_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/python_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/php_compressed.js"></script>
    <script type="text/javascript" src="<?php echo Configuration::$BASE_URL ?>/data/duckietown_blockly/blockly/msg/js/en.js"></script>

    <div class="btn-group btn-group-justified" role="group" style="margin-bottom:40px">
        <a role="button" class="btn btn-default" id="launch_button" name="launch_button" onclick="ExecutionLogicModule.launch_code(2);">
            <i class="fa fa-send" aria-hidden="true"></i>
            Launch
        </a>
        <a role="button" class="btn btn-default" id="end_button" name="end_button" href="#" onclick='ExecutionLogicModule.end_execution();' style="display:none;">
            <i class="fa fa-stop" aria-hidden="true"></i>
            Stop execution
        </a>
        <a role="button" class="btn btn-default" id="refresh_button" name="refresh_button" href="blockly.html" style="display:none;">
            <i class="fa fa-refresh" aria-hidden="true"></i>
            Refresh
        </a>
        <a role="button" class="btn btn-default" id="load_from_file_button" name="load_from_file_button" href="#" onclick='ExecutionLogicModule.load_from_file();'>
            <i class="fa fa-upload" aria-hidden="true"></i>
            Load from file
        </a>
        <a role="button" class="btn btn-default" id="save_to_file_button" name="save_to_file_button" href="#" onclick='ExecutionLogicModule.save_to_file();'>
            <i class="fa fa-download" aria-hidden="true"></i>
            Save to file
        </a>
        <a role="button" class="btn btn-default" id="clean_ws_button" name="clean_ws_button" href="#" onclick='ExecutionLogicModule.clean_ws();'>
            <i class="fa fa-eraser" aria-hidden="true"></i>
            Clean workspace
        </a>
    </div>

    <div id="wrapper">
        <div id="page-wrapper">
            <div id="blocklyArea" style="height:70vh;"></div>
        </div>
    </div>

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

    <!-- Code execution logic -->
    <script src="<?php echo Core::getJSscriptURL('execution_logic.js', 'duckietown_blockly') ?>"></script>

<script>
    var blocklyArea = document.getElementById('blocklyArea');
    var blocklyDiv = document.getElementById('blocklyDiv');
    var workspace = Blockly.inject( blocklyDiv,{
        toolbox: document.getElementById('toolbox'),
        scrollbars: true,
        rtl: false,
        zoom:{
            enabled: true,
            controls: true,
            wheel: true,
            maxScale: 4,
            minScale: .25,
            scaleSpeed: 1.1
        },
        grid:{
            spacing: 25,
            length: 3,
            colour: '#ccc',
            snap: false
        },
        trashcan: true
    });

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

  $(window).load(function() {
      restorelocal();
  });

</script>
