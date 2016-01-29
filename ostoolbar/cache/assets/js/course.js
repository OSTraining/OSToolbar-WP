// OSTeammate
(function($) {
    var timeout;

    function defer(method) {
        if (typeof $ !== 'undefined' && $ !== null) {
            method($);
            clearTimeout(timeout);
        } else {
            timeout = setTimeout(function() {
                defer(method)
            }, 100);
        }
    }

    defer(function($) {
        $(document).ready(function() {
            $('#ostTabs a').click(function(e) {
                e.preventDefault();
                $this = $(this);

                var contentId = $(this).attr('href');

                $('#ostTabs a').each(function(i, elem) {
                    $elem = $(elem);
                    $elem.parent().removeClass('active');
                    $($elem.attr('href')).removeClass('active');
                });

                $this.parent().addClass('active');
                $($this.attr('href')).addClass('active');
            });
        });
    });
})(jQuery);
