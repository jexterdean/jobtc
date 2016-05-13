// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md

CKEDITOR.plugins.add( 'cleanuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = 'plugins/cleanuploader/start.php';

    }
});
