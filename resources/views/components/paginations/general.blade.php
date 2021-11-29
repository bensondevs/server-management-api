@if (count($collection))
	@php
		$currentLink = currentLink();
		$parameters = request()->all();

		$links = generatePaginationLinks($currentLink, $parameters, $collection->lastPage());
	@endphp

	<ul class="pagination justify-content-center m-0">
		@if ($links['prev_link'] !== '#')
			<li class="page-item">
				<a class="page-link" href="{{ $links['prev_link'] }}">
					<i class="fas fa-arrow-circle-left"></i>
				</a>
			</li>
	    @endif

	    @foreach ($links['urls'] as $pageNum => $url)
	    	@php
	    		$isThisPage = ($collection->currentPage() == $pageNum);
	    	@endphp
	    	<li class="page-item {{ $isThisPage ? 'active' : null }}">
	    		<a class="page-link" href="{{ $isThisPage ? '' : $url }}">
			    	{{ $pageNum }}
			    </a>
	    	</li>
		@endforeach

		@if ($links['next_link'] !== '#')
			<li class="page-item">
				<a class="page-link" href="{{ $links['next_link'] }}">
					<i class="fas fa-arrow-circle-right"></i>
				</a>
			</li>
	    @endif
	</ul>
@endif