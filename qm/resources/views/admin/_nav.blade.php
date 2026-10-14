<ul class="nav nav-pills mb-4 flex-wrap gap-1">
  @foreach([['admin.dashboard','Dashboard'],['admin.users.index','Users'],['admin.pharmacies.index','Pharmacies'],['admin.drugs.index','Medicines'],['admin.orders.index','Orders']] as $n)
    <li class="nav-item"><a class="nav-link {{ request()->routeIs(str_replace('.index','.*',$n[0])) ? 'active bg-primary' : 'text-dark' }}" href="{{ route($n[0]) }}">{{ $n[1] }}</a></li>
  @endforeach
</ul>
