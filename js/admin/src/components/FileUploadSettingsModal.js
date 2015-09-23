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
                <label>Allowed filetypes (comma seperated, no spaces) </label>
                <input className="FormControl" bidi={this.setting('flamure.fileupload.allowed')}/>

                <label>Blocked filetypes (comma seperated, no spaces) </label>
                <input className="FormControl" bidi={this.setting('flamure.fileupload.blocked')}/>
            </div>

        ];
    }
}