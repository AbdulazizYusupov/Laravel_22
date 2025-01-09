<div>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Attributes</h1>
                    <button class="btn btn-{{ $activeForm ? 'secondary' : 'primary' }}"
                        wire:click="{{ $activeForm ? 'cancel' : 'create' }}">{{ $activeForm ? 'Cancel' : 'Create Attribute' }}</button>

                    @if ($activeForm)
                        <form wire:submit.prevent="save">
                            <div class="row mt-2">
                                <div class="col-4">
                                    <input type="text" wire:model="name" class="form-control"
                                        placeholder="Attribute Name">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="col-4">
                                    <select wire:model="categoryId" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('categoryId')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="col-4">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                    @endif

                    @if (!$activeForm)
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Attribute Name</th>
                                                <th>Category</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attributes as $attribute)
                                                <tr>
                                                    @if ($editId != $attribute->id)
                                                        <td>{{ $attribute->id }}</td>
                                                        <td>{{ $attribute->name }}</td>
                                                        <td>{{ $attribute->category->name }}</td>
                                                        <td>
                                                            <button class="btn btn-warning"
                                                                wire:click="edit({{ $attribute->id }})">Edit</button>
                                                            <button class="btn btn-danger"
                                                                wire:click="delete({{ $attribute->id }})">Delete</button>
                                                        </td>
                                                    @else
                                                        <td>{{ $attribute->id }}</td>
                                                        <td>
                                                            <input type="text" wire:model="editName"
                                                                class="form-control">
                                                        </td>
                                                        <td>
                                                            <select wire:model="editCategoryId" class="form-control">
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary"
                                                                wire:click="update({{ $attribute->id }})">Update</button>
                                                            <button class="btn btn-secondary"
                                                                wire:click="cancel">Cancel</button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
