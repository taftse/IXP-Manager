@if( $customer->typeAssociate() )
    <span class="badge badge-warning">ASSOCIATE MEMBER</span>
@elseif( $customer->typeProBono() )
    <span class="badge badge-info">PROBONO MEMBER</span>
@elseif( $customer->typeInternal() )
    <span class="badge badge-primary">INTERNAL INFRASTRUCTURE</span>
@elseif( $customer->typeFull() )
    <span class="badge badge-success">FULL MEMBER</span>
@else
    <span class="badge badge-dark">UNKNOWN MEMBER TYPE</span>
@endif