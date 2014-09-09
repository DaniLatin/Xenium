<?php
global $additional_text_editor, $additional_text_editor_modal;
?>
<div class="aloha-custom-editor custom-container edit-tools-toolbar">
    <p class="toolbar-title">Edit tools:</p>
    <hr />
    <span class="simple-editor">
        <p class="small-buttons">
            <button class="btn btn-cmd" id="bold-simple" title="Bold" action="bold" action-value=""><!--<i class="fam-icon-text-bold"></i>--><i class="icon-bold"></i></button>
            <button class="btn btn-cmd" id="italic-simple" title="Italic" action="italic" action-value=""><!--<i class="fam-icon-text-italic"></i>--><i class="icon-italic"></i></button>
            <button class="btn btn-cmd" id="underline-simple" title="Underline" action="underline" action-value=""><!--<i class="fam-icon-text-underline"></i>--><i class="icon-underline"></i></button>
            <button class="btn btn-cmd" id="strikethrough-simple" title="Strikethrough" action="strikethrough" action-value=""><!--<i class="fam-icon-text-strikethrough"></i>--><i class="icon-strikethrough"></i></button>
            <button class="btn" id="createlink-simple" title="Link" action="createlink" action-value="http://"><!--<i class="fam-icon-link"></i>--><i class="icon-link"></i></button>
        </p>
    </span>
    <span class="advanced-editor">
        <small>Basic:</small>
        <p class="small-buttons">
            <button class="btn btn-cmd" id="bold" title="Bold" action="bold" action-value=""><!--<i class="fam-icon-text-bold"></i>--><i class="icon-bold"></i></button>
            <button class="btn btn-cmd" id="italic" title="Italic" action="italic" action-value=""><!--<i class="fam-icon-text-italic"></i>--><i class="icon-italic"></i></button>
            <button class="btn btn-cmd" id="underline" title="Underline" action="underline" action-value=""><!--<i class="fam-icon-text-underline"></i>--><i class="icon-underline"></i></button>
            <button class="btn btn-cmd" id="strikethrough" title="Strikethrough" action="strikethrough" action-value=""><!--<i class="fam-icon-text-strikethrough"></i>--><i class="icon-strikethrough"></i></button>
            <!---->
        </p>
        <!--<hr class="separator" />-->
        <small>Align:</small>
        <p class="small-buttons">
            <button class="btn btn-cmd" id="justifyleft" title="Left" action="justifyleft" action-value=""><!--<i class="fam-icon-text-align-left"></i>--><i class="icon-align-left"></i></button>
            <button class="btn btn-cmd" id="justifycenter" title="Center" action="justifycenter" action-value=""><!--<i class="fam-icon-text-align-center"></i>--><i class="icon-align-center"></i></button>
            <button class="btn btn-cmd" id="justifyright" title="Right" action="justifyright" action-value=""><!--<i class="fam-icon-text-align-right"></i>--><i class="icon-align-right"></i></button>
            <button class="btn btn-cmd" id="justifyfull" title="Justify" action="justifyfull" action-valie=""><!--<i class="fam-icon-text-align-justify"></i>--><i class="icon-align-justify"></i></button>
        </p>
        
        <small>Paragraphs:</small>
        <p class="small-buttons">
            <button class="btn btn-par" id="formatblockp" title="Body" action="formatblock" action-value="p"><!--<i class="fam-icon-text-signature"></i>--><!--<i class="icon-edit"></i>--><b>&para;</b></button>
            <button class="btn btn-par" id="formatblockh1" title="Heading 1" action="formatblock" action-value="h1"><!--<i class="fam-icon-text-heading-1"></i>--><b>H<sup>1</sup></b></button>
            <button class="btn btn-par" id="formatblockh2" title="Heading 2" action="formatblock" action-value="h2"><!--<i class="fam-icon-text-heading-2"></i>--><b>H<sup>2</sup></b></button>
            <button class="btn btn-par" id="formatblockh3" title="Heading 3" action="formatblock" action-value="h3"><!--<i class="fam-icon-text-heading-3"></i>--><b>H<sup>3</sup></b></button>
            <button class="btn btn-par" id="formatblockh4" title="Heading 4" action="formatblock" action-value="h4"><b>H<sup>4</sup></b></button>
        </p>
        
        <small>Additional:</small>
        <p class="small-buttons">
            <button class="btn" id="insertunorderedlist" title="Bullet list" action="insertunorderedlist" action-value=""><!--<i class="fam-icon-text-list-bullets"></i>--><i class="icon-list-ul"></i></button>
            <button class="btn" id="insertorderedlist" title="Numbered list" action="insertorderedlist" action-value=""><!--<i class="fam-icon-text-list-numbers"></i>--><i class="icon-list-ol"></i></button>
            <button class="btn btn-cmd" id="subscript" title="Subscript" action="subscript"><!--<i class="fam-icon-text-subscript"></i>--><i class="icon-subscript"></i></button>
            <button class="btn btn-cmd" id="superscript" title="Superscript" action="superscript"><!--<i class="fam-icon-text-superscript"></i>--><i class="icon-superscript"></i></button>
        </p>
        <small>Insert:</small>
        <p>
            <button class="btn" id="createlink" title="Link" action="createlink" action-value="http://"><!--<i class="fam-icon-link"></i>--><i class="icon-link"></i></button>
            <button class="btn" title="Image" action="unlink"><!--<i class="fam-icon-image"></i>--><i class="icon-picture"></i></button>
            <button class="btn" title="Video"><!--<i class="fam-icon-film"></i>--><i class="icon-film"></i></button>
            <a href="#addIconModal" data-toggle="modal" class="btn" title="Icon" no-follow="true"><i class="icon-blank"></i></a>
            <?php foreach($additional_text_editor as $additional_setting) echo $additional_setting; ?>
        </p>
    </span>
    <span class="alignment-editor">
        <div class="preview_container">
            <img src="http://placehold.it/90x90&amp;text=[element]" id="preview_element" />
            <p>Lorem ipsum dolor sit amet</p>
            <div class="clear"></div>
        </div>
        <hr />
        <div>
            <div style="float: left;">
                <small>Floating:</small><br />
                &nbsp;&nbsp;<input value="none" type="radio" name="element_float" id="no-float" checked="checked" /> <small>none</small><br />
                &nbsp;&nbsp;<input value="left" type="radio" name="element_float" id="left-float" /> <small>left</small><br />
                &nbsp;&nbsp;<input value="right" type="radio" name="element_float" id="right-float" /> <small>right</small><br />
            </div>
            <div style="float: right;">
                <small>Margin (px):</small><br />
                <div style="text-align: center;"><input type="text" class="input-mini margin-input" id="margin-top" /></div>
                <div style="text-align: center;"><input type="text" class="input-mini margin-input" id="margin-left" /><i class="icon-move"></i><input type="text" class="input-mini margin-input" id="margin-right" /></div>
                <div style="text-align: center;"><input type="text" class="input-mini margin-input" id="margin-bottom" /></div>
            </div>
            <div class="clear"></div>
        </div>
    </span>
</div>

<div id="addIconModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Insert an icon in text</h3>
    </div>
    <div class="modal-body" style="height: 320px;">
        <p>Select an icon that you want to add and then select its size.</p>
        
        <p>
            <input name="e2_element" id="e2_element" />
        </p>
        
        <p>
            <label>Select icon color:</label>
            <input id="colorpicker" />
        </p>
        
        <p>
            <label>Select icon size:</label>
            <input id="icon_size" type="text" value="100" />
            <!--&nbsp;&nbsp;&nbsp;<input value="1" type="radio" name="text_textblock_size" checked="checked" /> 25%
            &nbsp;&nbsp;&nbsp;<input value="2" type="radio" name="text_textblock_size" /> 50%
            &nbsp;&nbsp;&nbsp;<input value="3" type="radio" name="text_textblock_size" /> 75%
            &nbsp;&nbsp;&nbsp;<input value="4" type="radio" name="text_textblock_size" /> 100%
            &nbsp;&nbsp;&nbsp;<input value="-one-third" type="radio" name="text_textblock_size" /> one third
            &nbsp;&nbsp;&nbsp;<input value="-two-thirds" type="radio" name="text_textblock_size" /> two thirds-->
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_icon" onclick="add_new_icon(); return false;">Insert icon</button>
        
    </div>
</div>

<!-- Modal -->
<?php foreach($additional_text_editor_modal as $additional_modal_setting) echo $additional_modal_setting; ?>

<script type="text/javascript">
    $("#text-editor-modals").html('');
    $(".modal").each(function(){
        var modal_element = $(this).detach();
        $("#text-editor-modals").append(modal_element);
    });

//$("#text-editor-modals").html('<?php //foreach($additional_text_editor_modal as $additional_modal_setting) echo preg_replace('~[\r\n]+~', '', $additional_modal_setting); ?>');

// load icons

var icm_icons = {
    'Web Applications' : [57436, 57437, 57438, 57439, 57524, 57525, 57526, 57527, 57528, 57531, 57532, 57533, 57534, 57535, 57536, 57537, 57541, 57545, 57691, 57692],
    'Business Icons' : [57347, 57348, 57375, 57376, 57377, 57379, 57403, 57406, 57432, 57433, 57434, 57435, 57450, 57453, 57456, 57458, 57460, 57461, 57463],
    'eCommerce' : [57392, 57397, 57398, 57399, 57402],
    'Currency Icons' : [],
    'Form Control Icons' : [57383, 57384, 57385, 57386, 57387, 57388, 57484, 57594, 57595, 57600, 57603, 57604, 57659, 57660, 57693],
    'User Action & Text Editor' : [57442, 57443, 57444, 57445, 57446, 57447, 57472, 57473, 57474, 57475, 57476, 57477, 57539, 57662, 57668, 57669, 57670, 57671, 57674, 57675, 57688, 57689],
    'Charts and Codes' : [57493],
    'Attentive' : [57543, 57588, 57590, 57591, 57592, 57593, 57596],
    'Multimedia Icons' : [57356, 57357, 57362, 57363, 57448, 57485, 57547, 57548, 57549, 57605, 57606, 57609, 57610, 57611, 57614, 57617, 57618, 57620, 57621, 57622, 57623, 57624, 57625, 57626],
    'Location and Contact' : [57344, 57345, 57346, 57404, 57405, 57408, 57410, 57411, 57413, 57414, 57540],
    'Date and Time' : [57415, 57416, 57417, 57421, 57422, 57423],
    'Devices' : [57359, 57361, 57364, 57425, 57426, 57430],
    'Tools' : [57349, 57350, 57352, 57355, 57365, 57478, 57479, 57480, 57481, 57482, 57483, 57486, 57487, 57488, 57663, 57664],
    'Social and Networking' : [57694, 57700, 57701, 57702, 57703, 57704, 57705, 57706, 57707, 57709, 57710, 57711, 57717, 57718, 57719, 57736, 57737, 57738, 57739, 57740, 57741, 57742, 57746, 57747, 57748, 57755, 57756, 57758, 57759, 57760, 57761, 57763, 57764, 57765, 57766, 57767, 57776],
    'Brands' : [57743, 57750, 57751, 57752, 57753, 57754, 57757, 57773, 57774, 57775, 57789, 57790, 57792, 57793],
    'Files & Documents' : [57378, 57380, 57381, 57382, 57390, 57391, 57778, 57779, 57780, 57781, 57782, 57783, 57784, 57785, 57786, 57787],
    'Like & Dislike Icons' : [57542, 57544, 57550, 57551, 57552, 57553, 57554, 57555, 57556, 57557],
    'Emoticons' : [57558, 57559, 57560, 57561, 57562, 57563, 57564, 57565, 57566, 57567, 57568, 57569, 57570, 57571, 57572, 57573, 57574, 57575, 57576, 57577, 57578, 57579, 57580, 57581, 57582, 57583],
    'Directional Icons' : [57584, 57585, 57586, 57587, 57631, 57632, 57633, 57634, 57635, 57636, 57637, 57638, 57639, 57640, 57641, 57642, 57643, 57644, 57645, 57646, 57647, 57648, 57649, 57650, 57651, 57652, 57653, 57654],
    'Other Icons' : [57351, 57353, 57354, 57358, 57360, 57366, 57367, 57368, 57369, 57370, 57371, 57372, 57373, 57374, 57389, 57393, 57394, 57395, 57396, 57400, 57401, 57407, 57409, 57412, 57418, 57419, 57420, 57424, 57427, 57428, 57429, 57431, 57440, 57441, 57449, 57451, 57452, 57454, 57455, 57457, 57459, 57462, 57464, 57465, 57466, 57467, 57468, 57469, 57470, 57471, 57489, 57490, 57491, 57492, 57494, 57495, 57496, 57497, 57498, 57499, 57500, 57501, 57502, 57503, 57504, 57505, 57506, 57507, 57508, 57509, 57510, 57511, 57512, 57513, 57514, 57515, 57516, 57517, 57518, 57519, 57520, 57521, 57522, 57523, 57529, 57530, 57538, 57546, 57589, 57597, 57598, 57599, 57601, 57602, 57607, 57608, 57612, 57613, 57615, 57616, 57619, 57627, 57628, 57629, 57630, 57655, 57656, 57657, 57658, 57661, 57665, 57666, 57667, 57672, 57673, 57676, 57677, 57678, 57679, 57680, 57681, 57682, 57683, 57684, 57685, 57686, 57687, 57690, 57695, 57696, 57697, 57698, 57699, 57708, 57712, 57713, 57714, 57715, 57716, 57720, 57721, 57722, 57723, 57724, 57725, 57726, 57727, 57728, 57729, 57730, 57731, 57732, 57733, 57734, 57735, 57744, 57745, 57749, 57762, 57768, 57769, 57770, 57771, 57772, 57777, 57788, 57791, 57794]
};
 
var icm_icon_search = {
    'Web Applications' : ['Box add', 'Box remove', 'Download', 'Upload', 'List', 'List 2', 'Numbered list', 'Menu', 'Menu 2', 'Cloud download', 'Cloud upload', 'Download 2', 'Upload 2', 'Download 3', 'Upload 3', 'Globe', 'Attachment', 'Bookmark', 'Embed', 'Code'],
    'Business Icons' : ['Office', 'Newspaper', 'Book', 'Books', 'Library', 'Profile', 'Support', 'Address book', 'Cabinet', 'Drawer', 'Drawer 2', 'Drawer 3', 'Bubble', 'Bubble 2', 'User', 'User 2', 'User 3', 'User 4', 'Busy'],
    'eCommerce' : ['Tag', 'Cart', 'Cart 2', 'Cart 3', 'Calculate'],
    'Currency Icons' : [],
    'Form Control Icons' : ['Copy', 'Copy 2', 'Copy 3', 'Paste', 'Paste 2', 'Paste 3', 'Settings', 'Cancel circle', 'Checkmark circle', 'Spell check', 'Enter', 'Exit', 'Radio checked', 'Radio unchecked', 'Console'],
    'User Action & Text Editor' : ['Undo', 'Redo', 'Flip', 'Flip 2', 'Undo 2', 'Redo 2', 'Zoomin', 'Zoomout', 'Expand', 'Contract', 'Expand 2', 'Contract 2', 'Link', 'Scissors', 'Bold', 'Underline', 'Italic', 'Strikethrough', 'Table', 'Table 2', 'Indent increase', 'Indent decrease'],
    'Charts and Codes' : ['Pie'],
    'Attentive' : ['Eye blocked', 'Warning', 'Question', 'Info', 'Info 2', 'Blocked', 'Spam'],
    'Multimedia Icons' : ['Image', 'Image 2', 'Play', 'Film', 'Forward', 'Equalizer', 'Brightness medium', 'Brightness contrast', 'Contrast', 'Play 2', 'Pause', 'Forward 2', 'Play 3', 'Pause 2', 'Forward 3', 'Previous', 'Next', 'Volume high', 'Volume medium', 'Volume low', 'Volume mute', 'Volume mute 2', 'Volume increase', 'Volume decrease'],
    'Location and Contact' : ['Home', 'Home 2', 'Home 3', 'Phone', 'Phone hang up', 'Envelope', 'Location', 'Location 2', 'Map', 'Map 2', 'Flag'],
    'Date and Time' : ['History', 'Clock', 'Clock 2', 'Stopwatch', 'Calendar', 'Calendar 2'],
    'Devices' : ['Camera', 'Headphones', 'Camera 2', 'Keyboard', 'Screen', 'Tablet'],
    'Tools' : ['Pencil', 'Pencil 2', 'Pen', 'Paint format', 'Dice', 'Key', 'Key 2', 'Lock', 'Lock 2', 'Unlocked', 'Wrench', 'Cog', 'Cogs', 'Cog 2', 'Filter', 'Filter 2'],
    'Social and Networking' : ['Share', 'Googleplus', 'Googleplus 2', 'Googleplus 3', 'Googleplus 4', 'Google drive', 'Facebook', 'Facebook 2', 'Facebook 3', 'Twitter', 'Twitter 2', 'Twitter 3', 'Vimeo', 'Vimeo 2', 'Vimeo 3', 'Github', 'Github 2', 'Github 3', 'Github 4', 'Github 5', 'Wordpress', 'Wordpress 2', 'Tumblr', 'Tumblr 2', 'Yahoo', 'Soundcloud', 'Soundcloud 2', 'Reddit', 'Linkedin', 'Lastfm', 'Lastfm 2', 'Stumbleupon', 'Stumbleupon 2', 'Stackoverflow', 'Pinterest', 'Pinterest 2', 'Yelp'],
    'Brands' : ['Joomla', 'Apple', 'Finder', 'Android', 'Windows', 'Windows 8', 'Skype', 'Paypal', 'Paypal 2', 'Paypal 3', 'Chrome', 'Firefox', 'Opera', 'Safari'],
    'Files & Documents' : ['File', 'File 2', 'File 3', 'File 4', 'Folder', 'Folder open', 'File pdf', 'File openoffice', 'File word', 'File excel', 'File zip', 'File powerpoint', 'File xml', 'File css', 'Html 5', 'Html 52'],
    'Like & Dislike Icons' : ['Eye', 'Eye 2', 'Star', 'Star 2', 'Star 3', 'Heart', 'Heart 2', 'Heart broken', 'Thumbs up', 'Thumbs up 2'],
    'Emoticons' : ['Happy', 'Happy 2', 'Smiley', 'Smiley 2', 'Tongue', 'Tongue 2', 'Sad', 'Sad 2', 'Wink', 'Wink 2', 'Grin', 'Grin 2', 'Cool', 'Cool 2', 'Angry', 'Angry 2', 'Evil', 'Evil 2', 'Shocked', 'Shocked 2', 'Confused', 'Confused 2', 'Neutral', 'Neutral 2', 'Wondering', 'Wondering 2'],
    'Directional Icons' : ['Point up', 'Point right', 'Point down', 'Point left', 'Arrow up left', 'Arrow up', 'Arrow up right', 'Arrow right', 'Arrow down right', 'Arrow down', 'Arrow down left', 'Arrow left', 'Arrow up left 2', 'Arrow up 2', 'Arrow up right 2', 'Arrow right 2', 'Arrow down right 2', 'Arrow down 2', 'Arrow down left 2', 'Arrow left 2', 'Arrow up left 3', 'Arrow up 3', 'Arrow up right 3', 'Arrow right 3', 'Arrow down right 3', 'Arrow down 3', 'Arrow down left 3', 'Arrow left 3'],
    'Other Icons' : ['Quill', 'Blog', 'Droplet', 'Images', 'Music', 'Pacman', 'Spades', 'Clubs', 'Diamonds', 'Pawn', 'Bullhorn', 'Connection', 'Podcast', 'Feed', 'Stack', 'Tags', 'Barcode', 'Qrcode', 'Ticket', 'Coin', 'Credit', 'Notebook', 'Pushpin', 'Compass', 'Alarm', 'Alarm 2', 'Bell', 'Print', 'Laptop', 'Mobile', 'Mobile 2', 'Tv', 'Disk', 'Storage', 'Reply', 'Bubbles', 'Bubbles 2', 'Bubbles 3', 'Bubbles 4', 'Users', 'Users 2', 'Quotes left', 'Spinner', 'Spinner 2', 'Spinner 3', 'Spinner 4', 'Spinner 5', 'Spinner 6', 'Binoculars', 'Search', 'Hammer', 'Wand', 'Aid', 'Bug', 'Stats', 'Bars', 'Bars 2', 'Gift', 'Trophy', 'Glass', 'Mug', 'Food', 'Leaf', 'Rocket', 'Meter', 'Meter 2', 'Dashboard', 'Hammer 2', 'Fire', 'Lab', 'Magnet', 'Remove', 'Remove 2', 'Briefcase', 'Airplane', 'Truck', 'Road', 'Accessibility', 'Target', 'Shield', 'Lightning', 'Switch', 'Powercord', 'Signup', 'Tree', 'Cloud', 'Earth', 'Bookmarks', 'Notification', 'Close', 'Checkmark', 'Checkmark 2', 'Minus', 'Plus', 'Stop', 'Backward', 'Stop 2', 'Backward 2', 'First', 'Last', 'Eject', 'Loop', 'Loop 2', 'Loop 3', 'Shuffle', 'Tab', 'Checkbox checked', 'Checkbox unchecked', 'Checkbox partial', 'Crop', 'Font', 'Text height', 'Text width', 'Omega', 'Sigma', 'Insert template', 'Pilcrow', 'Lefttoright', 'Righttoleft', 'Paragraph left', 'Paragraph center', 'Paragraph right', 'Paragraph justify', 'Paragraph left 2', 'Paragraph center 2', 'Paragraph right 2', 'Paragraph justify 2', 'Newtab', 'Mail', 'Mail 2', 'Mail 3', 'Mail 4', 'Google', 'Instagram', 'Feed 2', 'Feed 3', 'Feed 4', 'Youtube', 'Youtube 2', 'Lanyrd', 'Flickr', 'Flickr 2', 'Flickr 3', 'Flickr 4', 'Picassa', 'Picassa 2', 'Dribbble', 'Dribbble 2', 'Dribbble 3', 'Forrst', 'Forrst 2', 'Deviantart', 'Deviantart 2', 'Steam', 'Steam 2', 'Blogger', 'Blogger 2', 'Tux', 'Delicious', 'Xing', 'Xing 2', 'Flattr', 'Foursquare', 'Foursquare 2', 'Libreoffice', 'Css 3', 'IE', 'IcoMoon']
};

    $('#e2_element').fontIconPicker({
        useAttribute: true,
        source: icm_icons,
        searchSource: icm_icon_search,
        theme: 'fip-bootstrap',
        attributeName: 'data-icomoon'
    });
    
    $("#colorpicker").spectrum({
    preferredFormat: "hex",
    flat: false,
    showInput: true,
    allowEmpty:true
});

$("input[name='element_float']").click(function(){
    var floating = $(this).val(); //console.log(floating);
    $("#preview_element").css('float', floating);
    //console.log(window.editing_element_id);
    $("#" + window.editing_element_id).css('float', floating);
    
    var elem_id = window.alohaEditable.replace("-aloha", "");
    matchit(elem_id);
});

$(".margin-input").keyup(function(){
    var new_margin_top = $("#margin-top").val();
    var new_margin_right = $("#margin-right").val();
    var new_margin_bottom = $("#margin-bottom").val();
    var new_margin_left = $("#margin-left").val();
    
    $("#preview_element").css('margin', new_margin_top + 'px ' + new_margin_right + 'px ' + new_margin_bottom + 'px ' + new_margin_left + 'px');
    $("#" + window.editing_element_id).css('margin', new_margin_top + 'px ' + new_margin_right + 'px ' + new_margin_bottom + 'px ' + new_margin_left + 'px');
    //alert(window.editing_element_id);
    var elem_id = window.alohaEditable.replace("-aloha", "");
    matchit(elem_id);
});
    
    function add_new_icon(){
        var icon_val = $('#e2_element').val();
        var icon = $('[data-fip-value="' + icon_val + '"]').attr('data-icomoon');
        var icon_size = $("#icon_size").val()*1;
        var icon_color = $("#colorpicker").val();
        var add_style = ' style="';
        if (icon_size > 100){
            add_style = add_style + 'font-size: ' + icon_size + '%; ';
        }
        if (icon_color != '')
        {
            add_style = add_style + 'color: ' + icon_color + ';';
        }
        add_style = add_style + '" ';
        if (icon_size == 100 && icon_color == ''){
            add_style = '';
        }
        var icon_insert = '<div class="alohablock iconblock" data-icomoon="' + icon + '"' + add_style + '></div>';
        $("#" + window.alohaEditable).prepend(icon_insert);
        $("#" + window.alohaEditable).focus();
        
        var elem_id = window.alohaEditable.replace("-aloha", "");
        matchit(elem_id);
        
        jQuery('.aloha-editable .alohablock').alohaBlock();
        bind_aloha_block_functions();
    }
</script>