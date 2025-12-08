var editor_config = {
    selector: '.yaTinyEditor',
    content_css: [
        '/css/ya-tiny-editor-v1-0.css',
        '/css/rk-css-v1-2.css',
        '/css/bootstrap-v2-0.css',
        '/assets/fontawesome/css/all.min.css',
    ],
    path_absolute : "/",
    Remove_Powered_By: true,
    menubar: 'edit insert view format table',
    plugins: 'advlist autolink lists link image charmap anchor searchreplace wordcount code fullscreen insertdatetime media save table directionality emoticons autoresize',
    toolbar: 'undo redo | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | fullscreen emoticons code',
    images_upload_url: '/admin/tinymce-upload',
    automatic_uploads: true,
    images_upload_base_path: '/files',
    relative_urls: false,
    image_dimensions: false,
    // extended_valid_elements: 'span[class|style]',
    // valid_children : "+span[tex],+script[type]",
    // IMPORTANT: TinyMCE custom upload handler
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/admin/tinymce-upload');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function () {
                if (xhr.status !== 200) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                let json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location !== 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = function () {
                reject('Image upload failed due to a XHR Transport error.');
            };

            let formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });
    },
    init_instance_callback: function(instance) {
        myeditor = instance;
        editorContainer = instance.editorContainer;
        header = editorContainer.childNodes[0].childNodes[0];
        editorContainer.style.width = '100%';
        editorContainer.style.height = '100px';
        if (header) {
            header.style.display = 'none';

            myeditor.on('focus', (function(headerRef) {
                return function() {
                    headerRef.style.display = 'block';
                };
            })(header));

            myeditor.on('blur', (function(headerRef) {
                return function() {
                    headerRef.style.display = 'none';
                };
            })(header));
        }
    },
    file_picker_callback : function(callback, value, meta) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

        var cmsURL = editor_config.path_absolute + 'admin/file-manager?editor=' + meta.fieldname;
        if (meta.filetype == 'image') {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.openUrl({
            url : cmsURL,
            title : 'Filemanager',
            width : x * 0.8,
            height : y * 0.8,
            resizable : "yes",
            close_previous : "no",
            onMessage: (api, message) => {
            callback(message.content);
            }
        });
    },
    setup: function (editor) {
        editor.on('submit', function (e) {
            var content = editor.getContent();
            var regex = /<iframe(.*?)\s+src=["'](https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\s"']+))["'](.*?)>\s*<\/iframe>/gi;
            var modifiedContent = content.replace(regex, function(match, p1, p2, p3) {
                var title = 'YouTube video';
                var titleMatch = match.match(/title="([^"]+)"/i);
                if (titleMatch) {
                    title = titleMatch[1];
                }
                return '<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/' + p3 + '" title="' + title + '" allowfullscreen></iframe></div>';
            });
            editor.setContent(modifiedContent);
        });
    },
};
tinymce.init(editor_config);
document.addEventListener('focusin', (e) => {
    if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
        e.stopImmediatePropagation();
    }
});