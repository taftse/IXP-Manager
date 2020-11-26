<?php

namespace IXP\Models;

/*
 * Copyright (C) 2009 - 2020 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use Eloquent;

use Illuminate\Database\Eloquent\{
    Builder,
    Collection,
    Model,
    Relations\BelongsTo,
    Relations\HasMany
};

/**
 * IXP\Models\VirtualInterface
 *
 * @property int $id
 * @property int|null $custid
 * @property string|null $name
 * @property string|null $description
 * @property int|null $mtu
 * @property int|null $trunk
 * @property int|null $channelgroup
 * @property int $lag_framing
 * @property int $fastlacp
 * @property-read \IXP\Models\Customer|null $customer
 * @property-read Collection|\IXP\Models\PhysicalInterface[] $physicalInterfaces
 * @property-read int|null $physical_interfaces_count
 * @property-read Collection|\IXP\Models\VlanInterface[] $vlanInterfaces
 * @property-read int|null $vlan_interfaces_count
 * @method static Builder|VirtualInterface newModelQuery()
 * @method static Builder|VirtualInterface newQuery()
 * @method static Builder|VirtualInterface query()
 * @method static Builder|VirtualInterface whereChannelgroup($value)
 * @method static Builder|VirtualInterface whereCustid($value)
 * @method static Builder|VirtualInterface whereDescription($value)
 * @method static Builder|VirtualInterface whereFastlacp($value)
 * @method static Builder|VirtualInterface whereId($value)
 * @method static Builder|VirtualInterface whereLagFraming($value)
 * @method static Builder|VirtualInterface whereMtu($value)
 * @method static Builder|VirtualInterface whereName($value)
 * @method static Builder|VirtualInterface whereTrunk($value)
 * @mixin Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\IXP\Models\PhysicalInterface[] $physicalInterfacesConnected
 * @property-read int|null $physical_interfaces_connected_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\IXP\Models\VirtualInterface whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\IXP\Models\VirtualInterface whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\IXP\Models\VirtualInterface connected()
 * @property-read Collection|\IXP\Models\MacAddress[] $macAddresses
 * @property-read int|null $mac_addresses_count
 * @property-read Collection|\IXP\Models\SflowReceiver[] $sflowReceivers
 * @property-read int|null $sflow_receivers_count
 */
class VirtualInterface extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'virtualinterface';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'custid',
        'name',
        'description',
        'mtu',
        'trunk',
        'channelgroup',
        'lag_framing',
        'fastlacp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trunk'         => 'boolean',
        'lag_framing'   => 'boolean',
        'fastlacp'      => 'boolean',
    ];

    /**
     * Get the customer that owns the virtual interfaces.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'custid' );
    }

    /**
     * Get the VLAN interfaces for the virtual interface
     */
    public function vlanInterfaces(): HasMany
    {
        return $this->hasMany(VlanInterface::class, 'virtualinterfaceid');
    }

    /**
     * Get the physical interfaces for the virtual interface
     */
    public function physicalInterfaces(): HasMany
    {
        return $this->hasMany(PhysicalInterface::class, 'virtualinterfaceid');
    }

    /**
     * Get the mac addresses for the virtual interface
     */
    public function macAddresses(): HasMany
    {
        return $this->hasMany(MacAddress::class, 'virtualinterfaceid');
    }

    /**
     * Get the sflow receivers for the virtual interface
     */
    public function sflowReceivers(): HasMany
    {
        return $this->hasMany(SflowReceiver::class, 'virtual_interface_id');
    }

    /**
     * Get the speed of the LAG
     *
     * @param bool $connectedOnly Only consider physical interfaces with 'CONNECTED' state
     *
     * @return int
     */
    public function speed( bool $connectedOnly = true ): int
    {
        if( $connectedOnly ) {
            return $this->physicalInterfaces()->connected()->sum('speed');
        }

        return $this->physicalInterfaces()->sum('speed');
    }

    /**
     * Is this LAG graphable?
     *
     * @return bool
     */
    public function isGraphable(): bool
    {
        foreach( $this->physicalInterfaces as $pi ) {
            if( $pi->isGraphable() ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the core bundle associated to the virtual interface or false
     *
     * @return CoreBundle|bool
     */
    public function getCoreBundle()
    {
        foreach( $this->physicalInterfaces as $pi ) {
            if( $pi->coreinterface()->exists() ) {
                $ci = $pi->coreinterface;
                /** @var $ci CoreInterface */
                return $ci->coreLink()->coreBundle;
            }
        }
        return false;
    }

    /**
     * Get peerring PhysicalInterfaces
     *

     */
    public function peeringPhysicalInterface(): array
    {
        $ppis = [];
        foreach( $this->physicalInterfaces as $ppi ) {
            if( $ppi->peeringPhysicalInterface ){
                $ppis[] = $ppi->peeringPhysicalInterface;
            }
        }
        return $ppis;
    }

    /**
     * Get fanout PhysicalInterfaces
     *
     * @return array
     */
    public function fanoutPhysicalInterface(): array
    {
        $ppis = [];
        foreach( $this->physicalInterfaces as $ppi){
            if( $ppi->fanoutPhysicalInterface ) {
                $ppis[] = $ppi->peeringPhysicalInterface;
            }
        }
        return $ppis;
    }

    /**
     * Get a Switch Port of a virtual interface.
     *
     * @return SwitchPort|bool The switch port or false if no switch port.
     */
    public function switchPort()
    {
        if( $this->physicalInterfaces()->count() ){
            return $this->physicalInterfaces()->first()->switchPort;
        }
        return false;
    }

    /**
     * Get the *type* of virtual interface based on the switchport type.
     *
     * Actually returns type of the first physical interface's switchport. All
     * switchports in a virtual interface should be the same type so just
     * examining the first is sufficient to determine the *virtual interface type*.
     *
     * @see SwitchPortt::$TYPES
     *
     * @return string|bool The virtual interface type (`\Entities\SwitchPort::TYPE_XXX`) or false if no physical interfaces.
     */
    public function type()
    {
        if( $this->physicalInterfaces()->count() ) {
            return $this->physicalInterfaces()->first()->switchPort->type;
        }
        return false;
    }

    /**
     * Turn the database integer representation of the type into text as
     * defined in the SwitchPort::$TYPES array (or 'Unknown')
     * @return string
     */
    public function resolveType(): string
    {
        return SwitchPort::$TYPES[ $this->type() ] ?? 'Unknown';
    }

    /**
     * Is the type SwitchPort::TYPE_PEERING?
     *
     * @return bool
     */
    public function typePeering(): bool
    {
        return $this->type() === SwitchPort::TYPE_PEERING;
    }

    /**
     * Is the type SwitchPort::TYPE_FANOUT?
     *
     * @return bool
     */
    public function typeFanout(): bool
    {
        return $this->type() === SwitchPort::TYPE_FANOUT;
    }

    /**
     * Is the type SwitchPort::TYPE_RESELLER?
     *
     * @return bool
     */
    public function typeReseller(): bool
    {
        return $this->type() === SwitchPort::TYPE_RESELLER;
    }

    /**
     * Get the bundle name if name and channel group are set. Otherwise an empty string.
     *
     * @return string
     */
    public function bundleName(): string
    {
        if( $this->name && $this->channelgroup ) {
            return $this->name . $this->channelgroup;
        }
        return '';
    }

    /**
     * Check if the switch is the same for the physical interfaces of the virtual interface
     *
     * @return bool
     */
    public function sameSwitchForEachPI(): bool
    {
         return self::select( 'sp.switchid AS switchid' )
                 ->from( 'virtualinterface AS vi' )
                 ->leftJoin( 'physicalinterface AS pi', 'pi.virtualinterfaceid', 'vi.id' )
                 ->leftJoin( 'switchport AS sp', 'sp.id', 'pi.switchportid' )
                 ->where( 'vi.id', $this->id )->distinct()->get()->pluck( 'switchid' )->count() === 1;
    }
}