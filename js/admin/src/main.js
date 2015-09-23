import { extend } from 'flarum/extend';
import app from 'flarum/app';

import FileUploadSettingsModal from 'fileupload/components/FileUploadSettingsModal';


app.initializers.add('fileupload', () => {
  // TODO
    app.extensionSettings.fileupload = () => app.modal.show(new FileUploadSettingsModal());
});
