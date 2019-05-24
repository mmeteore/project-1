CKEDITOR.editorConfig = function( config ) {
    config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'insert', groups: [ 'insert' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] },        
        { name: 'about', groups: [ 'about' ] }
    ];

    config.width = '95%';
    config.height = '800';
    config.removeButtons = 'Save,Preview,Print,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Replace,Find,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,BulletedList,Outdent,Indent,JustifyRight,JustifyLeft,JustifyCenter,JustifyBlock,BidiRtl,BidiLtr,Language,Unlink,Anchor,Flash,PageBreak,Iframe,Format,Styles,ShowBlocks,About,NewPage,Templates';
};
