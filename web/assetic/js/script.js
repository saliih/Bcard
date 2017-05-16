/**
 * Created by sarra on 12/08/16.
 */
function htmlbt(id){
    var html = '';
    html +=' <span class="input-group-addon">';
    html +=' <a class="iframe-btn" href="/tinymce/plugins/filemanager/dialog.php?type=2&amp;field_id='+id+'"><i class="icon-upload bigger-110"></i>image</a>';
    html +='</span>';
    return html;
}