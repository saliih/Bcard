/**
 * Created by sarra on 12/08/16.
 */
$(document).ready(function () {
    $('.wall-item').on('click', function (event) {
        var id = $(event.currentTarget).attr('data-value');
        $('#optionid').val(id);
    })
});