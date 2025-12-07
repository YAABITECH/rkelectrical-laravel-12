@if($items->total()>0)
<div class="d-flex justify-content-between align-items-center mt-3">
    <div><small>{{ $items->count() }} of {{ $items->total() }} {{ $items->total() > 1 ? 'records' : 'record' }}</small></div>
    <div><small>{{ $items->currentPage() }} of {{ $items->lastPage() }} {{ $items->lastPage() > 1 ? 'pages' : 'page' }}</small></div>
</div>
@endif

@if ($items->currentPage() != 1 || $items->total() > $items->perPage())
<nav aria-label="Page navigation" class="pt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $paginator->currentPage() == 1 ? ' disabled' : '' }}" onclick="xfilter_page(1)" aria-label="First" data-bs-toggle="tooltip" data-bs-placement="top" title="Go to First Page">
                <i class="fas fa-angle-double-left"></i>
            </button>
        </li>
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $paginator->currentPage() == 1 ? ' disabled' : '' }}" onclick="xfilter_page({{ $paginator->currentPage() - 1 }})" aria-label="Previous" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous Page">
                <i class="fas fa-chevron-left"></i>
            </button>
        </li>
        <li class="page-item mx-1 active" aria-current="page">
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Current Page"><button class="btn btn-primary shadow"data-bs-toggle="modal" data-bs-target="#xfilter_model">{{ $paginator->currentPage() }}</button></span>
        </li>
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}" onclick="xfilter_page({{ $paginator->currentPage() + 1 }})" aria-label="Next" data-bs-toggle="tooltip" data-bs-placement="top" title="Next Page">
                <i class="fas fa-chevron-right"></i>
            </button>
        </li>
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}" onclick="xfilter_page({{ $paginator->lastPage() }})" aria-label="Last" data-bs-toggle="tooltip" data-bs-placement="top" title="Go to Last Page">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </li>
    </ul>
</nav>
@endif