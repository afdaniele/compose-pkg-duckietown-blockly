function log(type, msg) {
  $("#log_area").append("[" + type.toUpperCase() + "] : " + msg + "\n");
} //log

function clear_log() {
  $("#log_area").html("");
} //clear_log

var ExecutionLogicModule = (function () {
  var CODE_STATUS = {
    RUNNING: "running",
    PAUSED: "paused",
    COMPLETED: "completed",
    NOT_CONNECTED: "not_connected",
  };

  var END_BUTTON_ID = "end_button";
  var REFRESH_BUTTON_ID = "refresh_button";
  var LAUNCH_BUTTON_ID = "launch_button";
  var current_status = CODE_STATUS.NOT_CONNECTED;
  var current_block = null;

  function update_launch_button() {
    var launch_button_span = document.getElementById(
      LAUNCH_BUTTON_ID + "_span"
    );
    var launch_button_icon = $("#" + LAUNCH_BUTTON_ID + "_icon");
    var launch_button = $("#" + LAUNCH_BUTTON_ID);
    // ---
    switch (current_status) {
      case CODE_STATUS.PAUSED:
        launch_button_span.innerHTML = "Resume";
        launch_button_icon.removeClass("fa-play fa-pause fa-ban fa-spinner");
        launch_button_icon.addClass("fa-play");
        launch_button.removeClass(
          "btn-success btn-default btn-warning disabled"
        );
        launch_button.addClass("btn-success");
        break;

      case CODE_STATUS.RUNNING:
        launch_button_span.innerHTML = "Pause";
        launch_button_icon.removeClass("fa-play fa-pause fa-ban fa-spinner");
        launch_button_icon.addClass("fa-pause");
        launch_button.removeClass(
          "btn-success btn-default btn-warning disabled"
        );
        launch_button.addClass("btn-warning");
        break;

      case CODE_STATUS.COMPLETED:
        launch_button_span.innerHTML = "Launch";
        launch_button_icon.removeClass("fa-play fa-pause fa-ban fa-spinner");
        launch_button_icon.addClass("fa-play");
        launch_button.removeClass(
          "btn-success btn-default btn-warning disabled"
        );
        launch_button.addClass("btn-success");
        break;

      case CODE_STATUS.NOT_CONNECTED:
        launch_button_span.innerHTML = "Server down";
        launch_button_icon.removeClass("fa-play fa-pause fa-ban fa-spinner");
        launch_button_icon.addClass("fa-ban disabled");
        launch_button.removeClass(
          "btn-success btn-default btn-warning disabled"
        );
        launch_button.addClass("btn-danger");
        break;

      default:
        console.log("Unknown current status: " + current_status);
        break;
    }
  }

  function update_workspace() {
    var workspace = window.blockly_ws;
    var load_from_file_button_selector = "a[id='load_from_file_button']";
    var save_to_file_button_selector = "a[id='save_to_file_button']";
    var end_button_selector = "a[id='end_button']";
    var clean_ws_button_selector = "a[id='clean_ws_button']";

    switch (current_status) {
      case CODE_STATUS.PAUSED:
      case CODE_STATUS.RUNNING:
        workspace.options.readOnly = true;
        if (null != workspace.toolbox_) {
          workspace.toolbox_.HtmlDiv.hidden = true;
        }
        $(load_from_file_button_selector).hide();
        $(save_to_file_button_selector).hide();
        $(end_button_selector).show();
        $(clean_ws_button_selector).hide();
        break;

      case CODE_STATUS.COMPLETED:
      case CODE_STATUS.NOT_CONNECTED:
        current_block = null;
        var blocks = workspace.getAllBlocks();
        for (var i = 0; i < blocks.length; i++) {
          blocks[i].setShadow(false);
        }
        workspace.options.readOnly = false;
        if (null != workspace.toolbox_) {
          workspace.toolbox_.HtmlDiv.hidden = false;
        }
        $(load_from_file_button_selector).show();
        $(save_to_file_button_selector).show();
        $(end_button_selector).hide();
        $(clean_ws_button_selector).show();
        break;

      default:
        console.log("Unknown current status: " + current_status);
        break;
    }
  }

  function set_current_block_id(block_id) {
    var workspace = window.blockly_ws;
    if (
      [CODE_STATUS.RUNNING, CODE_STATUS.PAUSED].indexOf(current_status) >= 0
    ) {
      var selected_block = workspace.getBlockById(block_id);
      if (null != selected_block) {
        if (null != current_block) {
          current_block.setShadow(false);
        }
        selected_block.setShadow(true);
        current_block = selected_block;
      } else {
        console.log("Not existing block id: " + block_id);
      }
    } else {
      console.log("Code is not running. Ignoring current block changed event.");
    }
  } //set_current_block_id

  function initApi(interpreter, scope) {
    // Add an API function for the alert() block.
    Blockly.JavaScript.addReservedWords("alert");
    var wrapper = function (text) {
      return alert(arguments.length ? text : "");
    };
    interpreter.setProperty(
      scope,
      "alert",
      interpreter.createNativeFunction(wrapper)
    );

    // Add an API function for the prompt() block.
    Blockly.JavaScript.addReservedWords("prompt");
    wrapper = function (text) {
      return prompt(text);
    };
    interpreter.setProperty(
      scope,
      "prompt",
      interpreter.createNativeFunction(wrapper)
    );

    // Add an API function for highlighting blocks.
    Blockly.JavaScript.addReservedWords("highlightBlock");
    var wrapper = function (id) {
      var workspace = window.blockly_ws;
      return workspace.highlightBlock(id);
    };
    interpreter.setProperty(
      scope,
      "highlightBlock",
      interpreter.createNativeFunction(wrapper)
    );

    // Ensure function name does not conflict with variable names.
    Blockly.JavaScript.addReservedWords("waitForSeconds");
    var wrapper = interpreter.createAsyncFunction(function (
      timeInSeconds,
      callback
    ) {
      // Delay the call to the callback.
      setTimeout(callback, timeInSeconds * 1000);
    });
    interpreter.setProperty(scope, "waitForSeconds", wrapper);

    // Add APIs function for ROSDB
    Blockly.JavaScript.addReservedWords("publish");
    var wrapper = function (resource_name, json_data) {
      var data = JSON.parse(json_data);
      return window.ROSDB.publish(resource_name, data);
    };
    interpreter.setProperty(
      scope,
      "publish",
      interpreter.createNativeFunction(wrapper)
    );

    Blockly.JavaScript.addReservedWords("pause");
    var wrapper = function (resource_name) {
      return window.ROSDB.pause(resource_name);
    };
    interpreter.setProperty(
      scope,
      "pause",
      interpreter.createNativeFunction(wrapper)
    );

    // add external APIs
    if (Blockly.JavaScript.hasOwnProperty("__apis")) {
      for (var api_name in Blockly.JavaScript["__apis"]) {
        api_fcn = Blockly.JavaScript["__apis"][api_name];
        // ---
        Blockly.JavaScript.addReservedWords(api_name);
        interpreter.setProperty(
          scope,
          api_name,
          interpreter.createNativeFunction(api_fcn)
        );
      }
    }
  } //initApi

  function set_status(value) {
    var workspace = window.blockly_ws;
    var statuses = [
      CODE_STATUS.COMPLETED,
      CODE_STATUS.RUNNING,
      CODE_STATUS.PAUSED,
      CODE_STATUS.NOT_CONNECTED,
    ];
    if (statuses.indexOf(value) < 0) {
      console.log("Unknown status: " + value);
      return;
    }
    current_status = value;
    update_launch_button();
    update_workspace();
    if (
      [CODE_STATUS.NOT_CONNECTED, CODE_STATUS.COMPLETED].indexOf(
        current_status
      ) >= 0
    ) {
      workspace.highlightBlock(null);
    }
  } //set_status

  function launch_code() {
    var workspace = window.blockly_ws;
    // configure blockly to highlight blocks
    Blockly.JavaScript.STATEMENT_PREFIX = "highlightBlock(%1);\n";
    // get code
    var code = Blockly.JavaScript.workspaceToCode(workspace);
    // reset state
    set_status(CODE_STATUS.COMPLETED);
    set_status(CODE_STATUS.RUNNING);
    // create JS interpreter
    var js_interpreter = new Interpreter(code, initApi);

    // begin execution
    var runner = function () {
      switch (current_status) {
        case CODE_STATUS.PAUSED:
          // program is paused, try again later
          setTimeout(runner, 10);
          break;

        case CODE_STATUS.RUNNING:
          // program is running, keep spinning the interpreter
          var hasMore = js_interpreter.run();
          if (hasMore) {
            // execution is currently blocked by some async call. Try again later.
            setTimeout(runner, 10);
          } else {
            // program is complete
            set_status(CODE_STATUS.COMPLETED);
          }
          break;

        default:
        case CODE_STATUS.COMPLETED:
        case CODE_STATUS.NOT_CONNECTED:
          // do nothing
          break;
      }
    };
    runner();
  } //launch_code

  function toggle_run() {
    switch (current_status) {
      case CODE_STATUS.PAUSED:
        // program is paused, resume
        set_status(CODE_STATUS.RUNNING);
        break;

      case CODE_STATUS.RUNNING:
        // program is running, pause
        set_status(CODE_STATUS.PAUSED);
        break;

      case CODE_STATUS.COMPLETED:
        // program is stopped, run
        launch_code();

      default:
      case CODE_STATUS.NOT_CONNECTED:
        // do nothing
        break;
    }
  }

  function is_connection_closed() {
    return CODE_STATUS.NOT_CONNECTED == current_status;
  } //is_connection_closed

  return {
    toggle_run: toggle_run,

    set_current_status: set_status,

    STATUS: CODE_STATUS,

    load_from_file: function () {
      var workspace = window.blockly_ws;
      var can_load_file = false;
      if (workspace.getAllBlocks().length > 0) {
        can_load_file = confirm(
          "Current workspace is not empty. Do you want to override it?"
        );
      } else {
        can_load_file = true;
      }

      if (true == can_load_file) {
        var input_field_name = "load_workspace_from_file_input";
        var file_input = document.getElementById(input_field_name);
        if (null == file_input) {
          file_input = document.createElement("input");
          file_input.type = "file";
          file_input.id = input_field_name;
          file_input.name = input_field_name;
          file_input.addEventListener(
            "change",
            function (evt) {
              var files = evt.target.files;
              if (files.length > 0) {
                var file = files[0];
                var reader = new FileReader();
                reader.onload = function () {
                  workspace.clear();
                  var xml = Blockly.Xml.textToDom(this.result);
                  console.log("Loading workspace from file.");
                  Blockly.Xml.domToWorkspace(workspace, xml);
                  workspace.getAllBlocks().forEach(function (block) {
                    block.setDeletable(true);
                    block.setMovable(true);
                    try {
                      block.setEditable(true);
                    } catch (e) {}
                  });
                };
                reader.readAsText(file);
                // This is done in order to allow open the same file several times
                document.body.removeChild(file_input);
              }
            },
            false
          );
          // hiding element from view
          file_input.style = "position: fixed; top: -100em";
          document.body.appendChild(file_input);
        }
        file_input.click();
      }
    },

    save_to_file: function () {
      var workspace = window.blockly_ws;
      var filename = "blockly_workspace.xml";
      var xml = Blockly.Xml.workspaceToDom(workspace);
      var xml_text = Blockly.Xml.domToText(xml);
      var blob = new Blob([xml_text], { type: "text/xml" });
      if (window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveBlob(blob, filename);
      } else {
        var elem = window.document.createElement("a");
        elem.href = window.URL.createObjectURL(blob);
        elem.download = filename;
        document.body.appendChild(elem);
        elem.click();
        document.body.removeChild(elem);
      }
      console.log("Workspace saved.");
    },

    end_execution: function () {
      // var end_button = document.getElementById(END_BUTTON_ID);
      // var launch_button = document.getElementById(LAUNCH_BUTTON_ID);
      // var refresh_button = document.getElementById(REFRESH_BUTTON_ID);
      // if (is_connection_closed()) {
      //   console.log("Connection not opened.");
      //   return;
      // }
      // launch_button.firstChild.data = "EXECUTION CANCELED";
      // launch_button.onclick = null;
      //
      // end_button.style.display = "none";
      // refresh_button.style.display = "block";
    },

    clean_ws: function () {
      var workspace = window.blockly_ws;
      workspace.clear();
      console.log("Workspace cleaned.");
    },
  };
})();
