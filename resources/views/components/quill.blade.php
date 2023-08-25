<div class="h-40"
    x-data
    x-ref="quillEditor"
    x-init="
    toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block'],
    
    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    // [{ 'script': 'sub'}, { 'script': 'super' }],       superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction
    
    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],
    
    ['clean']                                         // remove formatting button
    ];
    
    quill = new Quill($refs.quillEditor, {modules: {
    toolbar: toolbarOptions
    },theme: 'snow'});
    quill.on('text-change', function () {
        $dispatch('quill-input', quill.root.innerHTML);
    });
    "
    x-on:quill-input.debounce.2000ms="@this.set('{{ $campo }}',$event.detail)">
    {!! $description !!}
</div>