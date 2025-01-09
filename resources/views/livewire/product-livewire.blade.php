<div>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Products</h1>
                    <button class="btn btn-{{ $activeForm ? 'secondary' : 'primary' }}"
                        wire:click="{{ $activeForm ? 'cancel' : 'create' }}">{{ $activeForm ? 'Cancel' : 'Create' }}</button>
                    @if ($activeForm)
                        <form wire:submit.prevent="save" enctype="multipart/form-data">
                            <div class="row mt-2">
                                <div class="col-3">
                                    <input type="text" wire:model.blur="name" class="form-control"
                                        placeholder="Name">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3">
                                    <input type="text" wire:model.blur="description" class="form-control"
                                        placeholder="Description">
                                </div>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3">
                                    <input type="text" wire:model.blur="title" class="form-control"
                                        placeholder="Title">
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3">
                                    <input type="text" wire:model.blur="price" class="form-control"
                                        placeholder="Price">
                                </div>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <input type="text" wire:model.blur="count" class="form-control"
                                        placeholder="Quantity">
                                </div>
                                @error('count')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <input type="file" wire:model="image" class="form-control">
                                </div>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <select name="category_id" wire:model="category_id" class="form-select">
                                        @foreach ($categories as $category)
                                            <option class="form-control" value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <select name="attribute_id" wire:model="attribute_id" class="form-select">
                                        <option value="">Select Attribute</option>
                                        @foreach ($atts as $att)
                                            <option value="{{ $att->id }}">{{ $att->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('attribute_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <select name="character_id" wire:model="character_id" class="form-select">
                                        @foreach ($characters as $char)
                                            <option class="form-control" value="{{ $char->id }}">
                                                {{ $char->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('character_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="col-3 mt-2">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                    @endif

                    @if (!$activeForm)
                        <div class="container-fluid mt-3">
                            <div class="row">
                                @foreach ($models as $model)
                                    <div class="col-md-4 mb-4">
                                        <div class="card" style="width: 18rem;">
                                            <img src="{{ asset('storage/' . $model->image) }}" class="card-img-top"
                                                alt="..." width="200px" height="200px">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $model->name }}</h5>
                                                <p class="card-text">{{ \Str::limit($model->description, 50) }}</p>
                                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#productModal{{ $model->id }}">Batafsil</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="productModal{{ $model->id }}" tabindex="-1"
                                        aria-labelledby="productModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="productModalLabel">{{ $model->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset('storage/' . $model->image) }}"
                                                        class="img-fluid" alt="" width="200px" height="200px">
                                                    <p><strong>Description:</strong> {{ $model->description }}</p>
                                                    <p><strong>Price:</strong> {{ $model->elements ? $model->elements->first()->price : "" }}</p>
                                                    <p><strong>Category:</strong> {{ $model->category->name }}</p>
                                                    <p><strong>Quantity:</strong> {{ $model->elements ? $model->elements->first()->count : "" }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
