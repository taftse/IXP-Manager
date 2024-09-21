@extends('layouts/ixpv4')

@section( 'page-header-preamble' )
    Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="row">
                <div class="col-12 col-xl-6">
                    <div>
                        <h4>
                          Overall {{ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Numbers
                        </h4>

                        <table class="table table-sm table-striped tw-shadow-md tw-rounded-sm table-hover tw-mt-6">
                            <thead>
                                <tr>
                                    <th>
                                        {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Type
                                    </th>
                                    <th class="tw-text-right">
                                        Count
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tw-text-sm">
                              @foreach($stats[ "types" ] as $type )
                                  <tr>
                                      <td>
                                          {{ \IXP\Models\Customer::givenType( $type[ 'ctype' ] ) }}
                                      </td>
                                      <td class="tw-text-right">
                                          <a href="{{ route( "customer@list" ) . '?type=' . $type[ 'ctype' ] }}">
                                              {{ $type[ 'cnt' ] }}
                                          </a>
                                      </td>
                                  </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(count( $stats[ "percentByVlan" ] ) > 1 )
                        <div class="tw-my-12">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.many' ) ) }} by VLAN
                            </h4>

                            <p>
                                We count full and pro-bono members with at least one connected physical interface.
                            </p>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            VLAN
                                        </th>
                                        <th class="tw-text-right">
                                            {{ ucfirst( config( 'ixp_fe.lang.customer.many' ) ) }}
                                        </th>
                                        <th class="tw-text-right">
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                    @foreach( $stats[ "percentByVlan" ] as $stats  )
                                        <tr>
                                            <td>
                                                {{$stats[ 'vlanname' ]}}
                                            </td>
                                            <td class="tw-text-right">
                                                <a href="{{route( "switch@configuration", [ "vlan" => $stats[ 'vlanid' ] ] )}}">
                                                    {{$stats[ 'count' ]}}
                                                </a>
                                            </td>
                                            <td class="tw-text-right">
                                                {{ round( $stats[ 'percent' ] )}}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if( count( $stats[ "custsByLocation" ] ) )
                        <div class="tw-my-12">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.many' ) ) }} by Location
                            </h4>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead>
                                  <tr>
                                      <th>
                                          Location
                                      </th>
                                      <th class="tw-text-right">
                                          {{ ucfirst( config( 'ixp_fe.lang.customer.many' ) ) }}
                                      </th>
                                  </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                  @foreach( $stats[ "custsByLocation" ] as $name => $loc  )
                                      <tr>
                                          <td>
                                              {{ $loc[ 'name' ] }}
                                          </td>
                                          <td class="tw-text-right">
                                              <a href="{{ route( "switch@configuration", [ "location" => $loc[ 'id' ] ] )}} ">
                                                  {{$loc[ 'count' ]}}
                                              </a>
                                          </td>
                                      </tr>
                                  @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if( count( $stats[ "byLocation" ] ) )
                        <div class="tw-my-12">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Ports by Location
                            </h4>
                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead class="tw-text-sm">
                                    <tr>
                                        <th>
                                            Location
                                        </th>
                                       @foreach( $stats[ "speeds" ] as $speed => $count )
                                            <th class="tw-text-right">
                                                {{$t->scaleBits( $speed * 1000000, 0 ) }}
                                            </th>
                                        @endforeach

                                        <th class="tw-text-right">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                    <?php $colcount = 0 ?>
                                    @foreach( $stats[ "byLocation"] as $location => $speed )
                                        <?php $rowcount = 0 ?>
                                        <tr>
                                            <td>
                                                {{ $location }}
                                            </td>
                                            @foreach( $stats[ "speeds"] as $s => $c )
                                                <td class="tw-text-right">
                                                    @if( isset( $speed[ $s ] ) )
                                                        <a href="{{ route( "switch@configuration", [ "location" => $speed[ 'id' ], "speed" => $s ] )}}">
                                                            {{$speed[ $s ]}}
                                                        </a>
                                                        <?php $rowcount += $speed[ $s ] ?>
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="tw-text-right">
                                                <b>
                                                    {{$rowcount}}
                                                </b>
                                            </td>
                                        </tr>
                                        {{ $colcount = $rowcount + $colcount }}
                                    @endforeach
                                    <tr>
                                        <td>
                                            <b>Totals</b>
                                        </td>
                                        @foreach( $tstats[ "speeds"] as $s => $c )
                                            <td class="tw-text-right">
                                                <b>
                                                    {{$c}}
                                                </b>
                                            </td>
                                        @endforeach
                                        <td class="tw-text-right">
                                            <b>
                                                {{$colcount}}
                                            </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if( count( $stats[ "rateLimitedPorts" ] ) )
                                <p>
                                    <i>These statistics take account of rate limited / partial speed ports. See <a href="{{route('admin@dashboard')}}#rate_limited_details">
                                            here for details</a>.
                                    </i>
                                </p>
                            @endif



                        </div>
                    @endif

                    @if( count( $stats[ "byLan" ] ) )
                        <div class="tw-my-10">
                            <h4  class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Ports by Infrastructure
                            </h4>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Infrastructure
                                        </th>
                                        @foreach( $stats[ "speeds"] as $speed => $count )
                                            <th class="tw-text-right">
                                                {{ $t->scaleBits( $speed * 1000000, 0 ) }}
                                            </th>
                                        @endforeach
                                        <th class="tw-text-right">
                                            Total
                                        </th>
                                        <th class="tw-text-right">
                                            Connected<br>
                                            Capacity
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                    <?php $colcount = 0 ?>
                                    @foreach( $stats[ "byLan"] as  $inf => $spds )
                                        <?php $rowcount = $rowcap = 0 ?>
                                        <tr>
                                            <td>
                                                {{  $inf }}
                                            </td>
                                            @foreach( $stats[ "speeds"] as $speed => $count )
                                                <td class="tw-text-right">
                                                    @if( isset( $spds[ $speed ] ) )
                                                        <a href="{{route( " switch@configuration", [ "infra" => $spds['id' ], "speed" => $speed ] )}}">
                                                            {{$spds[ $speed ]}}
                                                        </a>
                                                        <?php $rowcount += $spds[ $speed ] ?>
                                                        <?php $rowcap = $rowcap + $spds[ $speed ] * $speed ?>
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="tw-text-right">
                                                {{$rowcount }}
                                            </td>
                                            <td class="tw-text-right">
                                                {{ $t->scaleBits( $rowcap * 1000000, 2 ) }}
                                            </td>
                                        </tr>
                                        <?php $colcount = $rowcount + $colcount ?>
                                    @endforeach

                                    <tr>
                                        <td>
                                            <b>
                                                Totals
                                            </b>
                                        </td>
                                        <?php $rowcap = 0 ?>

                                        @foreach( $stats[ "speeds"] as $k => $i )
                                            <?php $rowcap = $rowcap + $i * $k ?>
                                            <td class="tw-text-right">
                                                <b>
                                                    {{$i}}
                                                </b>
                                            </td>
                                        @endforeach

                                        <td class="tw-text-right">
                                            <b>{{$colcount}}</b>
                                        </td>
                                        <td class="tw-text-right">
                                            <b>
                                                {{ $t->scaleBits( $rowcap * 1000000, 3 ) }}
                                            </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if( count( $stats[ "usage" ] ) )
                        <div class="tw-my-10">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Route Server Usage by VLAN
                            </h4>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Infrastructure
                                        </th>
                                        <th class="tw-text-right">
                                            RS Clients
                                        </th>
                                        <th class="tw-text-right">
                                            Total
                                        </th>
                                        <th class="tw-text-right">
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                    <?php $rsclients = $total = 0 ?>
                                    @foreach( $stats[ "usage"] as  $vlan )
                                        <tr>
                                            <td>
                                                {{ $vlan[ 'vlanname' ]}}
                                            </td>
                                            <td class="tw-text-right">
                                                {{$rsclients += $vlan[ 'rsclient_count' ] }}
                                                <a href="{{route( " switch@configuration", [ "vlan" => $vlan[ 'vlanid' ],"rs-client" => 1 ] )}}">
                                                    {{$vlan[ 'rsclient_count' ]}}
                                                </a>
                                            </td>
                                            <td class="tw-text-right">
                                                {{$total += $vlan[ 'overall_count' ]}}
                                                {{$vlan[ 'overall_count' ]}}
                                            </td>
                                            <td class="tw-text-right">
                                                {{round( ( 100.0 * $vlan[ 'rsclient_count' ] ) / $vlan[ 'overall_count' ])}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot class="tw-text-sm">
                                    <tr>
                                        <td>
                                            <b>Totals</b>
                                        </td>
                                        <td class="tw-text-right">
                                            <b>
                                                {{ $rsclients }}
                                            </b>
                                        </td>
                                        <td class="tw-text-right">
                                            <b>
                                                {{ $total }}
                                            </b>
                                        </td>
                                        <td class="tw-text-right">
                                            <b>
                                                {{ $total ? round( (100.0 * $rsclients ) / $total ) : 0 }}%
                                            </b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                    @if( count( $stats[ "usage" ] ) )
                        <div class="tw-my-10">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} IPv6 Usage by VLAN
                            </h4>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Infrastructure
                                        </th>
                                        <th class="tw-text-right">
                                            IPv6 Enabled
                                        </th>
                                        <th class="tw-text-right">
                                            Total
                                        </th>
                                        <th class="tw-text-right">
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tw-text-sm">
                                    <?php $ipv6 = $total = 0 ?>
                                    @foreach( $stats[ "usage"] as  $vlan )
                                        <tr>
                                            <td>
                                                {{ $vlan[ 'vlanname' ]}}
                                            </td>
                                            <td class="tw-text-right">
                                                <?php $ipv6 += $vlan[ 'ipv6_count' ] ?>
                                                <a href="{{ route( "switch@configuration", [ "vlan" => $vlan[ 'vlanid' ], "ipv6-enabled" => 1 ] ) }}">
                                                    {{ $vlan[ 'ipv6_count' ] }}
                                                </a>
                                            </td>
                                            <td class="tw-text-right">
                                                <?php $total += $vlan[ 'overall_count' ] ?>
                                                {{ $vlan[ 'overall_count' ] }}
                                            </td>
                                            <td class="tw-text-right">
                                                {{ round( (100.0 *  $vlan[ 'ipv6_count' ] ) / $vlan[ 'overall_count' ] ) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tw-text-sm">
                                    <tr>
                                        <td>
                                            <b>
                                                Totals
                                            </b>
                                        </td>
                                        <td class="text-right">
                                            <b>
                                                {{ $ipv6 }}
                                            </b>
                                        </td>
                                        <td class="text-right">
                                            <b>
                                                {{ $total }}
                                            </b>
                                        </td>
                                        <td class="text-right">
                                            <b>
                                                {{ $total ? round( (100.0 * $ipv6 ) / $total ) : 0 }}%
                                            </b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                    @if( count( $stats[ "byLocation" ] ) )
                        <div class="tw-my-12">
                            <h4 class="tw-mb-6">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Ports by Rack
                            </h4>


                        @foreach( $stats[ "byLocation"] as $location => $locationDetails )

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead class="tw-text-sm">
                                <tr>
                                    <th>
                                        {{ $location }}
                                    </th>
                                    @foreach( $stats[ "speeds" ] as $speed => $count )
                                        <th class="tw-text-right">
                                            {{ $t->scaleBits( $speed * 1000000, 0 ) }}
                                        </th>
                                    @endforeach

                                    <th class="tw-text-right">
                                        Total
                                    </th>
                                </tr>
                                </thead>

                                <tbody class="tw-text-sm">
                                <?php $colcount = 0 ?>
                                @foreach( $locationDetails['cabinets'] as $cabinet => $speed )
                                    <?php $rowcount = 0 ?>
                                    <tr>
                                        <td>
                                            {{$cabinet}}
                                        </td>
                                        @foreach( $stats[ "speeds"] as $s => $c )
                                            <td class="tw-text-right">
                                                @if( isset( $speed[ $s ] ) )
                                                    {{ $speed[ $s ] }}
                                                    <?php $rowcount += $speed[ $s ] ?>
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="tw-text-right">
                                            <b>
                                                {{ $rowcount }}
                                            </b>
                                        </td>
                                    </tr>
                                    <?php $colcount = $rowcount + $colcount ?>
                                @endforeach
                                <tr>
                                    <td>
                                        <b>Totals</b>
                                    </td>
                                    @foreach( $stats[ "speeds"] as $s => $c )
                                        <td class="tw-text-right">
                                            <a href="{{ route( "switch@configuration", [ "location" => $locationDetails[ 'id' ], "speed" => $s ] ) }}">
                                                {{ $locationDetails[$s] ?? 0 }}
                                            </a>
                                        </td>
                                    @endforeach
                                    <td class="tw-text-right">
                                        <b>
                                            {{ $colcount }}
                                        </b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endforeach
                        </div>
                    @endif

                    @if( count( $stats[ "rateLimitedPorts" ] ) )
                        <div class="tw-my-12">
                            <h4 class="tw-mb-6" id="rate_limited_details">
                                {{ ucfirst( config( 'ixp_fe.lang.customer.one' ) ) }} Rate Limited / Partial Speed Ports
                            </h4>

                            <p>
                                The above statistics take account of the following rate limited ports. By <i>take account of</i> we
                                mean that if a 10Gb port is rate limited as 2Gb then the above statistics reflect it as 2 x 1Gb
                                ports and the 10Gb is ignored.
                            </p>

                            <table class="table table-sm table-hover table-striped tw-shadow-md tw-rounded-sm">
                                <thead class="tw-text-sm">
                                <tr>
                                    <th>
                                        Physical Port Speed
                                    </th>
                                    <th class="tw-text-sm">
                                        Rate Limit
                                    </th>
                                    <th class="tw-text-sm">
                                        Account For As
                                    </th>
                                </tr>
                                </thead>

                                <tbody class="tw-text-sm">
                                @foreach( $stats[ "rateLimitedPorts"] as $rateLimitedPorts => $rlp )
                                    <tr>
                                        <td>
                                            {{ $t->scaleSpeed( $rlp['physint']) }}
                                        </td>
                                        <td>
                                            {{ $t->scaleSpeed( $rlp['numports'] * $rlp['rlspeed'] ) }}
                                        </td>
                                        <td>
                                            {{ $rlp['numports'] }} x {{ $t->scaleSpeed( $rlp['rlspeed']) }}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-12 col-xl-6">
                    <div class="tw-mb-6">
                        @foreach( $graph_periods as $period => $desc )
                            <a class="tw-mr-6 hover:tw-no-underline" href="{{route('admin@dashboard')}}?graph_period={{$period}}">
                                <span class="btn btn-white tw-rounded-full {{ $graph_period === $period ? 'tw-font-semibold tw-text-grey-darkest' : 'tw-text-grey-dark' }} mr-2">
                                    {{$desc}}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    @if( count( $graphs ) )
                        @foreach( $graphs as $id => $graph )
                            <div class="card mb-4">
                                <div class="card-header ">
                                    <h5 class="d-flex mb-0">
                                        <span class="mr-auto">
                                            {{ $graph->name() }} Aggregate Traffic
                                        </span>

                                        <a class="btn btn-white btn-sm"
                                            @if( $id === 'ixp' )
                                                href="{{route('statistics@ixp') }}"
                                            @else
                                                href="{{route('statistics@infrastructure', [ 'infra' => $id ] ) }}"
                                            @endif
                                        >
                                            <i class="fa fa-search"></i></a>
                                    </h5>
                                </div>

                                <div class="card-body">
                                    {!! $graph->renderer()->boxLegacy() !!}
                                </div>
                            </div>
                        @endforeach
                    @else

                    <div class="card mb-4">
                        <div class="card-header ">
                            <h3 class="mb-0">
                                Configure Your Aggregate Graph(s)
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>
                                Aggregate graphs have not been configured.
                                Please see <a href="https://github.com/inex/IXP-Manager/wiki/MRTG---Traffic-Graphs">this documentation</a>.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tw-bg-blue-100 tw-border-l-4 tw-border-blue-500 tw-text-blue-700 tw-p-4 tw-shadow-md" role="alert">
                Dashboard statistics are cached for 1 hour (graphs for 5mins). These dashboard statistics were last cached
                {{ $stats['cached_at']->diffForHumans() }}.
                <a href="{{ route('admin@dashboard') }}?graph_period={{ $graph_period }}&refresh_cache=1">Click
                    here</a> to refresh the cache now.
            </div>
        </div>
    </div>
@endsection