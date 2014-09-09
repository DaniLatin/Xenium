<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php'); 
if (!isset($_SESSION['admin_user']))
{
    header('Location: /admin/login/');
    die();
}
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) && !isset($_SERVER['Uploading']) )
{
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Danijel" />

	<title>Xenium CMS</title>
    
    <link href="/admin/theme/css/bootstrap.css" rel="stylesheet">
    <link href="/admin/theme/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/admin/theme/css/font-awesome.min.css" rel="stylesheet">
    <link href="/admin/theme/css/style.css?v=1" rel="stylesheet">
    <link href="/admin/theme/css/fam-icons.css" rel="stylesheet">
    <link href="/admin/theme/css/daterangepicker.css" rel="stylesheet">
    <link href="/admin/theme/js/select2/select2.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/admin/theme/css/jquery.fonticonpicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin/theme/css/jquery.fonticonpicker.grey.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/theme/css/jquery.fonticonpicker.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/theme/css/spectrum.css" />
    
    <link rel="stylesheet" type="text/css" href="/admin/theme/font/fontello/css/fontello.css" />
    <link rel="stylesheet" type="text/css" href="/admin/theme/font/icomoon/style.css" />
    
    <!--<script src="/admin/theme/js/aloha/lib/require.js"></script>-->
    <!--
    <script src="/admin/theme/js/aloha/lib/aloha-full.js" data-aloha-plugins="common/ui,common/format,common/list,common/link,common/align,common/block,common/paste,common/table,common/undo"></script>
    -->
    <script>
    
    function Something(){
        console.log('neki');
    }
    
    (function(window, undefined) {
        if (window.Aloha === undefined || window.Aloha === null) {
            window.Aloha = {};		
        }
        
        window.that = {'maxWidth': '50px', 'maxHeight': '50px'};
        
        //that.max-width = '50px';
        //that.max-height = '50px';
        
        window.Aloha.settings = { sidebar: { disabled: true } };
        
        window.Aloha.settings.contentHandler = {
            insertHtml: [ 'word', 'generic', 'oembed', 'sanitize' ],
            //insertHtml: [ 'word', 'oembed', 'sanitize' ],
            initEditable: [ 'sanitize' ],
            getContents: [ 'blockelement', 'sanitize', 'basic' ],
            sanitize: 'relaxed', // relaxed, restricted, basic,
            /*sanitize: {
                '.html_edit_simple': { elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a' ] },
                '.html_edit_advanced': { elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'sub', 'sup', 'ul', 'ol', 'li', 'div', 'img', 'video', 'audio', 'span' ] }
            },*/
            allows: {
                elements: ['strong', 'em', 'i', 'b', 'blockquote', 'br', 'cite', 'code', 'dd', 'div', 'dl', 'dt', 'em', 'i', 'li', 'ol', 'p', 'pre', 'q', 'small', 'strike', 'sub', 'sup', 'u', 'ul', 'h1', 'h2', 'h3', 'h4', 'h5', 'img', 'video', 'audio', 'span'],
                attributes: {
                    'div'         : ['class', 'style'],
                    'span'        : ['class', 'style'],
                    'a'           : ['id', 'class', 'style', 'href']
                }
            },
            handler: {
                generic: {
                    transformFormattings: false
                },
                sanitize: {
                    '.html_edit_simple': { 
                        elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a' ], 
                        attributes: {'a': ['id', 'class', 'style', 'href']},
                        //protocols: {'a': {'href': ['ftp', 'http', 'https', 'mailto', '__relative__']}, {'class': ['']}, {'id': ['']}}
                        protocols: {'a': {'href': ['ftp', 'http', 'https', 'mailto', '__relative__']}}
                    },
                    //'.aloha-input': { elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a' ] },
                    '.html_edit_advanced': { 
                        elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'sub', 'sup', 'ul', 'ol', 'li', 'div', 'img', 'video', 'audio', 'span' ], 
                        attributes: {
                            'a': ['id', 'class', 'style', 'href'],
                            'div': ['id', 'class', 'style', 'href'],
                            'span': ['id', 'class', 'style', 'href'],
                            'img': ['id', 'class', 'style', 'href'],
                            'video': ['id', 'class', 'style', 'href'],
                            'audio': ['id', 'class', 'style', 'href'],
                        },
                        protocols: {
                            'a': {'href': ['ftp', 'http', 'https', 'mailto', '__relative__']}
                            }
                    }
                }
            }
        }
        
        //Aloha.settings = {
            /*
			logLevels: {'error': true, 'warn': true, 'info': true, 'debug': true},
			errorhandling : false,
			ribbon: false,	
			"i18n": {
				// let the system detect the users language
				//"acceptLanguage": '<?=$_SERVER['HTTP_ACCEPT_LANGUAGE']?>'
				"acceptLanguage": 'fr,de-de,de;q=0.8,it;q=0.6,en-us;q=0.7,en;q=0.2'
			},*/
			/*"plugins": {
				"dragndropfiles": {
					config : { 'drop' : {'max_file_size': '200000',
			   										 'upload': {//'uploader_class':GENTICS.Aloha.Uploader, //this is the default
			   										 			    'uploader_class':Something,
                                                                    'config': {
				   										 			'url': '/content/neki/tko/',
                                                                    'callback': function(resp) { console.log(resp);},
				   										 			'extra_headers':{'Accept':'application/json', 'Uploading':'true'},
				   										 			'additional_params': {"location":"/neki/tko/tam/"},
							   										'www_encoded': false }}}}
				},
			 	"table": {
					config: ['table']
				},
				"image": {
   					config : { 'img': { 'max_width': '50px',
							'max_height': '50px' }},
				  	editables : {
						'#title'	: {},
				  	}
				}
		  	}
		};*/
        
        /*window.Aloha.settings.contentHandler.sanitize = {
            // elements allowed in the content
            elements: [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'sub', 'sup', 'ul', 'ol', 'li', 'div', 'img', 'video', 'audio', 'span' ],
            // attributes allowed for specific elements
            attributes: {
                    'div'         : ['class', 'style'],
                    'span'        : ['class', 'style'],
                    'a'           : ['class', 'style']
                },
            // protocols allowed for certain attributes of elements
            protocols: {
                'a' : {'href': ['ftp', 'http', 'https', 'mailto', '__relative__']},
                'blockquote' : {'cite': ['http', 'https', '__relative__']},
                'q' : {'cite': ['http', 'https', '__relative__']}
            }
        }*/
        
    })(window);
    /*
    Aloha.settings = {
            contentHandler: {
                sanitize: {
                    '.html_edit_simple': { elements: [ 'b', 'strong', 'i', 'em', 'strike', 'a' ] },
                    '.html_edit_advanced': { elements: [ 'b', 'strong', 'i', 'em', 'strike', 'a' ] }
                }
            }
        };*/
    //Aloha.settings.contentHandler.handler = {};
    //Aloha.settings.contentHandler.handler.sanitize = {};
    //Aloha.settings.contentHandler.handler.sanitize['.html_edit_simple'] = { elements : [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a' ] };
    //Aloha.settings.contentHandler.handler.sanitize['.html_edit_advanced'] = { elements : [ 'b', 'i', 'strong', 'em', 'strike', 'u', 'a', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'sub', 'sup', 'ul', 'ol', 'li', 'div', 'img', 'video', 'audio' ] };
    </script>
    <!--<script src="/admin/theme/js/aloha/aloha-config.js" language="javascript"></script>-->
    <!--<script src="/admin/theme/js/aloha/lib/aloha-full.js" data-aloha-plugins="common/ui,common/format,common/list,common/link,common/align,common/undo,common/contenthandler,common/block,common/paste,common/commands,extra/formatlesspaste"></script>-->
    <script src="/admin/theme/js/aloha.newest/lib/aloha-full.min.js" data-aloha-plugins="common/ui,common/format,common/list,common/link,common/align,common/undo,common/contenthandler,common/block,common/paste,common/commands"></script>
</head>

<body>

<div id="main-toolbar" class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <!--
          <a class="brand" href="#">
            <img id="logo" src="/admin/theme/img/xenium-logo-dark-transparent.png"/>
          </a>
          
          <div class="nav-collapse collapse">
            <img id="logo" src="/admin/theme/img/xenium-logo-dark-transparent.png"/>
          </div>-->
          <div class="nav-collapse collapse">
            <div class="btn-group pull-right">
                <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> Logged in as <strong><?php echo $_SESSION['admin_user'][0]['username']; ?></strong></a>
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">&nbsp;<i class="icon-chevron-down icon-white"></i>&nbsp;</a>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="icon-pencil"></i> Edit</a></li>
                    <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
                    <li><a href="#"><i class="icon-ban-circle"></i> Ban</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-star"></i> Make admin</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="logoff_user(); return false;"><i class="icon-off"></i> Logoff</a></li>
                </ul>
            </div>
            <ul id="main-menu" class="nav">
              <li class="active"><a href="/admin/interface/" class="follow"><i class="icon-home"></i> Dashboard</a></li>
              <li><a href="/admin/interface/contents/" class="follow"><i class="icon-pencil"></i> Contents</a></li>
              <li><a href="/admin/interface/applications/" class="follow"><i class="icon-th"></i> Applications</a></li>
              <li><a href="/admin/interface/users/" class="follow"><i class="icon-group"></i> Users</a></li>
              <li><a href="/admin/interface/multimedia/" class="follow"><i class="icon-picture"></i> Multimedia</a></li>
              <!--<li><a href="/admin/interface/mailing/" class="follow"><i class="icon-envelope"></i> Mailing</a></li>-->
              <li><a href="/admin/interface/settings/" class="follow"><i class="icon-wrench"></i> Settings</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid height-100">
      <div class="row-fluid height-100">
        <div class="span3">
            
            <div class="well well-white">
            <img id="logo-well" src="/admin/theme/img/xenium-logo-dark-transparent-glow.png"/>
            </div>
            
          <div class="well sidebar-nav left-navigation">
            
          </div><!--/.well -->
        </div><!--/span-->
        <div id="main-container" class="span9 height-100">
        <div id="main-container-data" class="container-fluid stretchedToMargin">
          
        </div><!--/span-->
        
        </div><!--/container-->
        
      </div><!--/row-->

      

      <div class="navbar navbar-fixed-bottom footer-class">
      <div class="navbar-inner">
        <div class="container-fluid">
        &copy; Xenium CMS v. 1.0.0
        </div>
        </div>
      </div>

    </div><!--/.fluid-container-->
    
    <div id="action-board"></div>
    <div id="text-editor-modals"></div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/admin/theme/js/jquery.min.js"></script>
    <script src="/admin/theme/js/jquery-ui.min.js"></script>
    <script src="/admin/theme/js/bootstrap.min.js"></script>
    <script src="/admin/theme/js/jquery.nicescroll.min.js"></script>
    <script src="/admin/theme/js/jquery.address.min.js"></script>
    <script src="/admin/theme/js/bootbox.min.js"></script>
    <script src="/admin/theme/js/jAPI.js"></script>
    <script src="/admin/system/admin.actions/admin.actions.php"></script>
    <script src="/admin/theme/js/compressed_form.js"></script>
    <script src="/admin/theme/js/diff_match_patch.js"></script>
    <script type="text/javascript" src="/admin/theme/js/plupload/js/plupload.full.js"></script>
    <script type="text/javascript" src="/admin/theme/js/jsTree/_lib/jquery.cookie.js"></script>
	<!--<script type="text/javascript" src="/admin/theme/js/jsTree/_lib/jquery.hotkeys.js"></script>-->
    <script type="text/javascript" src="/admin/theme/js/jsTree/jquery.jstree.js"></script>
    <script type="text/javascript" src="/admin/theme/js/select2/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="/admin/theme/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="/admin/theme/js/jquery.addresspicker.js"></script>
    <script type="text/javascript" src="/admin/theme/js/moment.min.js"></script>
    <script type="text/javascript" src="/admin/theme/js/daterangepicker.js"></script>
    <script type="text/javascript" src="/admin/theme/js/amcharts/amcharts.js"></script>
    <script type="text/javascript" src="/admin/theme/js/jquery.fonticonpicker.min.js"></script>
    <script type="text/javascript" src="/admin/theme/js/spectrum.js"></script>
    
    <script src="http://ajaxorg.github.com/ace/build/src/ace.js"></script>
    
    <script src="http://newsharejs-dani.herokuapp.com/channel/bcsocket.js"></script>
    <script src="http://newsharejs-dani.herokuapp.com/share/share.js"></script>
    <script src="http://newsharejs-dani.herokuapp.com/share/ace.js"></script>
    <script src="http://newsharejs-dani.herokuapp.com/share/textarea.js"></script>
    
    <script type="text/javascript">
    $("#main-container-data").niceScroll({cursorcolor:"#08C",spacebarenabled:false,enablekeyboard:false,enablemousewheel:true,railoffset:{top:0,left:14}});
    
    function get_nicescroll(){
        $(".aloha-textarea, textarea").each(function(){
            var get_id = $(this).attr('id');
            //$("#main-container-data").niceScroll("#" + get_id, {cursorcolor:"#08C",spacebarenabled:false,enablekeyboard:false,enablemousewheel:true,railoffset:{top:0,left:14}});
        });
        //$(".aloha-textarea").niceScroll({cursorcolor:"#08C",spacebarenabled:false,enablekeyboard:false,enablemousewheel:true,railoffset:{top:0,left:14}});
    }
    
    function addslashes( str ) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }
    
    function bind_aloha_block_functions(){
        $(".aloha-block").on("click",function(e){
            if(e.ctrlKey) {
                //Ctrl+Click
                var id = $(this).attr('id');
                var link = $("#" + id + " .link").text();
                if (link){
                    $("#action-board").html('<a id="redirect" class="follow" style="display: none;" href="' + link +'"></a><script type="text/javascript">$("#redirect").trigger("click");<\/script>');
                }
            }
        });
    }
    
    function toggle_advanced_link_settings(){
        $("#button_advanced_link_edit").toggleClass('btn-primary');
        $("#advanced_link_edit").toggleClass('invisible');
    }
    
    function cancel_link_edit(){
        $("#link-popover").removeClass('in');
        $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
        jQuery('#' + window.alohaEditable).focus();
        
        var currAlohaEditable = window.alohaEditable;
        var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
        
        //patch_launch(currAlohaEditable, currAlohaEditableBack);
        matchit(currAlohaEditableBack);
    }
    
    function remove_link(){
        $("#link-popover").removeClass('in');
        
        $('#' + window.alohaEditable + ' a[link-editing="true"]').contents().unwrap();
        jQuery('#' + window.alohaEditable).focus();
        
        var currAlohaEditable = window.alohaEditable;
        var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
        
        //patch_launch(currAlohaEditable, currAlohaEditableBack);
        matchit(currAlohaEditableBack);
    }
    
    function save_link()
    {
        var new_url = $("#link_edit_url").val();
        var new_target = $("#link_edit_target").val();
        var new_id = $("#link_edit_id").val();
        var new_class = $("#link_edit_class").val();
        
        var combination = '';
        
        if (new_url != ''){
            //combination = ' href="' + new_url + '"';
            $('#' + window.alohaEditable + ' a[link-editing="true"]').attr('href', new_url);
        } else {
            //combination = ' href="#"';
            $('#' + window.alohaEditable + ' a[link-editing="true"]').attr('href', '#');
        }
        
        if (new_target != ''){
            //combination = combination + ' target="' + new_target + '"';
            $('#' + window.alohaEditable + ' a[link-editing="true"]').attr('target', new_target);
        } else {
            $('#' + window.alohaEditable + ' a[link-editing="true"]').removeAttr('target');
        }
        
        if (new_id != ''){
            //combination = combination + ' id="' + new_id + '"';
            $('#' + window.alohaEditable + ' a[link-editing="true"]').attr('id', new_id);
        } else {
            $('#' + window.alohaEditable + ' a[link-editing="true"]').removeAttr('id');
        }
        
        if (new_class != ''){
            //combination = combination + ' class="' + new_class + '"';
            $('#' + window.alohaEditable + ' a[link-editing="true"]').attr('class', new_class);
        } else {
            $('#' + window.alohaEditable + ' a[link-editing="true"]').removeAttr('class');
        }
        
        $("#link-popover").removeClass('in');
        //$('#' + window.alohaEditable + ' a[link-editing="true"]').select();
        
        var the_link = $('#' + window.alohaEditable + ' a[link-editing="true"]');
        
        var selection = window.getSelection();
        if(selection.rangeCount > 0) selection.removeAllRanges();
        
        for(var i = 0; i < the_link.length; i++) {
            var range = document.createRange();
            range.selectNode(the_link[i]);
            selection.addRange(range);
        }
        
        $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
        jQuery('#' + window.alohaEditable).focus();
        
        var currAlohaEditable = window.alohaEditable;
        var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
        
        //patch_launch(currAlohaEditable, currAlohaEditableBack);
        matchit(currAlohaEditableBack);
    }
    
    function getSelectedText(id) {
        var len =$("#" + id).text().length;
        var start = $("#" + id)[0].selectionStart;
        var end = $("#" + id)[0].selectionEnd;
        var sel = $("#" + id).text().substring(start, end);
        return sel;
    }
    
    function getSelectionHtml() {
        var html = "";
        if (typeof window.getSelection != "undefined") {
            var sel = window.getSelection();
            if (sel.rangeCount) {
                var container = document.createElement("div");
                for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                    container.appendChild(sel.getRangeAt(i).cloneContents());
                }
                html = container.innerHTML;
            }
        } else if (typeof document.selection != "undefined") {
            if (document.selection.type == "Text") {
                html = document.selection.createRange().htmlText;
            }
        }
        return html;
    }
    
    function set_link_settings(){
        jQuery('#' + window.alohaEditable + ' a').off();
        jQuery('#' + window.alohaEditable + ' a').click( function(){
            
            jQuery("#link-popover").removeClass('in');
            
            var parent_position = jQuery('#' + window.alohaEditable).position(); //console.log(parent_position);
            var position = jQuery(this).position(); //console.log(position);
            var height = jQuery(this).height();
            var width = jQuery(this).width();
            var popover_width = jQuery("#link-popover").width();
            var container_width = jQuery(".custom-container-content").outerWidth();
            
            //var new_left = position.left - popover_width / 2 + width / 2;
            var new_left = parent_position.left + position.left - popover_width / 2 + width / 2;
            
            var max_left = new_left + popover_width;
            
            var already_editing = $(this).attr('link-editing');
            
            if (already_editing != 'true'){
                $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
                $(this).attr('link-editing', 'true');
                
                if (new_left < 0){
                    //jQuery("#link-popover").css('left', '0px').css('top', position.top + height + 'px');
                    jQuery("#link-popover").css('left', '0px').css('top', parent_position.top + position.top + height + 'px');
                    jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + new_left + 'px)');
                }
                if (new_left >= 0 && max_left <= container_width){
                    //jQuery("#link-popover").css('left', new_left + 'px').css('top', position.top + height + 'px');
                    jQuery("#link-popover").css('left', new_left + 'px').css('top', parent_position.top + position.top + height + 'px');
                    jQuery("#link-popover .arrow").css('left', '50%');
                }
                if (new_left >= 0 && max_left > container_width){
                    var maximal = container_width - popover_width;
                    var arrow_left = max_left - container_width;
                    //jQuery("#link-popover").css('left', maximal + 'px').css('top', position.top + height + 'px');
                    jQuery("#link-popover").css('left', maximal + 'px').css('top', parent_position.top + position.top + height + 'px');
                    jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + arrow_left + 'px)');
                }
                
                jQuery("#link_edit_url").val($(this).attr('href'));
                jQuery("#link_edit_target").val($(this).attr('target'));
                jQuery("#link_edit_id").val($(this).attr('id'));
                jQuery("#link_edit_class").val($(this).attr('class'));
                
                
            }
            
            jQuery("#link-popover").addClass('in');
        });
    }
    
    function add_link(){
        /*var start = '<a href="#" link-editing="true">';
        var end = '</a>';
        var tmpVal = getSelectionHtml();
        alert($("#" + window.alohaEditable).selection('html'));*/
        //console.log(tmpVal);
        //alert(tmpVal);
        //$("#" + window.alohaEditable).html($("#" + window.alohaEditable).html().replace(tmpVal, start + tmpVal + end));
    }
    
    function create(htmlStr) {
        var frag = document.createDocumentFragment(),
            temp = document.createElement('div');
        temp.innerHTML = htmlStr;
        while (temp.firstChild) {
            frag.appendChild(temp.firstChild);
        }
        return frag;
    }
    
    function insertHtmlAtCursor(e) {
        console.log(e);
        var range;
        var textNode;
        var offset;
        //alert(e.originalEvent.clientX + ' - ' + e.originalEvent.clientY);
        // standard
        
        
        
        if (document.caretPositionFromPoint) {
            range = document.caretPositionFromPoint(e.originalEvent.clientX, e.originalEvent.clientY);
            textNode = range.offsetNode;
            offset = range.offset;
            
        // WebKit
        } else if (document.caretRangeFromPoint) {
            range = document.caretRangeFromPoint(e.originalEvent.clientX, e.originalEvent.clientY);
            textNode = range.startContainer;
            offset = range.startOffset;
        }
        console.log(range);
        
        window.random_id = Math.floor(Math.random()*100001);
        var div = create('<div class="text-image" id="element-' + window.random_id + '" contenteditable="false">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>');
        
        console.log(div);
        
        if (e.target.localName == 'p' || e.target.localName == 'h1' || e.target.localName == 'h2' || e.target.localName == 'h3' || e.target.localName == 'h4' || e.target.localName == 'img' || e.target.localName == 'video'){
            e.target.parentNode.insertBefore(div, e.target);
        }
        
        if (e.target.localName == 'div'){
            if (e.target.isContentEditable == true){
                //e.target.innerHTML = e.target.innerHTML + div;
                e.target.appendChild(div);
            } else {
                e.target.parentNode.insertBefore(div, e.target);
            }
        }
        
        if (e.target.localName == 'span' || e.target.localName == 'b' || e.target.localName == 'i' || e.target.localName == 'u' || e.target.localName == 'a'){
            e.currentTarget.insertBefore(div, e.target.parentNode);
        }
        
        // only split TEXT_NODEs
        /*if (textNode.nodeType == 3) {
            window.random_id = Math.floor(Math.random()*100001);
            //var replacement = textNode.splitText(offset);
            var replacement = textNode.splitText(textNode);
            //var br = document.createElement('br');
            var div = create('<div class="slideshow-image" id="element-' + window.random_id + '">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>');
            //textNode.parentNode.insertBefore(div, replacement);
            textNode.parentElement.insertBefore(div, textNode);
        }*/
    }
    
    var init = true, 
    state = window.history.pushState !== undefined;
    
    var root_admin = '/admin/interface';    
    $.address.state('/admin/interface').init(function() {
        jQuery('a.follow').address();
    }).change(function(event) {
        
        $("#text-editor-modals").html('');
        
        Aloha.unbind('aloha-selection-changed aloha-command-executed');
        $(".aloha-block").off();
        
        $(".edit-tools-toolbar").hide();
        
        Aloha.ready( function() {
            var $ = jQuery = Aloha.jQuery;
        
            var link_string = event.path;
            path_split = link_string.split('/');
            
            var main_content = path_split[1];
            var main_function = path_split[2];
            var main_parameters = path_split[3];
            if (!main_content){
                main_content = 'dashboard';
            }
            if (!main_function){
                main_function = main_content + '_default';
            }
            if (main_parameters){
                split_parameters = main_parameters.split('_');
                for (i = 0, l = split_parameters.length; i < l; i += 1) {
                    split_parameters[i] = '"' + split_parameters[i] + '"';
                }
                send_parameters = split_parameters.join(', ');
            } else {
                send_parameters = '';
            }
        
            var left_menu_attr = $('.left-navigation').attr('loaded');
            if (left_menu_attr != main_content){
                $('.left-navigation').html(AdminAction.load_left_menu(main_content)).slideDown('slow');
                $('.left-navigation').attr('loaded', main_content);
            }
        
            var main_container_attr = $('#main-container-data').attr('loaded'); //console.log('loaded: ' + main_container_attr);
            var check_main_container_attr = main_function + '_' + main_parameters; //console.log('check: ' + check_main_container_attr);
            if (main_container_attr != check_main_container_attr){
                var combine_function = 'AdminAction.' + main_function + '(' + send_parameters + ')';
                $('#main-container-data').html(eval(combine_function));
                $('#main-container-data').attr('loaded', check_main_container_attr);
                $(".custom-container-content").append('<div id="link-popover" class="popover fade bottom" style="top: 69px; left: 372px; display: block;"><div class="arrow"></div><h3 class="popover-title">Link editing&nbsp;&nbsp;&nbsp;&nbsp;<button id="button_advanced_link_edit" class="btn btn-mini" onclick="toggle_advanced_link_settings()">Advanced</button><button onclick="cancel_link_edit()" class="btn btn-mini btn-danger" style="float: right;"><i class="icon-remove" style="line-height: 16px;"></i></button></h3><div class="popover-content"><label>Url:</label><input id="link_edit_url" type="text" class="input-xlarge" style="width: 320px;" /><div id="advanced_link_edit" class="invisible"><hr style="margin: 8px 0px;"><div>Target: <select id="link_edit_target"><option id="none" value="">none</option><option id="same_window" value="_self">same window</option><option id="new_window" value="_blank">new window</option></select></div><div style="float: left;"><label>Id:</label><input id="link_edit_id" type="text" class="input-medium" /></div><div style="float: right;"><label>Class:</label><input id="link_edit_class" type="text" class="input-medium" /></div><div class="clear"></div></div></div><hr style="margin: 8px 0px;"><button onclick="remove_link()" class="btn" style="float: left;"><i class="icon-unlink"></i> Remove link</button><button onclick="save_link()" class="btn btn-primary" style="float: right;"><i class="icon-ok"></i> Save changes</button></div>');
            
                //console.log("main: " + main_function);
                window.main_func = main_function;
            }
                
            // Selects the proper navigation link
            // MAIN MENU
            $('#main-menu li a').each(function() {
                if ($(this).attr('href') == (root_admin + '/' + path_split[1] + '/') || ($(this).attr('href') == (root_admin + '/') && path_split[1] == '')) {
                    $(this).parent().addClass('active').focus();
                } else {
                    $(this).parent().removeClass('active');
                }
            });
        
            // LEFT NAVIGATION     
            $('.left-navigation li a').each(function() {
                if ($(this).attr('href') == (root_admin + event.path)) {
                    $(this).parent().addClass('active').focus();
                } else {
                    $(this).parent().removeClass('active');
                }
            });
        
            // RIGHT TOOLBAR
            $("#main-container-data a[class!='btn translation-btn follow']").each(function() {
                if ($(this).attr('href') == (root_admin + event.path)) {
                    $(this).addClass('active').focus();
                } else {
                    $(this).removeClass('active');
                }
            });
            
            
        
            Aloha.jQuery('.html_edit_simple').aloha();
            Aloha.jQuery('.html_edit_advanced').aloha();
            
            $(".aloha-editable").each(function() {
                var original_id = $(this).attr("id").replace("-aloha", "");
                var classes = $("#" + original_id).attr("class").replace("input-xxlarge", "").replace("span12", "").replace(" ", "");
                $(this).addClass(classes);
            });
            
            update_editables();
            
            
            
            jQuery('.alohablock').alohaBlock();
            //jQuery('.aloha-editable div').alohaBlock();
            
            get_nicescroll();
            
            bind_aloha_block_functions();
            
            /*
            require(['block/blockmanager'], function(BlockManager) {
            BlockManager.bind('block-selection-change', function (blocks) {
                // blocks is an array now, where the first element is the selected block
                // and the other elements are the ancestor blocs.
                // If the array is empty, no block has been selected.
                console.log('block changed');
            });
            });
            */
            
            //$('.aloha-editable a').popover({placement:'left',html:'neki tko'});
            
            
            
            // preventing default functionality - keypoard shortcuts
            //function test(e)
            /*$(".aloha-editable").keydown(function(e)
            {
            	// Ctrl-variations
            	if (e.ctrlKey)
            	{
            		switch(e.keyCode)
            		{
            			case 66: // b
            			//case 70: // f
            			case 73: // i
            			//case 75: // k
            			case 85: // u
            				disableDefault(e);
                            //console.log('default disabled');
            			break;
            		}
            	}
            	
            	// F5
            	else 
            	{
            		switch(e.keyCode)
            		{
            			case 116: // F5
            				disableDefault(e);
            			break;
            		}
            	}
            });*/

            function disableDefault(e)
            {
            	
            	// FF
            	if (e.preventDefault)
            	{
            		e.preventDefault();
            	}
            		
            	// MSIE
            	else
            	{
            		e.returnValue = false;
            		// Fix som stuborn events, like F5 and ctrl-f
            		e.keyCode = 0; 
            	}
            	
            	return;	
            }
            
            
            
            
            // plupload
            $(".aloha-editable.html_edit_advanced").each(function(){
        
        //var target_textarea_id = $(this).attr('id').replace('-plupload', '');
        var drop_element = $(this).attr('id');
        //var language = $(this).attr('language');
        //alert(drop_element);
    	window['uploader_' + drop_element] = new plupload.Uploader({
    		runtimes : 'html5',
    		//browse_button : 'pickfiles',
            drop_element : drop_element,
    		//container : 'container',
    		max_file_size : '10mb',
    		url : '/admin/system/admin.actions/admin.upload.php',
    		//flash_swf_url : '/plupload/js/plupload.flash.swf',
    		//silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
    		filters : [
    			{title : "Image files", extensions : "jpg,jpeg,gif,png"},
    			//{title : "Zip files", extensions : "zip"}
    		],
    		//resize : {width : 320, height : 240, quality : 90}
            multi_selection:false,
            max_file_count: 1
    	});
    
    	window['uploader_' + drop_element].bind('Init', function(up, params) {
    		//$('#image-block').html("<div>Current runtime: " + params.runtime + "</div>");
    	});
        /*
    	$('#uploadfiles').click(function(e) {
    		uploader.start();
    		e.preventDefault();
    	});
        */
    	window['uploader_' + drop_element].init();
    
    	window['uploader_' + drop_element].bind('FilesAdded', function(up, files) {
    		$.each(files, function(i, file) {
    			//$('#image-block').append(
                /*$('#' + drop_element).append(
    				'<div class="slideshow-image" id="' + file.id + '">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>');*/
                
                //$("#" + drop_element).focus();
                /*document.selection.createRange().pasteHTML('<div class="slideshow-image" id="' + file.id + '">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>');*/
                //insertHtmlAtCursor();
                
                //$("#" + drop_element).focus();
                
                /*$("#element-" + window.random_id).replaceWith( '<div class="slideshow-image" id="' + file.id + '">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>' );*/
                
    		});
    
    		up.refresh(); // Reposition Flash/Silverlight
            up.start();
    	});
    
    	window['uploader_' + drop_element].bind('UploadProgress', function(up, file) {
    		//$('#' + file.id + " b").html(file.percent + "%");
            //console.log(file);
            $("#element-" + window.random_id + " b").html(file.percent + "%");
    	});
    
    	window['uploader_' + drop_element].bind('Error', function(up, err) {
    		$('#filelist').append("<div>Error: " + err.code +
    			", Message: " + err.message +
    			(err.file ? ", File: " + err.file.name : "") +
    			"</div>"
    		);
    
    		up.refresh(); // Reposition Flash/Silverlight
    	});
    
    	window['uploader_' + drop_element].bind('FileUploaded', function(up, file, response) {
    	   /*
           var images_text = $("#Slideshow_images_<?php echo $id . '_' . $project_id; ?>").val();
    	   
           var get_images = jQuery.parseJSON(images_text);
           if (get_images == null){
                var images = {"image_files" : []};
           } else {
                var images = {"image_files" : get_images};
           }
           */
           
           //var images = {"image_files" : []};
           
    		var obj = jQuery.parseJSON(response.response);
            /*$('#' + file.id).html('<div class="img" style="background: url(/images/1000x200/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + ')"></div>');
            $('#' + file.id).attr('year', obj.fileYear);
            $('#' + file.id).attr('month', obj.fileMonth);
            $('#' + file.id).attr('image', obj.fileName);
            images.image_files.push( { "id":file.id, "fileName":obj.fileName, "fileYear":obj.fileYear, "fileMonth":obj.fileMonth } );*/
            
            //$('#' + file.id).replaceWith('<img src="/uploads/image/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + '" />');
            
            // TEMPORARILY REMOVED
            $("#element-" + window.random_id).replaceWith('<img src="/uploads/image/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + '" />');
            
            var elem_id = drop_element.replace("-aloha", "");
            
            matchit(elem_id);
            
            //var new_images = JSON.stringify(images.image_files);
            //$("#" + target_textarea_id).val(new_images);
    	});
    
    });
            //plupload end
            
            // EDITOR funcs
            var button = $(".edit-tools-toolbar button");
            
            button.click( function() {
                var action = $(this).attr('action');
                var action_value = $(this).attr('action-value');
                $(".edit-tools-toolbar").show();
                $("#" + window.alohaEditable).addClass('aloha-editable-active');
                window.alohaRange.select();
                
                
                var curAlohaRange = Aloha.Selection.getRangeObject();
                Aloha.Selection.rangeObject.select();
                
                if ( Aloha.getSelection().rangeCount > 0 ) {
                    range = Aloha.getSelection().getRangeAt( 0 );
                } else {
                    //console.log('there is no range');
                }
                Aloha.execCommand( action, false, action_value );
                $("#" + window.alohaEditable).focus();
                updateActivated(action);
            });
            
            $(".insert-html").click( function() {
                $("#" + window.alohaEditable).addClass('aloha-editable-active');
                window.alohaRange.select();
                
                var curAlohaRange = Aloha.Selection.getRangeObject();
                Aloha.Selection.rangeObject.select();
                
                if ( Aloha.getSelection().rangeCount > 0 ) {
                    range = Aloha.getSelection().getRangeAt( 0 );
                } else {
                    //console.log('there is no range');
                }
                
                //Aloha.execCommand('inserthtml', false, '<div class="offer col1"><span class="title">neki tko</span><span class="image" style="background-image: url(/uploads/image/2013/04/cover.jpg);"></span></div>');
                //Aloha.block({default:'Some content'}).appendTo("#" + window.alohaEditable);
                
                /*
                if ($("#" + window.alohaEditable + " .block-container").length == 0){
                    $("#" + window.alohaEditable).append('<div class="block-container"></div>');
                }
                */
                
                //$("#" + window.alohaEditable + " .block-container").append('<div class="offer col1 aloha-block aloha-block-DefaultBlock" contenteditable="false" data-aloha-block-type="DefaultBlock" data-sortable-item="[object Object]"><span class="aloha-block-handle aloha-block-draghandle aloha-block-draghandle-blocklevel"></span><span class="title">neki tko</span><span class="image" style="background-image: url(/uploads/image/2013/04/cover.jpg);"></span></div>');
                $("#" + window.alohaEditable).append('<div class="offer col1 aloha-block aloha-block-DefaultBlock" contenteditable="false" data-aloha-block-type="DefaultBlock" data-sortable-item="[object Object]"><span class="aloha-block-handle aloha-block-draghandle aloha-block-draghandle-blocklevel"></span><span class="title">neki tko</span><span class="image" style="background-image: url(/uploads/image/2013/04/cover.jpg);"></span></div>');
                $("#" + window.alohaEditable).focus();
            })
            
            $(".delete-block").click( function() {
                $(this).parent().remove();
            })
            /*
            Aloha.bind('aloha-command-executed', function() {
                var currAlohaEditable = window.alohaEditable;
                var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
                patch_launch(currAlohaEditable, currAlohaEditableBack);
                console.log('command executed');
            });
            */
            Aloha.bind('aloha-selection-changed aloha-command-executed', function() {
                var currAlohaEditable = window.alohaEditable;
                var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
                var curAlohaRange = Aloha.Selection.getRangeObject();
                //console.log('active');
                if (curAlohaRange.startOffset < curAlohaRange.endOffset){
                    //console.log('selection made!');
                    window.alohaRange = curAlohaRange;
                } else {
                    //console.log('no selection');
                    window.alohaRange = curAlohaRange;
                }
                //console.log(window.paste_mode);
                if (window.paste_mode == undefined){
                    $('.btn-cmd').each(function() {
                        var action = $(this).attr('action');
                        updateActivated(action);
                    });
                    
                    var action_value = Aloha.queryCommandValue( 'formatblock' );
                    //console.log(action_value);
                    $(".btn-par").removeClass("active");
                    $("#formatblock" + action_value).addClass("active");
                    
                    var process_links = undefined;
                } else {
                    var process_links = 'true';
                    window.paste_mode = undefined;
                }
                
                //console.log('changed');
                //patch_launch(currAlohaEditable, currAlohaEditableBack);
                
                matchit(currAlohaEditableBack);
                
                /*
                if (process_links == 'true'){
                    var text = $("#" + currAlohaEditable).html();
                    var current_url = window.location.href;
                    console.log(current_url);
                    var current_url_escaped = addslashes(current_url);
                    text = text.replace(new RegExp(current_url_escaped, 'g'), '');
                    console.log(text);
                    $("#" + currAlohaEditableBack).val(text);
                }*/
                
                /*
                jQuery('#' + window.alohaEditable + ' a').off();
                jQuery('#' + window.alohaEditable + ' a').click( function(){
                    
                    jQuery("#link-popover").removeClass('in');
                    
                    var position = jQuery(this).position();
                    var height = jQuery(this).height();
                    var width = jQuery(this).width();
                    var popover_width = jQuery("#link-popover").width();
                    var container_width = jQuery(".custom-container-content").outerWidth();
                    
                    var new_left = position.left - popover_width / 2 + width / 2;
                    
                    var max_left = new_left + popover_width;
                    
                    var already_editing = $(this).attr('link-editing');
                    
                    if (already_editing != 'true'){
                        $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
                        $(this).attr('link-editing', 'true');
                        
                        if (new_left < 0){
                            jQuery("#link-popover").css('left', '0px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + new_left + 'px)');
                        }
                        if (new_left >= 0 && max_left <= container_width){
                            jQuery("#link-popover").css('left', new_left + 'px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', '50%');
                        }
                        if (new_left >= 0 && max_left > container_width){
                            var maximal = container_width - popover_width;
                            var arrow_left = max_left - container_width;
                            jQuery("#link-popover").css('left', maximal + 'px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + arrow_left + 'px)');
                        }
                        
                        jQuery("#link_edit_url").val($(this).attr('href'));
                        jQuery("#link_edit_target").val($(this).attr('target'));
                        jQuery("#link_edit_id").val($(this).attr('id'));
                        jQuery("#link_edit_class").val($(this).attr('class'));
                        
                        
                    }
                    
                    jQuery("#link-popover").addClass('in');
                });*/
                set_link_settings();
                
            });
        
        
        
        
        
        // ALOHA READY END BEFORE 
        //});
        
            $(".table-translations .aloha-input").css('width', 'calc(100% - 10px)');
            $(".table-translations .aloha-input").css('height', $(this).parent().height + 'px');
            
            $(".aloha-textarea").each(function() {
                var width = $(this).width();
                var new_width = width*1 - 1;
                $(this).css('width', new_width + 'px');
                // get id
                var the_id = $(this).attr('id');
                var split_id = the_id.split('-');
                var search_id = split_id[0];
                var txt_height = $("#" + search_id).height();
                $(this).css('width', 'calc(100% - 14px)').css('height', txt_height + 'px');
            });
            
            $('.html_edit_advanced, .html_edit_simple').change(function() {
                //alert('Handler for .change() called.');
                var currAlohaEditableBack = $(this).attr('id');
                var currAlohaEditable = currAlohaEditableBack + "-aloha";
                patch_launch(currAlohaEditableBack, currAlohaEditable);
            });
            
            jQuery(".aloha-editable").mousedown(function(){
                if ($(this).hasClass('html_edit_advanced') && !$(this).html())
                {
                    $(this).append('<p style=""><br style=""></p>');
                }
            });
            /*
            jQuery(".aloha-editable").mouseover(function(){
                if ($(this).hasClass('html_edit_advanced') && !$(this).html())
                {
                    $(this).append('<p style=""><br style=""></p>');
                }
            });*/
        
            jQuery(".aloha-editable").focusin(function() {
                //alert('Handler for .focus() called.');
                window.alohaEditable = $(this).attr("id");
                
                //Aloha.execCommand( 'formatblock', false, 'p' );
                //alert(window.alohaEditable);
                /*
                jQuery('#' + window.alohaEditable + ' a').off();
                jQuery('#' + window.alohaEditable + ' a').click( function(){
                    
                    jQuery("#link-popover").removeClass('in');
                    
                    var position = jQuery(this).position();
                    var height = jQuery(this).height();
                    var width = jQuery(this).width();
                    var popover_width = jQuery("#link-popover").width();
                    var container_width = jQuery(".custom-container-content").outerWidth();
                    
                    var new_left = position.left - popover_width / 2 + width / 2;
                    
                    var max_left = new_left + popover_width;
                    
                    var already_editing = $(this).attr('link-editing');
                    
                    if (already_editing != 'true'){
                        $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
                        $(this).attr('link-editing', 'true');
                        
                        if (new_left < 0){
                            jQuery("#link-popover").css('left', '0px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + new_left + 'px)');
                        }
                        if (new_left >= 0 && max_left <= container_width){
                            jQuery("#link-popover").css('left', new_left + 'px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', '50%');
                        }
                        if (new_left >= 0 && max_left > container_width){
                            var maximal = container_width - popover_width;
                            var arrow_left = max_left - container_width;
                            jQuery("#link-popover").css('left', maximal + 'px').css('top', position.top + height + 'px');
                            jQuery("#link-popover .arrow").css('left', 'calc(50% + ' + arrow_left + 'px)');
                        }
                        
                        jQuery("#link_edit_url").val($(this).attr('href'));
                        jQuery("#link_edit_target").val($(this).attr('target'));
                        jQuery("#link_edit_id").val($(this).attr('id'));
                        jQuery("#link_edit_class").val($(this).attr('class'));
                        
                        
                    }
                    
                    jQuery("#link-popover").addClass('in');
                });*/
                set_link_settings();
                
            });
            
            
        
            //jQuery(".aloha-input").focusin(function() {
            jQuery(".html_edit_simple").focusin(function() {
                $(".simple-editor").css("display", "inline");
                $(".advanced-editor").css("display", "none");
                $(".alignment-editor").css("display", "none");
            });
            
            //jQuery(".aloha-textarea").focusin(function() {
            jQuery(".html_edit_advanced").focusin(function(event) {
                $(".simple-editor").css("display", "none");
                $(".advanced-editor").css("display", "inline");
                $(".alignment-editor").css("display", "none");
                
                var id = $(this).attr('id');
                
            });
            /*
            jQuery(".html_edit_advanced div").focusin(function() {
                $(".simple-editor").css("display", "none");
                $(".advanced-editor").css("display", "none");
                $(".alignment-editor").css("display", "inline");
                
                var id = $(this).attr('id');
                
            });*/
            /*
            jQuery(".html_edit_advanced img").on('click', function() {
                alert('focused');
                $(this).resizable();
                $(".simple-editor").css("display", "none");
                $(".advanced-editor").css("display", "inline");
            });*/
            
            //$(document).on('click', '.txt-image-container', function() { 
            $(document).on('click', '.aloha-editable img', function(e) { 
                //console.log(e);
                console.log($(this).parent());
                //if ( $(this).parent().not( "div.txt-image-container" ) ) {
                if ( !$(this).parent().hasClass( "txt-image-container" ) ) {
                    //alert('not');
                    var width = $(this).width();
                    var height = $(this).height();
                    
                    var margin_top = $(this).css('margin-top');
                    var margin_right = $(this).css('margin-right');
                    var margin_bottom = $(this).css('margin-bottom');
                    var margin_left = $(this).css('margin-left');
                    var floating = $(this).css('float');
                    
                    
                    
                    var randomnumber = 'editing-' + Math.floor(Math.random()*10001) + '-' + Math.floor(Math.random()*10001) + '-' + Math.floor(Math.random()*10001);
                    window.editing_element_id = randomnumber;
                    
                    $(this).wrap('<div id="' + randomnumber + '" class="txt-image-container" style="width: ' + width + 'px; height: ' + height + 'px; margin: ' + margin_top + ' ' + margin_right + ' ' + margin_bottom + ' ' + margin_left + '; float: ' + floating + ';"></div>');
                    $(document).find("div.txt-image-container").alohaBlock();
                    
                    var element_margin_top = $(this).css('margin-top').replace('px', '');
                    var element_margin_right = $(this).css('margin-right').replace('px', '');
                    var element_margin_bottom = $(this).css('margin-bottom').replace('px', '');
                    var element_margin_left = $(this).css('margin-left').replace('px', '');
                    
                    $("#margin-top").val(element_margin_top);
                    $("#margin-right").val(element_margin_right);
                    $("#margin-bottom").val(element_margin_bottom);
                    $("#margin-left").val(element_margin_left);
                    
                    if (floating == 'left'){
                        $("input[name='element_float']").attr('checked', '');
                        $("#left-float").attr('checked', 'checked');
                        $("#preview_element").css('float', floating);
                    } else if (floating == 'right'){
                        $("input[name='element_float']").attr('checked', '');
                        $("#right-float").attr('checked', 'checked');
                        $("#preview_element").css('float', floating);
                    } else if (floating == 'none' || floating == undefined || !floating) {
                        $("input[name='element_float']").attr('checked', '');
                        $("#no-float").attr('checked', 'checked');
                        $("#preview_element").css('float', 'none');
                    }
                    
                    $(this).css('float', 'none').css('margin', '0');
                    
                    
                    //alert(window.editing_element_id);
                }
                $(this).parent().resizable();
                
                /*if ( $(this).parent().not( "div.txt-image-container.aloha-block" ) ) {
                    alert('not');
                    $(this).parent().alohaBlock();
                }*/
            });
            
            /*$(document).on('click', '.html_edit_advanced img', function() { 
                //alert('focused image'); 
                $(this).resizable();
                if ( $(document).find('.aloha-editable img').parent().not( "div.ui-wrapper" ) ) {
                    $(this).wrap('<div class="ui-wrapper" style="overflow: hidden; position: relative; width: 224px; height: 202px; top: 0px; left: 0px; margin: 0px;"></div>');
                    $(this).parent().append('<div class="ui-resizable-handle ui-resizable-e" style="z-index: 1000;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 1000;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 1000;"></div>')
                    $(document).find('.aloha-editable img').removeClass('ui-draggable ui-resizable');
                    $(document).find('.ui-resizable-handle').remove();
                    $(document).find('.aloha-editable img').unwrap();
                }
            });*/
            
            $(".html_edit_advanced").on(
                'drop',
                function(e){
                    //insertHtmlAtCursor(e);
                    if(e.originalEvent.dataTransfer){
                        if(e.originalEvent.dataTransfer.files.length) {
                            e.preventDefault();
                            e.stopPropagation();
                            /*UPLOAD FILES HERE*/
                            //upload(e.originalEvent.dataTransfer.files);
                            
                            insertHtmlAtCursor(e);
                        }   
                    }
                }
            );
            
            
            window.ctrlDown = false;
            var ctrlKey = 17, vKey = 86, cKey = 67, bKey = 66, iKey = 73, uKey = 85, sKey = 83, fiveKey = 53, kKey = 75, mKey = 77;
            if (window.alohaEditable){
            var hkAlohaEditable = window.alohaEditable;
            var hkAlohaEditableBack = hkAlohaEditable.replace("-aloha", "");
            }
            
            $(document).keydown(function(e)
            {
                if (e.keyCode == ctrlKey){
                    window.ctrlDown = true;
                    window.ctrl = true;
                } 
            }).keyup(function(e)
            {
                if (e.keyCode == ctrlKey){
                    window.ctrlDown = false;
                    window.ctrl = false;
                } 
            });
        
            $(".aloha-editable").keydown(function(e)
            //$(".aloha-editable").keyup(function(e)
            {
                //if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey)) return false;
                
                if (window.ctrlDown && (e.keyCode == vKey)){
                    window.paste_mode = 'true';
                    //console.log(Aloha.activeEditable.obj.attr('class'));
                }
                
                if (window.ctrlDown && (e.keyCode == bKey)){
                    //return false;
                    //alert($("#" + window.alohaEditable).selection());
                    
                    disableDefault(e);
                    //console.log('going on');
                    //console.log(window.getSelection().rangeCount);
                    
                    if (window.getSelection() != ''){
                    //if (window.getSelection().rangeCount > 1){
                        //console.log('bolding');
                        //Aloha.execCommand( 'bold', false, '' );
                    }
                    //Aloha.execCommand( 'bold', false, '' ); alert('bolding');
                    patch_launch(hkAlohaEditable, hkAlohaEditableBack);
                    //return false;
                }
                
                if (window.ctrlDown && (e.keyCode == iKey)){
                    //return false;
                    //console.log(window.getSelection());
                    disableDefault(e);
                    
                    if (window.getSelection() != ''){
                    //if (window.getSelection().rangeCount > 1){
                        //Aloha.execCommand( 'italic', false, '' );
                    }
                    patch_launch(hkAlohaEditable, hkAlohaEditableBack);
                }
                
                if (window.ctrlDown && (e.keyCode == uKey)){
                    //return false;
                    //console.log(window.getSelection().rangeCount);
                    disableDefault(e);
                    
                    if (window.getSelection() != ''){
                    //if (window.getSelection().rangeCount > 1){
                        //console.log('underlining');
                        Aloha.execCommand( 'underline', false, '' );
                    }
                    patch_launch(hkAlohaEditable, hkAlohaEditableBack);
                }
                /*
                // strikethrough - to be edited
                if (ctrlDown && (e.keyCode == mKey)){
                    return false;
                    Aloha.execCommand( 'strikethrough', false, '' );
                    patch_launch(hkAlohaEditable, hkAlohaEditableBack);
                }
                */
            });
        
    
        // ALOHA READY END NOW
        });
    
        // select boxes
        
        function format(state) {
            var originalOption = state.element;
     
            return "<img class='flag' src='/admin/theme/img/flags/16/" + $(originalOption).data('icon') + "' />" + state.text;
        }
        
        // initialize select2
        if ($(".classic-select").length){
            $(".classic-select").select2();
        }
        
        if ($(".translation-select").length){
            $(".translation-select").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });
        }
        
        $('form .classic-select').change(function(){
            var value = $(this).val();
            $(this).select2("val", value);
        })
        
        if ($(".daterange").length){
            $('.daterange').daterangepicker({format: 'YYYY-MM-DD', append_to: '#main-container-data', opens: 'custom'});
        }
        
        // initialize addresspicker
        if ($(".addresspicker").length){
            var geolat = $(".geo-lat").val();
            var geolng = $(".geo-lng").val();
            var addresspicker = $( ".addresspicker" ).addressPicker({
                map: '#geo-map',
                mapOptions:{
                    zoom: 16,
                    center: [geolat,geolng],
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoomControl: false
                },/*
                marker:{
                    values:[
                        {latLng:[geolat, geolng], data: {index: 0},
                            options:{icon: "http://maps.google.com/mapfiles/marker_green.png"}
                        },
                        {address:"86000 Poitiers, France", data: {index: 1}},
                        {address:"66000 Perpignan, France", data: {index: 2}}
                    ],
                    options:{
                        draggable: false
                    },
                },*/
                geocoderOptions: {
                    //appendAddressString: $(".addresspicker").val(),
                    address: $(".addresspicker").val(),
                    location: new google.maps.LatLng(geolat, geolng),
                    /*region: 'CH',
                    bounds: new google.maps.LatLngBounds(new google.maps.LatLng(geolat, geolng))*/
                },
                boundElements: {
                    '.geo-lat': 'lat',
                    '.geo-lng': 'lng'
                }
            });
		  
        }
        
        // MOBWRITE LOCAL
        //mobwrite.syncGateway = 'http://localhost:8080/scripts/q.py';
        
        // MOBWRITE GOOGLE APP
        //mobwrite.syncGateway = 'http://mobwrite-avantbon2.appspot.com/scripts/q.py';
        
        // SPEEDING UP SETTINGS
        /*
        mobwrite.minSyncInterval = 500;
        mobwrite.syncInterval = 1000;
        mobwrite.maxSyncInterval = 2000;
        mobwrite.timeoutInterval = 3000;
        */
        //mobwrite.debug = true;
        //mobwrite.share('seo_description_sl');
        
        // ShareJS implementation
        /*
        $("form[id^='EditContent']").each(function() {
            var form_id = $(this).attr('id');
            //mobwrite.share(form_id);
        });
        */
        
        /*
        var sharejs_options = {
            origin: "http://newsharejs-dani.herokuapp.com/channel"
        }
        
        $("form[id^='EditContent'] input, form[id^='EditContent'] textarea").each(function() {
            var elem_id = $(this).attr('id');
            var editor = ace.edit(elem_id);
            
            sharejs.open(elem_id, 'text', 'http://newsharejs-dani.herokuapp.com/channel', function (error, doc) {
            //sharejs.open(elem_id, 'text', sharejs_options, function (error, doc) {
                doc.attach_ace(editor);
                //doc.attach_textarea(elem);
            });
        });
        */
        
        //intro_en_7_1
        
        //var editor = ace.edit('intro_en_7_1');
        
        $("form[id^='EditContent'] input[class!='not-editable'], form[id^='EditContent'] textarea[class!='not-editable']").each(function() {
        
        //var elem_id = document.getElementById('intro_en_7_1');
        var elem_id = $(this).attr('id');
        
        //alert(elem_id);
        
        var element = document.getElementById(elem_id);
        
        //console.log(elem_id);
            
            //sharejs.open('hello3', 'text', 'http://newsharejs-dani.herokuapp.com/channel', function (error, doc) {
            sharejs.open(window.main_func + "_" + elem_id, 'text', 'http://newsharejs-dani.herokuapp.com/channel', function (error, doc) {
                //doc.attach_ace(editor);
                doc.attach_textarea(element);
                
                
                //console.log(elem_id + ": " + doc.getText());
                
                var current_text = doc.getText();
                
                if ($("#" + elem_id + "-aloha").length > 0){
                
                var aloha_text = $("#" + elem_id + "-aloha").html();
                
                if (!current_text && aloha_text != "" && aloha_text != undefined){
                    //console.log(elem_id + " has no text");
                    //matchit(elem_id);
                    $("#" + elem_id).val(aloha_text);
                    doc.insert(0, aloha_text);
                    
                }
                
                if (current_text && aloha_text == "" && aloha_text != undefined){
                    //console.log(elem_id + " has no text");
                    //matchit_reverse(elem_id);
                    $("#" + elem_id + "-aloha").html(current_text);
                    jQuery('.alohablock').alohaBlock();
                }
                
                if (current_text && current_text != aloha_text && aloha_text != undefined){
                    $("#" + elem_id + "-aloha").html(current_text);
                    jQuery('.alohablock').alohaBlock();
                }
                }
                
                doc.on('remoteop', function(op) {
                    console.log(op);
                    if($("#" + elem_id).val() != $("#" + elem_id + "-aloha").html()){
                        console.log('Version: ' + doc.version);
                        matchit_reverse(elem_id);
                    }
                });
            });
        
        $("#" + elem_id + "-aloha").keyup(function(){
            //console.log("keyup");
            matchit(elem_id);
        })
            
        });
        
        
        
    });
    /*
    $(".aloha-block").mouseup(function(){
        elem_id = window.alohaEditable.replace("-aloha", "");
        matchit(elem_id);
    })
    */
    
    function sharit(diffs, elem_id){
        sharejs.open(window.main_func + "_" + elem_id, 'text', 'http://newsharejs-dani.herokuapp.com/channel', function (error, doc) {
        
        diffs.reverse();
                
        console.log("DIFFS ARRAY");
        console.log(diffs);
        
        
        
        
        
        $.each(diffs, function(index, diff_item) {
        
        window.insert_point = undefined;
        window.text_to_insert = undefined;
        window.text_to_remove = undefined;
        
        //var current_text = doc.getText();
        
        //doc.attach_textarea(poskus);
        $.each(diffs[index]['diffs'], function(i, item) {
            console.log(diffs[index]['diffs'][i]);
            
            //var insert_point = undefined;
            //var text_to_insert = undefined;
            
            //console.log(diffs[0]['diffs'][0][1].length);
            
            window.start_point = diffs[index]['start1'];
            
            if (diffs[index]['diffs'][i][0] == 0  && i == 0){ //console.log("i=0");
                window.insert_point = diffs[index]['start1'] + diffs[index]['diffs'][0][1].length;
                
                //window.start_point = current_text.indexOf(diffs[index]['diffs'][0][1]);
                
            } else if (diffs[index]['diffs'][i][0] == 0  && i != 0) {
                window.insert_point = window.insert_point + diffs[index]['diffs'][i][1].length;
            } else if (diffs[index]['diffs'][i][0] == -1  && i == 0){
                window.insert_point = diffs[index]['start1'];
            } else if (diffs[index]['diffs'][i][0] == -1  && i != 0){
                window.insert_point = window.insert_point + diffs[index]['diffs'][i][1].length;
            } else if (diffs[index]['diffs'][i][0] == 1){
                //window.insert_point = window.insert_point - diffs[0]['diffs'][i][1].length;
            }
            
            /* else {
                insert_point = diffs[0]['start1'];
            }*/
            
            if (window.text_add == undefined){
                window.text_add = "";
            }
            
            if (window.text_del == undefined){
                window.text_del = "";
            }
            
            if (window.text_del2 == undefined){
                window.text_del2 = "";
            }
            
            if (diffs[index]['diffs'][i][0] == 0){
                window.text_add = window.text_add + diffs[index]['diffs'][i][1];
                window.text_del = window.text_del + diffs[index]['diffs'][i][1];
            }
            
            if (diffs[index]['diffs'][i][0] == -1){
                window.text_add = window.text_add + diffs[index]['diffs'][i][1];
            }
            
            if (diffs[index]['diffs'][i][0] == 1){
                window.text_del = window.text_del + diffs[index]['diffs'][i][1];
            }
            
            console.log('insert point: ' + window.start_point);
            
            
            
            /*
            if (diffs[0]['diffs'][i][0] == -1)
            {
                window.text_to_add = diffs[0]['diffs'][i][1];
                doc.insert(window.insert_point, window.text_to_add);
            }
            
            if (diffs[0]['diffs'][i][0] == 1)
            {
                window.text_to_del = diffs[0]['diffs'][i][1];
                doc.del(window.insert_point, window.text_to_del.length);
            }
            */
            
            
            
            /*
            if (insert_point != undefined && text_to_add != undefined){
                console.log("position: " + insert_point + " text: " + text_to_add);
                doc.insert(insert_point, text_to_add);
                
                insert_point = undefined;
                text_to_insert = undefined;
            }
            */
            //doc.insert(0, diffs[0][1]);
        });
        
        window.insert_point = undefined;
        window.text_to_insert = undefined;
        window.text_to_del = undefined;
        
        console.log("ADD: " + window.text_add);
        console.log("DELETE: " + window.text_del);
        
        doc.del(window.start_point, window.text_del.length);
        doc.insert(window.start_point, window.text_add);
        
        window.text_add = undefined;
        window.text_del = undefined;
        
        });
        
        /*if (window.insert_point != undefined && window.text_to_add != undefined){
            console.log("position: " + window.insert_point + " text: " + window.text_to_add);
            doc.insert(window.insert_point, window.text_to_add);
            
            window.insert_point = undefined;
            window.text_to_insert = undefined;
            window.text_to_remove = undefined;
        }*/
        
        });
    }
    
    function matchit(elem_id)
    {
        //var text1 = document.getElementById('contenteditable').innerHTML;
        //var text1 = $('#' + elem_id + '-aloha').html().replace("'", "&#39;").replace('"', "&quot;");
        var text1 = $('#' + elem_id + '-aloha').html();
        var text2 = document.getElementById(elem_id).value;
        //var text2 = $('#poskus').html();
        var patches = dmp.patch_fromText(patch_text);
        
        var diff = dmp.diff_main(text1, text2, true);
        
        var ms_start = (new Date).getTime();
        var results = dmp.patch_apply(patches, text1);
        var ms_end = (new Date).getTime();
        
        document.getElementById(elem_id).value = results[0];
        
        var patch_list = dmp.patch_make(text1, text2, diff);
        patch_text2 = dmp.patch_toText(patch_list);
        
        console.log("TEXT: " + patch_text2);
        
        if (patch_list[0] != undefined && patch_list[0]["diffs"] != undefined){
            console.log(patch_list[0]["diffs"]);
            
            //sharit(patch_list[0]["diffs"]);
            sharit(patch_list, elem_id);
        }
        
        /*
        document.getElementById('diffoutputdiff').innerHTML =
          '<FIELDSET><LEGEND>Patch:</' + 'LEGEND><PRE>' + patch_text2 +
          '</' + 'PRE></' + 'FIELDSET>';
          */
        
    }
    
    
    function matchit_reverse(elem_id)
    { console.log("onchange triggered");
        var text1 = document.getElementById(elem_id).value;
        var patches = dmp.patch_fromText(patch_text);
        
        var ms_start = (new Date).getTime();
        var results = dmp.patch_apply(patches, text1);
        var ms_end = (new Date).getTime();
        
        document.getElementById(elem_id + "-aloha").innerHTML = results[0];
        
        jQuery('.alohablock').alohaBlock();
    }
    
    
    
    var dmp = new diff_match_patch();
    var patch_text = '';
    
    function patch_launch(id1, id2) {
        if ($("#" + id1).is("input") || $("#" + id1).is("select") || $("#" + id1).is("textarea") || $("#" + id1).is("fieldset")){
            var text1 = $("#" + id1).val();
            var text2 = $("#" + id2).html();
        } else {
            var text1 = $("#" + id1).html(); //console.log(text1);
            var text1 = $("#" + id2).val();
            var current_url = window.location.href;
            
            var current_url_escaped = addslashes(current_url);
            
            // REMOVED cause of error "text1 undefined"
            //text1 = text1.replace(new RegExp(current_url_escaped, 'g'), '');
            
            //$("#" + currAlohaEditableBack).val(text);
        }
        
        //var diff = dmp.diff_main(text1, text2, true);
        //var patch_list = dmp.patch_make(text1, text2, diff);
        
        var patches = dmp.patch_fromText(patch_text);
        var results = dmp.patch_apply(patches, text1);
        if ($("#" + id2).is("input") || $("#" + id2).is("select") || $("#" + id2).is("textarea") || $("#" + id2).is("fieldset")){
            $("#" + id2).val(results[0]);
            
            //sharit(patch_list, elem_id);
            
            //$("#" + id2).attr('value', results[0]);
        } else {
            $("#" + id2).html(results[0]);
            //matchit_reverse(id2);
        }
    }
    
    
    
    // base64 encode, decode functions
    function utf8_to_b64( str ) {
        return window.btoa(unescape(encodeURIComponent( str )));
    }
    
    function b64_to_utf8( str ) {
        return decodeURIComponent(escape(window.atob( str )));
    }
    
    
    function add_new_content(call_func, project_id){
        var title = $('#' + call_func + '_title').val();
        var combine_function = 'AdminAction.' + call_func + '("' + title + '","' + project_id + '")';
        $('#action-board').html(eval(combine_function));
        //$('.modal-backdrop').removeClass('in').addClass('out').remove();
        $('.modal-backdrop').fadeOut().remove();
    }
    
    function duplicate_content(call_func, orig_title, new_title_input, project_id){
        var new_title = $(new_title_input).val().replace("'", "&#39;");
        var title = $('#' + call_func + '_title').val().replace("'", "&#39;");
        var combine_function = 'AdminAction.' + call_func + '("' + orig_title + '","' + new_title + '","' + project_id + '")';
        
        var result = eval(combine_function);
        
        if (result == 'title exists'){
            $(".duplicate_alert").slideDown('slow').delay(2000).slideUp('slow');
        } else {
            $('#action-board').html(result);
            $('#duplicateContent').modal('hide');
            $('.modal-backdrop').fadeOut().remove();
        }
    }
    
    $('#language-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })
    
    
            
    function update_textarea(){
        $(".aloha-editable").each(function() {
            var currAlohaEditable = $(this).attr('id');
            var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
            patch_launch(currAlohaEditable, currAlohaEditableBack);
        });
    }
    
    function update_editables(){
        $(".aloha-editable").each(function() {
            var currAlohaEditable = $(this).attr('id');
            var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
            patch_launch(currAlohaEditableBack, currAlohaEditable);
        });
    }
    
    function save_begin(){
        $(".save-btn").html('<i class="icon-2x save-preloader">&nbsp;&nbsp;&nbsp;&nbsp;</i><span class="save-btn-txt">Saving...</span>');
    }
    
    function save_finished(){
        $(".save-btn").effect('highlight', {color:"#ffff99"}, 2000);
        $(".save-btn").html('<i class="icon-save icon-2x"></i><span class="save-btn-txt">Save</span>');
    }
    
    function save_content(id, project_id, save_function){
        save_begin();
        update_textarea();
        
        var new_values = $('form').serialize();
        var new_values_replaced_first = new_values.replace(/&/g, 'ANDPARAMETER');
        var new_values_replaced_second = new_values_replaced_first.replace(/%26amp%3B/g, 'AMPERSAND');
        var new_values_replaced_third = new_values_replaced_second.replace(/%2B/g, 'PLUSSIGN');
        var new_values_replaced = new_values_replaced_third.replace(/%26nbsp%3B/g, 'SPACESIGN');
        
        var run_function = save_function + '("' + id + '", "' + project_id + '", "' + new_values_replaced + '")';
        
        $.when(eval(run_function)).done(save_finished);
    }
    
    function save_translations(language_to, save_function){
        save_begin();
        update_textarea();
        
        var new_values = $('form').serialize();
        var new_values_replaced_first = new_values.replace(/&/g, 'ANDPARAMETER');
        var new_values_replaced = new_values_replaced_first.replace(/%26amp%3B/g, 'AMPERSAND');
        
        var run_function = save_function + '("' + language_to + '", "' + new_values_replaced + '")';
        
        $.when(eval(run_function)).done(save_finished);
    }
    
    
    
    function hide_content_row(id, run_func){
        var title = $("#" + id + " td").first().html();
        if (run_func == 'trash_offer'){
            var message = 'Are you sure to delete "<b>' + title + '</b>"?';
        } else {
            var message = 'Are you sure to renew "<b>' + title + '</b>"?';
        }
        bootbox.confirm(message, function(result) {
            if (result){
                $("#" + id + " td").effect("highlight", {}, 1000);
                $("#" + id + " td").slideUp("slow");
                var func = 'AdminAction.' + run_func + '(' + id + ')';
                eval(func);
            }
        });
    }
    
    function logoff_user(){
        AdminAction.logoff_user();
        window.location = "/admin/login/";
    }
    
    var ctrlDown = false;
    var ctrlKey = 17, vKey = 86, cKey = 67;

    $(document).keydown(function(e)
    {
        if (e.keyCode == ctrlKey) ctrlDown = true;
    }).keyup(function(e)
    {
        if (e.keyCode == ctrlKey) ctrlDown = false;
    });
    
    $(document).mouseup(function(event) {
        /*
        if($(event.target).hasClass("aloha-block-handle") == true){
            console.log('block drop triggered');
            //return true;
        }
        */
        if ($(event.target).hasClass("aloha-editable") == true || $(event.target).parents(".aloha-editable").length != 0){
            
            if ($(event.target).hasClass("aloha-editable") == true){
                var currentEditable = $(event.target).attr("id");
            }
            
            if ($(event.target).parents(".aloha-editable").length != 0){
                var currentEditable = $(event.target).parents(".aloha-editable").attr("id");
            }
            
            //console.log('mouse up triggered ' + currentEditable);
            var elem_id = currentEditable.replace("-aloha", "");
            
            //matchit(elem_id);
            //patch_launch(currentEditable, elem_id);
            //setTimeout(function(){matchit(elem_id);}, 1500);
            
            matchit(elem_id);
            //setTimeout(function(){matchit(elem_id);}, 1500);
        }
    });
    
    $(document).click(function(event) {
        if($(event.target).hasClass("aloha-editable") == false && $(event.target).parents(".aloha-editable").length == 0 && $(event.target).parents(".edit-tools-toolbar").length == 0 && $(event.target).parents("#link-popover").length == 0) {
            $('.edit-tools-toolbar').hide();
            $("#link-popover").removeClass("in");
            $('.aloha-editable a[link-editing="true"]').removeAttr('link-editing');
            
            //console.log('click triggered');
            
            //$(document).find('.aloha-editable img.ui-resizable').resizable('destroy');
            //$(document).find('.aloha-editable img').off();
            //$(document).find('.aloha-editable .ui-wrapper').off();
            //$( ".aloha-editable img" ).resizable().resizable( "destroy" );
            //$( ".aloha-editable img" ).resizable( "destroy" );
            //widget.resizable("destroy").resizable("destroy");
            /*
            if ( $(document).find('.aloha-editable img').parent().is( "div.ui-wrapper" ) ) {
                $(document).find('.aloha-editable img').removeClass('ui-draggable ui-resizable');
                $(document).find('.ui-resizable-handle').remove();
                $(document).find('.aloha-editable img').unwrap();
            }*/
            
            if ( $(document).find('.aloha-editable img').parent().is( "div.txt-image-container" ) ) {
                var width = $("div.txt-image-container").width();
                var height = $("div.txt-image-container").height();
                
                //var floating = $("div.txt-image-container").css('float');
                //var margin = $("div.txt-image-container").css('margin');
                
                //$(this).width(width);
                //$(this).height(height);
                //$(this).css('width', width + 'px').css('height', height + 'px');
                $("div.txt-image-container img").attr('style', 'width: ' + width + 'px; height: ' + height + 'px;');
                $(document).find('.ui-resizable-handle').remove();
                $(document).find('div.txt-image-container .aloha-block-handle').remove();
                //$(document).find('.aloha-editable img').unwrap();
                $(document).find("div.txt-image-container").children().unwrap();
                
            }
            
            
            if (window.alohaEditable != undefined)
            {
                var currAlohaEditable = window.alohaEditable;
                var currAlohaEditableBack = currAlohaEditable.replace("-aloha", "");
                
                patch_launch(currAlohaEditable, currAlohaEditableBack);
            }
        } else {
            if($(event.target).is(".aloha-editable img") == false && $(event.target).parents(".edit-tools-toolbar").length == 0){
                var width = $("div.txt-image-container").width();
                var height = $("div.txt-image-container").height();
                
                var floating = $("div.txt-image-container").css('float');
                var margin_top = $("div.txt-image-container").css('margin-top');
                var margin_right = $("div.txt-image-container").css('margin-right');
                var margin_bottom = $("div.txt-image-container").css('margin-bottom');
                var margin_left = $("div.txt-image-container").css('margin-left');
                
                //$(this).width(width);
                //$(this).height(height);
                //$(this).css('width', width + 'px').css('height', height + 'px');
                $("div.txt-image-container img").attr('style', 'width: ' + width + 'px; height: ' + height + 'px;');
                
                $("div.txt-image-container img").css('margin-top', margin_top);
                $("div.txt-image-container img").css('margin-right', margin_right);
                $("div.txt-image-container img").css('margin-bottom', margin_bottom);
                $("div.txt-image-container img").css('margin-left', margin_left);
                
                if (floating != 'none'){
                    $("div.txt-image-container img").css('float', floating);
                }
                
                $(document).find('.ui-resizable-handle').remove();
                $(document).find('div.txt-image-container .aloha-block-handle').remove();
                //$(document).find('.aloha-editable img').unwrap();
                $(document).find("div.txt-image-container").children().unwrap();
            }
            
            $('.edit-tools-toolbar').show();
        }   
        
        
        /*if($(event.target).is(".aloha-editable div span") == true){  
            console.log('div clicked');
        }*/
        
        if ($(event.target).parents(".html_edit_advanced").length > 0){
            if ($(event.target).parents(".alohablock").length > 0 || $(event.target).is(".aloha-editable div.alohablock") == true || $(event.target).is(".aloha-editable img") == true || $(event.target).is(".aloha-editable video") == true){
                //console.log('div clicked');
                $(".simple-editor").css("display", "none");
                $(".advanced-editor").css("display", "none");
                $(".alignment-editor").css("display", "inline");
                
                var randomnumber = 'editing-' + Math.floor(Math.random()*10001) + '-' + Math.floor(Math.random()*10001) + '-' + Math.floor(Math.random()*10001);
                
                if (!$(event.target).parents('.alohablock').attr('id'))
                {
                    $(event.target).parents('.alohablock').attr('id', randomnumber);
                    window.editing_element_id = randomnumber;
                } else {
                    window.editing_element_id = $(event.target).parents('.alohablock').attr('id');
                }
                
                var floating = $(event.target).parents('.alohablock').css('float');
                console.log(floating);
                if (floating == 'left'){
                    $("input[name='element_float']").attr('checked', '');
                    $("#left-float").attr('checked', 'checked');
                    $("#preview_element").css('float', floating);
                } else if (floating == 'right'){
                    $("input[name='element_float']").attr('checked', '');
                    $("#right-float").attr('checked', 'checked');
                    $("#preview_element").css('float', floating);
                } else if (floating == 'none' || floating == undefined || !floating) {
                    $("input[name='element_float']").attr('checked', '');
                    $("#no-float").attr('checked', 'checked');
                    $("#preview_element").css('float', 'none');
                }
                
                var element_margin_top = $(event.target).parents('.alohablock').css('margin-top').replace('px', '');
                var element_margin_right = $(event.target).parents('.alohablock').css('margin-right').replace('px', '');
                var element_margin_bottom = $(event.target).parents('.alohablock').css('margin-bottom').replace('px', '');
                var element_margin_left = $(event.target).parents('.alohablock').css('margin-left').replace('px', '');
                
                $("#margin-top").val(element_margin_top);
                $("#margin-right").val(element_margin_right);
                $("#margin-bottom").val(element_margin_bottom);
                $("#margin-left").val(element_margin_left);
                
                $("#preview_element").css('margin', element_margin_top + 'px ' + element_margin_right + 'px ' + element_margin_bottom + 'px ' + element_margin_left + 'px');
                
            } else {
                $(".simple-editor").css("display", "none");
                $(".advanced-editor").css("display", "inline");
                $(".alignment-editor").css("display", "none");
                
                $("#" + window.editing_element_id).removeAttribute('id');
                
                delete window.editing_element_id;
            }
        }
    });
    
    function updateActivated(action) {
        if ( Aloha.queryCommandIndeterm( action ) ) {
            $("#" + action + ", #" + action + "-simple").addClass('btn-warning');
    		return;
    	} else {
            $("#" + action + ", #" + action + "-simple").removeClass('btn-warning');
    	}
        
        $("#" + action + ", #" + action + "-simple").attr( 'class', 
            Aloha.queryCommandState( action ) ? 'btn btn-cmd active' : 'btn btn-cmd'
        );
    }
    </script>


</body>
</html>