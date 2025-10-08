<tr class="category-row-level-{{ $level }}">
    <td>
        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox">
    </td>
    <td>
        @for($i = 0; $i < $level; $i++)
            <span class="category-indent">└─</span>
        @endfor
        @if($category->image)
            <img src="{{ Storage::disk('public')->url($category->image) }}" 
                 alt="{{ $category->name }}" 
                 style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
        @else
            <i class="fas fa-folder text-warning" style="margin-right: 10px;"></i>
        @endif
        {{ $category->name }}
    </td>
    <td>{{ $category->slug }}</td>
    <td>
        @if($category->parent)
            <span class="badge badge-info">{{ $category->parent->name }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>{{ $category->order }}</td>
    <td>
        @if($category->is_active)
            <span class="badge badge-success">Active</span>
        @else
            <span class="badge badge-secondary">Inactive</span>
        @endif
    </td>
    <td>
        <span class="badge badge-light">0</span>
    </td>
    <td>
        <div class="btn-group">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="btn btn-sm btn-outline-primary" 
               title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            
            <a href="{{ route('admin.categories.toggle-status', $category) }}" 
               class="btn btn-sm btn-outline-warning toggle-status" 
               title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
            </a>
            
            <form action="{{ route('admin.categories.destroy', $category) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>