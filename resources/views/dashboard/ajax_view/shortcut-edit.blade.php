<div class="modal-dialogue">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Update Shortcut') }}</h6>
            <a href="#" role="button" type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <span class="fas fa-times"></span>
            </a>
        </div>
        <div class="modal-body">
            <form action="{{ route('shortcut-bookmarks.update', $shortcutBookmark->id) }}" method="POST"
                id="shortcut-bookmarks-edit-form">
                @csrf
                @method('PATCH')
                <div class="form-group mb-3">
                    <input type="text" name="name" class="form-control" id="shortcutNameEdit"
                        placeholder="Shortcut name" required value="{{ $shortcutBookmark->name }}">
                </div>
                <div class="form-group mb-3">
                    <input type="text" name="url" class="form-control" id="shortcutUrlEdit"
                        placeholder="Shortcut url" required value="{{ $shortcutBookmark->url }}">
                </div>
                <div class="form-group d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-success save-shortcut" data-bs-dismiss="modal"
                        disabled>
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
