
<?php if( $t->pi && !$t->pi->statusIsConnected() ): ?>
    <a href="<?= route( 'physical-interface@edit-from-virtual-interface', [ "pi" => $t->pi->getId(), "vi" => $t->pi->getVirtualInterface()->getId() ] ) ?>">
        <?php if( $t->pi->statusIsQuarantine() ): ?>
            <span class="badge badge-warning">IN QUARANTINE</span>
        <?php elseif( $t->pi->statusIsDisabled() ): ?>
            <span class="badge badge-warning">DISABLED</span>
        <?php elseif( $t->pi->statusIsNotConnected() ): ?>
            <span class="badge badge-warning">NOT CONNECTED</span>
        <?php elseif( $t->pi->statusIsAwaitingXConnect() ): ?>
            <span class="badge badge-warning">AWAITING XCONNECT</span>
        <?php else: ?>
            <span class="badge badge-inverse">UNKNOWN STATE</span>
        <?php endif; ?>
    </a>
<?php endif; ?>
