<nav aria-label="Page navigation" class="pt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $currentpage == 1 ? ' disabled' : '' }}"
                onclick="gotopage(1)" aria-label="First">
                <i class="fas fa-angle-double-left"></i>
            </button>
        </li>
        <li class="page-item mx-1">
            <button class="btn btn-outline-primary border-light shadow{{ $currentpage == 1 ? ' disabled' : '' }}"
                onclick="gotopage({{ $currentpage - 1 }})" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>
        </li>
        <li class="page-item mx-1 active" aria-current="page">
            <button class="btn btn-primary shadow">{{ $currentpage }}</button>
        </li>
        <li class="page-item mx-1">
            <button
                class="btn btn-outline-primary border-light shadow{{ $currentpage == $totalPages ? ' disabled' : '' }}"
                onclick="gotopage({{ $currentpage + 1 }})" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </li>
        <li class="page-item mx-1">
            <button
                class="btn btn-outline-primary border-light shadow{{ $currentpage == $totalPages ? ' disabled' : '' }}"
                onclick="gotopage({{ $totalPages }})" aria-label="Last">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </li>
    </ul>
</nav>
