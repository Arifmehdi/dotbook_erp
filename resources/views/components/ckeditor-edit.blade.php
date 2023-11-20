<script>
    window.editors = {};
    document.querySelectorAll('.ckEditor-edit').forEach((node, index) => {
        ClassicEditor
            .create(node, {})
            .then(newEditor => {
                newEditor.editing.view.change(writer => {
                    var height = node.getAttribute('data-height');
                    writer.setStyle('min-height', height + 'px', newEditor.editing.view.document
                        .getRoot());
                });
                window.editors[index] = newEditor
            });
    });
</script>
