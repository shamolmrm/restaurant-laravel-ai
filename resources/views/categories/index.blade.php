@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Categories</h4>
        <p class="text-muted small mb-0">Manage menu categories</p>
    </div>
    @can('create categories')
    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
    @endcan
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>#</th><th>Image</th><th>Name</th><th>Items</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" width="40" height="40" style="border-radius:8px;object-fit:cover">
                            @else
                            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center"><i class="bi bi-tag text-muted"></i></div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $cat->name }}</div>
                            <div class="text-muted small">{{ Str::limit($cat->description,50) }}</div>
                        </td>
                        <td><span class="badge bg-primary">{{ $cat->menu_items_count }}</span></td>
                        <td>{{ $cat->sort_order }}</td>
                        <td>
                            <span class="badge {{ $cat->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $cat->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('edit categories')
                                <a href="{{ route('categories.edit',$cat) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('delete categories')
                                <form method="POST" action="{{ route('categories.destroy',$cat) }}" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No categories found. <a href="{{ route('categories.create') }}">Add one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer">
    <span>Showing {{ $categories->firstItem() }}-{{ $categories->lastItem() }} of {{ $categories->total() }}</span>
    {{ $categories->links() }}
</div>
    @endif
</div>
@endsection

