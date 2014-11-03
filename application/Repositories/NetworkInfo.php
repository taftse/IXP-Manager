<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * NetworkInfo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NetworkInfo extends EntityRepository
{
    /**
     * Returns an array of the network information indexed by Vlan.id with
     * sub-arrays indexed by protocol.
     *
     * For example (where `x` is the vlan ID):
     *
     *     [x] => array(2) {
     *       [4] => array(9) {
     *         ["id"] => string(1) "1"
     *           ["protocol"] => string(1) "4"
     *           ["network"] => string(13) "193.242.111.0"
     *           ["masklen"] => string(2) "25"
     *           ["rs1address"] => string(13) "193.242.111.8"
     *           ["rs2address"] => string(13) "193.242.111.9"
     *           ["dnsfile"] => string(44) "/opt/bind/zones/reverse-vlan-10-ipv4.include"
     *           ["Vlan"] => array(5) {
     *             ["id"] => string(1) "2"
     *             ["name"] => string(15) "Peering VLAN #1"
     *             ["number"] => string(2) "10"
     *             ["rcvrfname"] => string(0) ""
     *             ["notes"] => string(0) ""
     *           }
     *       }
     *       [6] => array(9) {
     *         ["id"] => string(1) "2"
     *           ["vlanid"] => string(1) "2"
     *           ["protocol"] => string(1) "6"
     *           ["network"] => string(16) "2001:07F8:0018::"
     *           ["masklen"] => string(2) "64"
     *           ["rs1address"] => string(14) "2001:7f8:18::8"
     *           ["rs2address"] => string(14) "2001:7f8:18::9"
     *           ["dnsfile"] => string(44) "/opt/bind/zones/reverse-vlan-10-ipv6.include"
     *           ["Vlan"] => array(5) {
     *             ["id"] => string(1) "2"
     *             ["name"] => string(15) "Peering VLAN #1"
     *             ["number"] => string(2) "10"
     *             ["rcvrfname"] => string(0) ""
     *             ["notes"] => string(0) ""
     *           }
     *         }
     *     }
     *
     * @return array As described above
     */
    
    public function asVlanProtoArray()
    {
        $networkInfo = $this->getEntityManager()->createQuery(
                "SELECT n, v
                FROM \\Entities\\NetworkInfo n
                LEFT JOIN n.Vlan v"
            )->useResultCache( true, 3600 )
            ->getArrayResult();

        $data = array();
        foreach( $networkInfo as $ni )
        {
            $data[ $ni['Vlan']['id'] ][ $ni['protocol'] ] = $ni;
        }
        
        return $data;
    }

    public function asVlanEuroIXExportArray()
    {
        $networkInfo = $this->getEntityManager()->createQuery(
                "SELECT n, v
                FROM \\Entities\\NetworkInfo n
                LEFT JOIN n.Vlan v"
            )->useResultCache( true, 3600 )
            ->getArrayResult();

        $data = array();
        foreach( $networkInfo as $ni )
        {
            $data [$ni['Vlan']['id'] ]['name'] = $ni['Vlan']['name'];
            $data [$ni['Vlan']['id'] ][ 'ipv'.$ni['protocol']] ['prefix'] = $ni[ 'network' ];
            $data [$ni['Vlan']['id'] ][ 'ipv'.$ni['protocol']] ['mask_length'] = $ni[ 'masklen' ];
        }
        
        return $data;
    }

}
