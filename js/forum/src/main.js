import { extend, override } from 'flarum/extend';
import app from 'flarum/app';

import Composer from 'flarum/components/Composer';
import ComposerBody from 'flarum/components/ComposerBody';
import ComposerButton from 'flarum/components/ComposerButton';


var loading = false;

var success = function(response) {
    loading = false;
    if (!response.data) return;

    // gets the current url and appends the file to it
    // TODO: this is not good if flarum is installed in a subfolder
    var file = location.protocol + '//' + location.host + response.data.id;
    var content = app.composer.component.content();
    content = content ? content : "";
    content += "\n\n" + file;

    app.composer.component.editor.setValue(content);
};

var failure = function(response) {
    loading = false;

    app.alertErrors([{detail: app.trans('fileupload.uploadfailed')}]);
};

var upload = function() {
    if (loading) return;

    // Create a hidden HTML input element and click on it so the user can select
    // an avatar file. Once they have, we will upload it via the API.
    // const user = this.props.user;
    const $input = $('<input type="file">');

    $input.appendTo('body').hide().click().on('change', e => {
        const data = new FormData();
        data.append('file', $(e.target)[0].files[0]);

        loading = true;
        //m.redraw();

        app.request({
            method: 'POST',
            url: app.forum.attribute('apiUrl') + '/upload',
            serialize: raw => raw,
            data
        }).then(
            success.bind(this),
            failure.bind(this)
        );
    });
};


extend(Composer.prototype, 'controlItems', function(oItems) {

    oItems.add('uploadFile', ComposerButton.component({
        icon: 'upload',
        title: app.trans('fileupload.upladnewfile'),
        onclick: function() {
            upload();
        }
    }));

    return oItems;
});
