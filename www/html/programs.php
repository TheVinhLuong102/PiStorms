<?php
/*
# Copyright (c) 2016 mindsensors.com
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License version 2 as
# published by the Free Software Foundation.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
#
#mindsensors.com invests time and resources providing this open source code, 
#please support mindsensors.com  by purchasing products from mindsensors.com!
#Learn more product option visit us @  http://www.mindsensors.com/
#
# History:
# Date         Author          Comments
# July 2016    Roman Bohuk     Initial Authoring 
*/

include "api/config.php";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ./login.php');
    exit();
}


?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="theme-color" content="#DD4B39">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PiStorms Web Interface</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/bootstrap.min.css">  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/font-awesome.min.css">
  <link rel="stylesheet" href="assets/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/pnotify.min.css">
  <link rel="stylesheet" href="assets/skin-red.min.css">
  <link rel="stylesheet" href="assets/slider.css">
    <script src="assets/blockly/blockly_compressed.js"></script>
  <script src="assets/blockly/blocks_compressed.js"></script>
  <script src="assets/blockly/python_compressed.js"></script>
  <script src="assets/blockly/msg/js/en.js"></script>
  <style>
    .btn-sq {
      width: 50px !important;
      height: 50px !important;
      font-size: 24px;
    }
  </style>
  <style type="text/css" media="screen">
    #blocklyeditor, #aceeditor { 
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 600px;
    }
    td {
        vertical-align: middle !important;
    }
    
    @media (max-width: 600px) {
        .blocklyeditor-row, .aceeditor-row {
            padding-right:30px;
        }
    }
</style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="./" class="logo">
      <span class="logo-mini"><b>PS</b></span>
      <span class="logo-lg"><b>PiStorms</b> Web</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
            <a href="./logout.php">Logout&nbsp;&nbsp;<i class="fa fa-sign-out"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <?php
    include_once("./components/nav.php");
  ?>

  <div class="content-wrapper">
  
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Your Programs</h3>
              <div class="box-tools pull-right">
                <button type="button" onclick="addfile('py');" class="btn btn-sm btn-flat btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;File</button>
                <button type="button" onclick="addfile('bl');" class="btn btn-sm btn-flat btn-info"><i class="fa fa-plus"></i>&nbsp;&nbsp;Blockly File</button>
                <button type="button" onclick="addfile('folder');" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Folder</button>
              </div>
            </div>
            <div class="box-body" id="programs_list" style="max-height:640px; overflow: auto;">
              <div class="text-center"><h4><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Fetching</h4></div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-8" id="editorDash">
          <div class="box box-danger" style="margin-bottom:0px !important;padding-bottom:0px !important;">
            <div class="box-body" id="edit_options">
                Please select a program on the left or create a new file
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-8 aceeditor-row" style="display:none">
          <div class="box">
            <div class="box-body" style="height:630px;">
               <div id="aceeditor" class="editor" style="height: 100%; width: 100%;"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-8 blocklyeditor-row" style="display:none">
          <div class="box">
            <div class="box-body" style="height:630px;">
               <div id="blocklyeditor" class="editor" style="height: 100%; width: 100%;"></div>
            </div>
          </div>
        </div>
        
      </div>

    </section>
  </div>

<?php include_once("./components/footer.php"); ?>

</div>


<div class="modal fade" tabindex="-1" id="filenameModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter Name</h4>
      </div>
      <div class="modal-body">
        Create an object in <code id="pathmodal">/home/pi/PiStorms/programs/</code>
        <br><br>
        <div id="modalinputgroup" class="form-group">
            <input class="form-control" minlen="2" type="text" id="filenameinput" placeholder="Enter file name here">
            <span id="modalinputhelp" class="help-block"></span>
        </div>
        <input class="form-control" type="hidden" id="filetypeinput" value="">
        The name must start with a 2-digit number to be displayed. Example: <code>01-Sample</code><br>Do not put a file extension
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="createobject()" class="btn btn-success">Create new <span id="objecttype"></span></button>
      </div>
    </div>
  </div>
</div>




<script src="assets/jquery.min.js"></script>
<script src="assets/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/app.min.js"></script>
<script type="text/javascript" src="assets/pnotify.min.js"></script>
<script type="text/javascript" src="assets/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-slider.min.js"></script>
<script type="text/javascript" src="assets/ps_blocks.js"></script>
<script type="text/javascript" src="assets/sha256.min.js"></script>

<?php include "components/blocks.php"; ?>

<script src="assets/ace/ace.js"></script>

<script>
PNotify.prototype.options.styling = "bootstrap3";
PNotify.prototype.options.delay = 3000;

function notify(tt,tx,tp) {
    new PNotify({
        title: tt,
        text: tx,
        type: tp,
        icon: false
    });
}

var currentdir = "";
var initdir = "";
var api = "http://<?=$_SERVER['SERVER_NAME']?>:3141/";

var tbl = '<table class="table table-striped">\
                <tr>\
                  <th class="text-center">Type</th>\
                  <th class="text-center">Name</th>\
                  <th style="width:80px" class="text-center">Actions</th>\
                </tr>';
var filerow = '<tr>\
                  <td class="text-center"><img src="assets/&&ft&&.png" alt="object" style="height:40px"></img></td>\
                  <td class="text-center"><b>&&fn&&</b></td>\
                  <td class="text-right"><button onclick="edit(\'&&fn&&\',\'&&fl&&\',\'&&id&&\')" style="width:32px;" class="btn btn-flat btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button style="width:32px;" onclick="deleteFile(&&id&&);" class="btn btn-flat btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>\
                </tr>';
var folderrow = '<tr>\
                  <td class="text-center"><img src="assets/&&ft&&.png" alt="object" style="height:40px"></img></td>\
                  <td class="text-center"><b>&&fn&&</b></td>\
                  <td class="text-right"><button style="width:32px;" onclick="traverse(\'&&fn&&\');" class="btn btn-flat btn-info btn-sm"><i class="fa fa-level-down" aria-hidden="true"></i></button><button style="width:32px;" onclick="deleteDirectory(&&id&&);" class="btn btn-flat btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>\
                </tr>';
var backrow = '<tr style="cursor:pointer" onclick="traverseup();">\
                  <td class="text-center"><img src="assets/updir.png" alt="object" style="height:34px;margin:3px;"></img></td>\
                  <td class="text-center"><b>Go back up</b></td>\
                </tr>';

var progs = [];

function fetchlist() {
    $.post(api + "getprograms", {path:currentdir}, function(data){
        data = $.parseJSON(data);
        progs = data;
        table = tbl;
        if (currentdir != initdir) {
            table += backrow;
        }
        for (var i = 0; i < data.length; i++) {
            table += (data[i][2] == "py" || data[i][2] == "bl" ? filerow : folderrow).replace("&&ft&&", data[i][2] == "py" ? "python" : data[i][2] == "bl" ? "blockly" : "folder").split("&&fn&&").join(data[i][0]).replace("&&fl&&",data[i][1]).split("&&id&&").join(i);
        }
        table += "</table>";
        $("#programs_list").html(table);
        $("#programs_list").addClass("no-padding");
    });
}

$.get(api + "getprogramsdir", function(data){
    currentdir = data;
    initdir = data;
    fetchlist();
});


var editor = ace.edit("aceeditor");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/python");
editor.setOptions({
   autoScrollEditorIntoView: true 
});
var workspace = null;

      
var edittype = "";

function blocklyedit(filename, location, id, content) {
    var stored = content.split('--START BLOCKS--\n')[1].split('\n--END BLOCKS--')[0].split("\n");
    var broken = stored.length != 2;
    var hash = CryptoJS.SHA256(stored[0]).toString();
    broken = broken || hash != stored[1];
    if (broken) {
        if (confirm("The blockly file is corrupted and the program can't restore the saved blocks. Do you want to edit the code in a text editor instead?")) {
            edittype = "py";
            aceedit(filename, location, id, content);
            return 0;
        }
    }
    var xml_text = Base64.decode(stored[0]);
    $(".aceeditor-row").hide();
    $(".blocklyeditor-row").show();
    if (workspace != null) {workspace.dispose();}
    workspace = Blockly.inject('blocklyeditor',
      {toolbox: document.getElementById('toolbox')});
    var xml = Blockly.Xml.textToDom(xml_text);
    Blockly.Xml.domToWorkspace(xml, workspace);
}

function aceedit(filename, location, id, content) {
    edittype = "py";
    if (workspace != null) {workspace.dispose();}
    $(".blocklyeditor-row").hide();
    editor.setValue(content);
    editor.gotoLine(1);
    $(".aceeditor-row").show();
    editor.session.setScrollTop(0)
}


function edit(filename, location, id) {
    $.post(api+"fetchscript", {path: location}, function(result){
        edittype = progs[id][2];
        $("#edit_options").html('<span style="font-size:20px;padding-bottom:0px;margin-bottom:-10px;display:block;">Edit <b>' + filename + '</b></span><br><button type="button" class="btn btn-success btn-flat btn-settings" onclick="save(\'' + location + '\')"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;&nbsp;Save</button><!--<button type="button" class="btn btn-danger btn-flat btn-settings"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancel</button>-->');
        if (progs[id][2] == "bl") {
            blocklyedit(filename, location, id, result);
            return 0;
        } else {
            aceedit(filename, location, id, result);
            return 1;
        }
    });
    var current = $(document).scrollTop();
    var need = $("#editorDash").offset().top - 20;
    if (current > need || Math.abs(current - need) > 70) {
        $('html,body').animate({
           scrollTop: need
        });
    }
}

function save(location) {
    var content = "";
    if (edittype == "bl") {
        var xml = Blockly.Xml.workspaceToDom(workspace);
        var blocks = Base64.encode(Blockly.Xml.domToText(xml));
        var code = Blockly.Python.workspaceToCode(workspace);
        content = '#!/usr/bin/env python\n\n# ATTENTION!\n# Please do not manually edit the contents of this file\n# Only use the web interface for editing\n# Otherwise, they file may no longer be editable using the web interface, or you changes may be lost\n\n"""\n--BLOCKLY FILE--\n--START BLOCKS--\n' + blocks + '\n' + CryptoJS.SHA256(blocks).toString() + '\n--END BLOCKS--\n"""\n\n\n' + code;
        
    } else if (edittype == "py") {
        content = editor.getValue();
    }
    $.post(api+"savescript", {path: location, contents:content}, function(result){
        notify("Saved","File successfully saved","success");
    });
    fetchlist();
}

function deleteFile(id) {
    var location = progs[id][1];
    if (progs[id][2] == "folder") {
        notify("Error","An error has occured","error");
        return 0;
    }
    if (confirm("Are you sure you want to delete this file?")) {
        $.post(api+"removefile", {path: location}, function(result){
            notify("Deleted","File successfully deleted","success");
        });
        fetchlist();
    }
}

function deleteDirectory(id) {
    var location = progs[id][1];
    if (progs[id][2] != "folder") {
        notify("Error","An error has occured","error");
        return 0;
    }
    if (confirm("Are you sure you want to delete this folder?")) {
        $.post(api+"removedir", {path: location}, function(result){
            notify("Deleted","Folder successfully deleted","success");
        });
        fetchlist();
    }
}

function traverse(path) {
    currentdir += path;
    fetchlist();
}

function traverseup() {
    currentdir = currentdir.split("/").slice(0,currentdir.split("/").length-1).join("/") + "/";
    fetchlist();
}

$("#srwb").click(function(){$.get(api+"startrecording/withBg", function(data){});notify("Success","Started taking frames with background","success");});
$("#stpr").click(function(){$.get(api+"stoprecording", function(data){});notify("Success","Stopped recording","success");});
$("#chkr").click(function(){$.get(api+"readrecording", function(data){notify("Result",data,"success");});});
$("#clar").click(function(){
    if (confirm("Are you sure you want to permanently remove all screenshots?")) {
        $.get(api+"clearimages", function(data){notify("Result","Images cleared","success");});
    }
});


function addfile(type) {
    $("#filenameinput").val("")
    $('#objecttype').html(type == "folder" ? "Folder" : type == "bl" ? "Drag-and-drop program" : "Python program")
    if (type == "folder") {$('#filenameinput').attr("placeholder", "Enter folder name here.");}
    $("#filetypeinput").val(type);
    var pathtoadd = currentdir.charAt(currentdir.length-1) != "/" ? currentdir + "/" : currentdir;
    $("#pathmodal").html(pathtoadd);
    $('#filenameModal').modal('show');
}

function checkname(type) {
    $("#filenameinput").val("")
    $('#objecttype').html(type == "folder" ? "Folder" : type == "bl" ? "Drag-and-drop program" : "Python program")
    if (type == "folder") {$('#filenameinput').attr("placeholder", "Enter folder name here.");}
    $("#filetypeinput").val(type);
    $('#filenameModal').modal('show');
}

// https://scotch.io/tutorials/how-to-encode-and-decode-strings-with-base64-in-javascript
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

// http://stackoverflow.com/a/10834843/3600428
function isInteger(str) {
    var n = ~~Number(str);
    return String(n) === str && n >= 0;
}

function createobject() {
    var typein = $("#filetypeinput").val();
    var namein = $("#filenameinput").val();
    
    var grievances = [];
    for (var i = 0; i < progs.length; i++) {
        if (progs[i][0].toLowerCase() == namein.toLowerCase() || progs[i][0].toLowerCase() == namein.toLowerCase()+".py") {
            grievances.push("An object with such name already exists!");
            break;
        }
    }
    if (namein.length <= 3) {
        grievances.push("Filename is too short!");
    }
    if (!isInteger(namein.substring(0,2).replace("0","1"))) {
        grievances.push("The filename does not start with a 2-digit number!");
    }
    var legal = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var ext = "()-_+ ";
    for (var i = 0; i < namein.length; i++) {
        if ((legal + ext).indexOf(namein.charAt(i)) < 0) {
            grievances.push("The filename cannot contain special characters except <code>() -_</code>!");
            break;
        }
    }
    if (legal.indexOf(namein.charAt(namein.length - 1)) < 0) {
        grievances.push("The filename cannot end with a special character!");
    }
    
    if (grievances.length <= 0) {
        $.post(api+"addobject", {path: currentdir, type:typein, filename:namein}, function(result){
            notify("Success","Object successfully created","success");
            fetchlist();
            grievances = [];
            $('#filenameModal').modal('hide');
            $('#filenameinput').val("");
            $('#modalinputhelp').html('');
            $('#modalinputgroup').removeClass('has-error');
        });
    } else {
        $('#modalinputgroup').addClass('has-error');
        $('#modalinputhelp').html(grievances.join('<br>'));
    }
}

$("#filenameinput").keyup(function (e) {
    if (e.keyCode == 13) {
        createobject();
    }
});
</script>

</body>
</html>