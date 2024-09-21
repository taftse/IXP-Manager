@if( $customer->dateleave && \Carbon\Carbon::instance( $customer->dateleave )->format( 'Y-m-d' ) !== '0000-00-00' && \Carbon\Carbon::instance( $customer->dateleave )->format( 'Y-m-d' ) !== '-0001-11-30' )
    <span class="badge badge-danger">CLOSED</span>
@else
    @if( $customer->statusSuspended() )
        <span class="badge badge-warning">SUSPENDED</span>
    @elseif( $customer->statusNormal() || ( $customer->typeAssociate() && $customer->statusNotConnected() ) )
        <span class="badge badge-success">NORMAL</span>
    @elseif( $customer->statusNotConnected() )
        <span class="badge badge-warning">NOT CONNECTED</span>
    @else
        <span class="badge-dark">{*$cconf.mapper[$row.$col]*}</span>
    @endif
@endif