

<!--
// IXP Manager v3 version of graph boxes to fit with the existing UI

// RRD graphs already have all the information embedded:
-->
@if( config('grapher.backends.mrtg.dbtype') === 'rrd' || $graph->classType() === "Smokeping" )
    <img width="100%" border="0" src="data:image/png;base64,<?=base64_encode( $graph->png() )?>" />
@else

    <table cellspacing="1" cellpadding="1" style="font-size: 12px;">
        <tr>
            <td colspan="8">
                <img class="img-fluid" src="data:image/png;base64,<?=base64_encode( $graph->png() )?>" />
            </td>
        </tr>
        <tr>
            <td width="10%">
            </td>
            <td width="25%" class="text-right">
                <b>Max&nbsp;&nbsp;&nbsp;&nbsp;</b>
            </td>
            <td width="25%" class="text-right">
                <b>Average&nbsp;&nbsp;&nbsp;&nbsp;</b>
            </td>
            <td width="25%" class="text-right">
                <b>Current&nbsp;&nbsp;&nbsp;&nbsp;</b>
            </td>
            <td width="15%"></td>
        </tr>
        <tr>
            <td style="color: #00cc00;"  class="text-left">
                <b>
                    In
                </b>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->maxIn(), $graph->category() )?>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->averageIn(), $graph->category() )?>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->curIn(), $graph->category() )?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="color: #0000ff;"  class="text-left">
                <b>
                    Out
                </b>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->maxOut(), $graph->category() )?>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->averageOut(), $graph->category() )?>
            </td>
            <td class="text-right">
                <?=$this->grapher()->scale( $graph->statistics()->curOut(), $graph->category() )?>
            </td>
            <td></td>
        </tr>
    </table>

@endif