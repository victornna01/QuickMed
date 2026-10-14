<ul class="nav nav-pills mb-4 flex-wrap gap-1">
  @foreach([['pharmacy.dashboard','Dashboard'],['pharmacy.inventory.index','Inventory'],['pharmacy.orders.index','Orders'],['pharmacy.profile.edit','Profile']] as $n)
    <li class="nav-item"><a class="nav-link {{ request()->routeIs(str_replace(['.index','.edit'],'.*',$n[0])) ? 'active bg-primary' : 'text-dark' }}" href="{{ route($n[0]) }}">{{ $n[1] }}</a></li>
  @endforeach
</ul>
