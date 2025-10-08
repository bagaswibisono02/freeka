@extends('layouts.sidebar')

@section('title', 'Edit Category: ' . $category->name)

@section('body')
<div class="container-fluid" style="margin: 20px">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-folder"></i>
                    <div>
                        <h1>Edit Category: {{ $category->name }}</h1>
                        <div class="breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <span>/</span>
                            <a href="{{ route('admin.categories.index') }}">Categories</a>
                            <span>/</span>
                            <span>Edit</span>
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
                    <form action="{{ route('admin.categories.update', $category) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="required">Category Name</label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $category->name) }}" 
                                           required
                                           placeholder="Enter category name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              rows="4"
                                              placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
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
                                            <label for="order">Display Order</label>
                                            <input type="number" 
                                                   name="order" 
                                                   id="order" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   value="{{ old('order', $category->order) }}"
                                                   placeholder="0">
                                            <small class="form-text text-muted">Lower numbers display first</small>
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
                                                            <i class="fas fa-trash"></i> Remove current image
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="image-preview mb-3" id="image-preview" 
                                             style="{{ $category->image ? 'display: none;' : '' }}">
                                            <div class="upload-text text-center p-4 border rounded">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-1">Upload category image</p>
                                                <small class="text-muted">Recommended: 300x300px</small>
                                            </div>
                                            <img src="" alt="Preview" style="display: none; max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                                        </div>
                                        
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="form-control-file @error('image') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
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

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Category Info</h6>
                                        <p class="mb-1"><strong>Slug:</strong> {{ $category->slug }}</p>
                                        <p class="mb-1"><strong>Created:</strong> {{ $category->created_at->format('M d, Y') }}</p>
                                        <p class="mb-0"><strong>Updated:</strong> {{ $category->updated_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions pt-4 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .page-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .page-title h1 {
        font-size: 1.8rem;
        color: #6c757d;
        margin: 0;
    }
    
    .page-title i {
        font-size: 2rem;
        color: #8B5FBF;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .breadcrumb a {
        color: #8B5FBF;
        text-decoration: none;
    }
    
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    
    .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .image-preview {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .image-preview:hover {
        border-color: #8B5FBF;
    }
</style>
@endpush

@push('scripts')
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
                    
                    // Hide current image if exists
                    $('.current-image').hide();
                }
                reader.readAsDataURL(file);
            }
        });

        // Click on preview to trigger file input
        $('#image-preview').click(function() {
            $('#image').click();
        });

        // Handle remove image checkbox
        $('#remove_image').change(function() {
            if ($(this).is(':checked')) {
                $('.current-image').hide();
                $('#image-preview').show();
            } else {
                $('.current-image').show();
                $('#image-preview').hide();
            }
        });
    });
</script>
@endpush