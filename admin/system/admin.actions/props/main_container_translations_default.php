<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $translations, $language_from_code, $language_to_code, $language_from_name, $language_to_name, $all_languages;

?>

<div class="custom-container title-container">
    <h3>Translations</h3>
    <p>Translate short sentences on website to any language.</p>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container">
            <h2>Showing all translations</h2>
            <?php if (is_array($translations) && isset($translations)){ ?>
            <form id="translating_to_<?php echo $language_to_code; ?>">
                <table class="table table-striped table-hover table-translations">
                    <thead>
                        <tr>
                            <th class="span6">Translating from <i><?php echo $language_from_name; ?></i></th>
                            <th class="span6">Translating to <i><?php echo $language_to_name; ?></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($translations as $translation ){ ?>
                        <?php if ($translation['translation_' . $language_from_code]){ ?>
                        <tr id="<?php echo $translation['id']; ?>">
                            <td><?php echo $translation['translation_' . $language_from_code]; ?></td>
                            <td>
                                <textarea id="translation_<?php echo $language_to_code; ?>-<?php echo $translation['id']; ?>" name="translation_<?php echo $language_to_code; ?>-<?php echo $translation['id']; ?>" type="text" class="span12 html_edit_simple html_edit_translation"><?php echo $translation['translation_' . $language_to_code]; ?></textarea>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <?php } else { ?>
            <h5>There are no translations to display. Try to add some.</h5>
            <?php } ?>
        </div>
    </div>
    
    <div class="span2">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included right-editor">
                <div class="custom-container main-tools-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <label>Translate from:</label>
                    <p>
                        <select id="select_language_from" class="translation-select" onchange="change_translation_link()">
                            <?php foreach ($all_languages as $language){ ?>
                            <option value="<?php echo $language['code']; ?>" data-icon="<?php echo $language['icon']; ?>" <?php if ($language['code'] == $language_from_code){ ?> selected="selected" <?php } ?> ><?php echo $language['name']; ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>
                    <label>Translate to:</label>
                        <select id="select_language_to" class="translation-select" onchange="change_translation_link()">
                            <?php foreach ($all_languages as $language){ ?>
                            <option value="<?php echo $language['code']; ?>" data-icon="<?php echo $language['icon']; ?>" <?php if ($language['code'] == $language_to_code){ ?> selected="selected" <?php } ?> ><?php echo $language['name']; ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p><a id="go_translate_btn" class="btn translation-btn follow" href="/admin/interface/contents/translations_default/<?php echo $language_from_code . '_' . $language_to_code; ?>/" title="Translate">Translate</a></p>
                    <hr />
                    <p><button class="btn btn-primary save-btn" onclick="save_translations('<?php echo $language_to_code; ?>', 'AdminAction.translations_save')" href="/admin/interface/contents/" title="Save"><i class="icon-save icon-2x"></i> <span class="save-btn-txt">Save</span></button></p>
                    <!--
                    <p><a class="btn back-btn follow" href="/admin/interface/contents/" title="Go back"><i class="icon-double-angle-left"></i> Back</a></p>
                    -->
                </div>
                <?php self::load_text_editor(); ?>
            </div>
        </div>
    </div>
    <!--
    <div class="span1">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included">
                <div class="custom-container right-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><a class="btn follow" href="/admin/interface/contents/" title="Show all static contents"><i class="icon-list-alt icon-2x"></i></a></p>
                    <p><a class="btn" href="#addStaticContent" data-toggle="modal" no-follow="true" title="Create a new static content"><i class="icon-file-alt icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show rejected static contents"><i class="icon-trash icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
    -->
</div>

<!-- Modal -->
<div id="addStaticContent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new static content</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new static content.</p>
        <p><label>Title:</label><input id="add_new_static_content_title" class="input-xlarge" type="text" /></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_static_content" onclick="add_new_content('add_new_static_content', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>
        <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
// translation link generation
function change_translation_link(){
    var language_from = $("#select_language_from").val();
    var language_to = $("#select_language_to").val();
    
    $("#go_translate_btn").attr('href', '/admin/interface/contents/translations_default/' + language_from + '_' + language_to + '/');
}
</script>