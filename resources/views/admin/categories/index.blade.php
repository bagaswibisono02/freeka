@extends('layouts.sidebar')

{{-- @section('title', 'Categories Management') --}}

@section('body')
<div class="" style="margin: 10px">
    <div class="row">
        <div class="col-12">

            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="page-title d-flex align-items-center">
                    <i class="fas fa-folder mr-3"></i>
                    <div>
                        <h1>Categories Management</h1>
                        <div class="breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <span>/</span>
                            <span>Categories</span>
                        </div>
                    </div>
                </div>
                <div class="page-actions">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Category
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Categories Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title mb-0">All Categories</h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <form id="bulk-action-form" method="POST"
                                  action="{{ route('admin.categories.bulk-action') }}">
                                @csrf
                                <div class="input-group" style="max-width: 300px; margin-left: auto;">
                                    <select name="action" class="form-control" required>
                                        <option value="">Bulk Actions</option>
                                        <option value="activate">Activate</option>
                                        <option value="deactivate">Deactivate</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-primary" id="apply-bulk-action">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    @if ($mainCategories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="categories-table">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Parent</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Products</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mainCategories as $category)
                                        @include('admin.categories.partials.category-row', [
                                            'category' => $category,
                                            'level' => 0,
                                        ])

                                        @if (isset($subCategories[$category->id]))
                                            @foreach ($subCategories[$category->id] as $subCategory)
                                                @include('admin.categories.partials.category-row', [
                                                    'category' => $subCategory,
                                                    'level' => 1,
                                                ])
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4>No categories found</h4>
                            <p class="text-muted">Get started by creating your first category.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            // Select all checkbox
            $('#select-all').change(function() {
                $('.category-checkbox').prop('checked', this.checked);
            });

            // Bulk action form submission
            $('#bulk-action-form').on('submit', function(e) {
                const selectedCategories = $('.category-checkbox:checked');

                if (selectedCategories.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one category.');
                    return false;
                }

                if ($(this).find('select[name="action"]').val() === '') {
                    e.preventDefault();
                    alert('Please select an action.');
                    return false;
                }

                // Add selected category IDs to form
                selectedCategories.each(function() {
                    $('#bulk-action-form').append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'categories[]')
                        .val($(this).val())
                    );
                });
            });

            // Toggle status
            $('.toggle-status').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                if (confirm('Are you sure you want to change the status of this category?')) {
                    window.location.href = url;
                }
            });

            // Delete confirmation
            $('.delete-category').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                if (confirm(
                    'Are you sure you want to delete this category? This action cannot be undone.')) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection


@push('styles')
    <style>
        .category-row-level-1 {
            background-color: #f8f9fa;
        }

        .category-row-level-1 td:first-child {
            padding-left: 40px !important;
        }

        .category-indent {
            display: inline-block;
            width: 20px;
        }
    </style>
@endpush

