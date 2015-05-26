(function($) {
    $(document).ready(function() {
        $('#sortable1, #sortable2').sortable({
            connectWith: '.connectedSortable'
        }).disableSelection();

        var updateSortableField = function() {
            var selected = $('#sortable2').sortable('toArray');
            var string = selected.join(',');
            $('#videos').val(string);
        };

        $('#sortable2').bind('sortupdate', function(event, ui) {
            updateSortableField();
        });

        updateSortableField();
    });
})(jQuery);
