<div>
    <div class="container mt-5">
        <div class="row">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="col-5">
                <h1>
                    SendMessage
                </h1>
                <form wire:submit.prevent="send" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="text">Message Text:</label>
                        <input type="text" id="text" class="form-control" wire:model="text">
                    </div>

                    <div class="form-group mt-3">
                        <label for="image">Attach Image (Optional):</label>
                        <input type="file" id="image" class="form-control" wire:model="image">
                    </div>
                    <div class="form-group mt-3">
                        <label for="video">Attach Video File (Optional):</label>
                        <input type="file" id="video" class="form-control" wire:model="video"
                            accept="image/*,video/*">
                    </div>
                    <div class="form-group mt-3">
                        <label for="audio">Attach Audio File (Optional):</label>
                        <input type="file" id="audio" class="form-control" wire:model="audio" accept="audio/*">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>
