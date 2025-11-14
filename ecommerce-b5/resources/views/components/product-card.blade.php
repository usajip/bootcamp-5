<div class="card h-100">
    <div class="d-flex justify-content-center mt-3">
    @if($image)
        <img src="{{ $image }}" class="img-fluid" style="max-width: 200px" alt="{{ $name }}">
    @else
        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
    @endif
    </div>
    <div class="card-body">
        <span class="badge bg-secondary mb-2">{{ $category ?? 'Uncategorized' }}</span>
        <h5 class="card-title">{{ $name }}</h5>
        <p class="card-text">{{ \Illuminate\Support\Str::limit($description, 80) }}</p>
        <p class="card-text"><strong>Price:</strong> Rp{{ number_format($price, 0, ',', '.') }}</p>
        <a href="{{ route('product.detail', $id) }}" class="btn btn-primary">View Details</a>
    </div>
</div>