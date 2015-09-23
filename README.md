# flarum_fileupload

fileupload extension for flarum [work in progress]

adds upload button to composer, after clicking it you can select the file you want to upload. file url is appended to composer editor after succesfull upload.

TODO:
- move button to more appropriate place (waiting till visual buttons are added to flarum core)
- loading indication
- add s9e/TextFormatter parser, which will look for allowed file types and replace them with [image (based on filetype) + link]


INSTALLATION:

- clone this repository (or download zip) to flarum/extensions/fileupload/
- you can change settings (allowed file types and upload folder) in flarum/extensions/fileupload/src/Api/UploadAction.php
- create upload folder (flarum/upload) and make sure you set write permission on it (chmod 755)


USING:

- when opening composer (creating new discussion / replying / editing) you will see upload icon next to close window icon
- click it, select the file you want to upload (everything but .js, .html, .php and .doc is allowed by default)
- file will get uploaded and url of the file will get appended to your composer editor