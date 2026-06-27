@if(\Auth::user()->belongstouser->unreadNotifications?->count())
	@foreach(\Auth::user()->belongstouser->unreadNotifications as $v)
		<li>
			<a class="dropdown-item" href="{{ $v->data['link'] }}">
				<i class="fa-regular fa-comment"></i>
				{{ $v->data['title'] }}
			</a>
		</li>
	@endforeach
@endif
<a class="dropdown-item" href="#"><i class="fa-regular fa-comment"></i>Notifications (MarkAsRead in pdf-layout.blade)</a>
