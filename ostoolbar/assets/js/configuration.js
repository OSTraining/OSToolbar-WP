(function(Ractive, $) {
    $(function() {
        Ractive.DEBUG = false;

        var currentPermissions = JSON.parse($('#current-permissions').val());

        var ractive = new Ractive({
            el: '#ostoolbar-settings-panel',
            template: '#ostoolbar-settings-template',
            data: {
                'permissions': currentPermissions,
                'json': ''
            },
            oninit: function() {
                this.updateJSONValue();
            },
            updateJSONValue: function() {
                var permissions = this.get('permissions');
                var jsonValue = {};

                $.each(permissions, function(index, perm) {
                    jsonValue[index] = perm.allowed ? 1 : 0;
                });

                this.set('json', JSON.stringify(jsonValue));
            },
            updatePermissions: function() {
                var permissions = this.get('permissions');

                $.each($('#ostoolbar-settings-panel .role_permission'), function(index, ch) {
                    permissions[$(ch).data('name')].allowed = $(ch).is(':checked');
                });

                this.set('permissions', permissions);

                this.updateJSONValue();
            }
        });

        ractive.on('updatePermissions', function(e) {
            this.updatePermissions();
        });
    });
})(Ractive, jQuery);
