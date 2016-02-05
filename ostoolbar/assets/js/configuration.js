(function(Ractive, $) {
    $(function() {
        Ractive.DEBUG = false;

        /**
         * Token
         *
         * @type       {Ractive}
         */
        var token = new Ractive({
            el: '#ostoolbar-token-panel',
            template: '#ostoolbar-token-template',
            data: {
                'token': $('#ostoolbar-token').val(),
                'defaultToken': $('#ostoolbar-default-token').val(),
                'connected': $('#ostoolbar-connected').val(),
                'showDefaultTokenWarning': $('#ostoolbar-connected').val() == 0,
                'usingDefaultToken': $('#ostoolbar-token').val() === $('#ostoolbar-default-token').val(),
                'edited': false
            },
            applyDefaultToken: function() {
                this.set('token', this.get('defaultToken'));
            }
        });

        token.on('applyDefaultToken', function() {
            this.applyDefaultToken();
        });

        token.observe('token', function (newValue) {
            this.set('usingDefaultToken', this.get('defaultToken') === newValue);
            this.set('showDefaultTokenWarning',
                (this.get('connected', 0) == 0 && !this.get('usingDefaultToken', false)) || newValue.trim() == ''
            );
            this.set('edited', newValue != $('#ostoolbar-token').val());
        });

        /**
         * Permissions
         *
         * @type       {Ractive}
         */
        var permissions = new Ractive({
            el: '#ostoolbar-permissions-panel',
            template: '#ostoolbar-permissions-template',
            data: {
                'permissions': JSON.parse($('#ostoolbar-current-permissions').val()),
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

                $.each($('#ostoolbar-permissions-panel .role_permission'), function(index, ch) {
                    permissions[$(ch).data('name')].allowed = $(ch).is(':checked');
                });

                this.set('permissions', permissions);

                this.updateJSONValue();
            }
        });

        permissions.on('updatePermissions', function(e) {
            this.updatePermissions();
        });
    });
})(Ractive, jQuery);
