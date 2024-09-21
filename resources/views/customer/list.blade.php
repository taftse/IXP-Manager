@extends( 'layouts/ixpv4' )


@section( 'page-header-preamble' )
    {{ ucfirst( config( 'ixp_fe.lang.customer.many' ) ) }} / List
@endsection

@section( 'page-header-postamble' )
    <div class="btn-group btn-group-sm" role="group">
        <a id="btn-filter-options" class="btn btn-white" href="<?= route( 'customer@list', [ 'current-only' => $showCurrentOnly ? '0' : '1' ] ) ?>">
            <?= $showCurrentOnly ? ( "Show All " . ucfirst( config( 'ixp_fe.lang.customer.many' ) ) ) : ( "Show Current " . ucfirst( config( 'ixp_fe.lang.customer.many' ) ) ) ?>
        </a>

        <div class="btn-group btn-group-sm">
            <button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= $state ? 'State: ' . \IXP\Models\Customer::$CUST_STATUS_TEXT[ $state ] : "Limit to state..." ?>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item  <?= $state ?: "active" ?>" href="<?= route( 'customer@list', [ 'state' => 0 ] ) ?>">
                    All States
                </a>

                <div class="dropdown-divider"></div>

                <?php foreach( \IXP\Models\Customer::$CUST_STATUS_TEXT as $state => $text ): ?>
                    <a class="dropdown-item <?= (int)$state !== $state ?: "active" ?>" href="<?= route( 'customer@list', [ 'state' => $state ] ) ?>">
                        <?= $text ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="btn-group btn-group-sm">
            <button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= $type ? 'Type: ' . \IXP\Models\Customer::$CUST_TYPES_TEXT[ $type ] : "Limit to type..." ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item <?= $type ?: "active" ?>" href="<?= route( 'customer@list', [ 'type' => 0 ] ) ?>">
                    All Types
                </a>

                <div class="dropdown-divider"></div>
                <?php foreach( \IXP\Models\Customer::$CUST_TYPES_TEXT as $type => $text ): ?>
                    <a class="dropdown-item <?= (int)$type !== $type ?: "active" ?>" href="<?= route( 'customer@list', [ 'type' => $type ] ) ?>">
                        <?= $text ?>
                    </a>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="btn-group btn-group-sm">
            <button class="btn btn-white btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$tag ? 'Tag: ' . $tags[ $tag ][ 'display_as' ]  : "Limit to tag..." }}
            </button>

            <ul class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item <?= $tag ?: "active" ?>" href="<?= route( 'customer@list', [ 'tag' => 0 ] ) ?>">
                    All Tags
                </a>

                <div class="dropdown-divider"></div>
                <?php foreach( $tags as $tag ): ?>
                    <a class="dropdown-item <?= $tag !== $tag[ 'id' ] ?: "active" ?>"href="<?= route( 'customer@list' , [ 'tag' => $tag[ 'id' ] ] ) ?>">
                        {{$tag[ 'display_as' ] }}
                    </a>
                <?php endforeach; ?>
            </ul>
        </div>

        <a class="btn btn-white" href="<?= route( 'customer@create' ) ?>">
            <span class="fa fa-plus"></span>
        </a>
    </div>
@endsection

@section('content')
    <?php // $alerts() ?>
        <table id='customer-list' class="table collapse table-striped no-wrap responsive tw-shadow-md w-100" >
            <thead class="thead-dark">
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        AS
                    </th>
                    <th>
                        Peering Policy
                    </th>
                    <th>
                        Reseller
                    </th>
                    <th>
                        Type
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Joined
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            <thead>
            <tbody>
            @foreach( $customers as $customer )

                <tr>
                    <td>
                        <a href="<?= route( "customer@overview" , [ 'cust' => $customer->id ] ) ?>">
                            {{$customer->name }}
                        </a>
                    </td>
                    <td>
                        @if( $customer->autsys )
                            <a href="#">
                               {{$customer->autsys}}
                            </a>
                        @endif
                    </td>
                    <td>
                        {{$customer->peeringpolicy}}
                    </td>
                    <td>
                        <?= $customer->reseller ? "Yes" : "No" ?>
                    </td>
                    <td>
                        @include( 'customer/list-type',   [ 'customer' => $customer ] )
                    </td>
                    <td>
                        @include( 'customer/list-status', [ 'customer' => $customer ] )
                    </td>
                    <td>
                        <?= \Carbon\Carbon::instance( $customer->datejoin )->format( 'Y-m-d' ) ?>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a class="btn btn-white" href="<?= route( "customer@overview" , [ 'cust' => $customer->id ] ) ?>" title="Overview">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a class="btn btn-white" href="<?= route ( "customer@delete-recap", [ "cust" => $customer->id ] )   ?>" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tbody>
        </table>
@endsection

@section( 'scripts' )
    <script>
        $(document).ready( function() {
            $('#customer-list').dataTable( {
                responsive: true,
                stateSave: true,
                stateDuration : DATATABLE_STATE_DURATION,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],
            } ).show();
        });
    </script>
@endsection