@extends('layouts.sidebar')

@section('title', $category->exists ? 'Edit Category' : 'Create Category')

@section('body')
<div class="container-fluid" style="padding: 20px">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-folder"></i>
                    <div>
                        <h1>{{ $category->exists ? 'Edit Category' : 'Create New Category' }}</h1>
                        <div class="breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <span>/</span>
                            <a href="{{ route('admin.categories.index') }}">Categories</a>
                            <span>/</span>
                            <span>{{ $category->exists ? 'Edit' : 'Create' }}</span>
                        </div>
                    </div>
                </div>
                <div class="page-actions">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if($category->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="required">Category Name</label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $category->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              rows="4">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_id">Parent Category</label>
                                            <select name="parent_id" 
                                                    id="parent_id" 
                                                    class="form-control @error('parent_id') is-invalid @enderror">
                                                <option value="">No Parent (Main Category)</option>
                                                @foreach($parentCategories as $parent)
                                                    <option value="{{ $parent->id }}" 
                                                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                        {{ $parent->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order">Order</label>
                                            <input type="number" 
                                                   name="order" 
                                                   id="order" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   value="{{ old('order', $category->order) }}">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">Category Image</label>
                                    <div class="image-upload-container">
                                        @if($category->image)
                                            <div class="current-image mb-3">
                                                <img src="{{ Storage::disk('public')->url($category->image) }}" 
                                                     alt="{{ $category->name }}" 
                                                     style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                                                <div class="mt-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               name="remove_image" 
                                                               id="remove_image" 
                                                               value="1" 
                                                               class="form-check-input">
                                                        <label for="remove_image" class="form-check-label text-danger">
                                                            Remove current image
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="image-preview mb-3" id="image-preview" 
                                             style="{{ $category->image ? 'display: none;' : '' }}">
                                            <div class="upload-text text-center p-4 border rounded">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">Upload category image</p>
                                            </div>
                                            <img src="" alt="Preview" style="display: none; max-width: 100%;">
                                        </div>
                                        
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="form-control-file @error('image') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1" 
                                               class="form-check-input" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">
                                            Active Category
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ $category->exists ? 'Update Category' : 'Create Category' }}
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Image preview
        $('#image').change(function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').show();
                    $('#image-preview img').attr('src', e.target.result).show();
                    $('#image-preview .upload-text').hide();
                }
                reader.readAsDataURL(file);
            }
        });

        // Auto-generate slug from name
        $('#name').on('blur', function() {
            // This would typically make an AJAX call to generate a unique slug
            // For now, we'll just show a message
            if ($(this).val().trim() !== '') {
                console.log('Slug would be generated from:', $(this).val());
            }
        });
    });
</script>

@endsection


