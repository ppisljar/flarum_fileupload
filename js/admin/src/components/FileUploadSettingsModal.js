import SettingsModal from 'flarum/components/SettingsModal';

export default class FileUploadSettingsModal extends SettingsModal {
    className() {
        return 'FileUploadSettingsModal Modal--small';
    }

    title() {
        return 'File Upload Settings';
    }

    form() {
        return [
            <div className="Form-group">
                <label>File Upload</label>
                <input className="FormControl" bidi={this.setting('hyn.fileupload.allowed')}/>
            </div>
            <div className="Form-group">
                <label>File Upload</label>
                <input className="FormControl" bidi={this.setting('hyn.fileupload.blocked')}/>
            </div>
        ];
    }
}