<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * LeadFieldValueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LeadFieldValueRepository extends CommonRepository
{

    /**
     * Gets a list of unique values from fields for autocompletes
     * @param        $field
     * @param string $search
     * @param int    $limit
     * @param int    $start
     * @return array
     */
    public function getValueList($field, $search = '', $limit = 10, $start = 0)
    {
        $q = $this->_em->createQueryBuilder()
            ->select('v.value')
            ->distinct()
            ->from('MauticLeadBundle:LeadFieldValue', 'v')
            ->leftJoin('MauticLeadBundle:LeadField', 'f', 'WITH', 'v.field = f.id')
            ->where('f.alias = :field')
            ->andWhere("v.value != ''")
            ->andWhere("v.value IS NOT NULL")
            ->setParameter('field', $field);

        if (!empty($search)) {
            $q->andWhere('v.value LIKE :search')
                ->setParameter('search', "{$search}%");
        }

        $q->orderBy('v.value');

        if (!empty($limit)) {
            $q->setFirstResult($start)
                ->setMaxResults($limit);
        }

        $results = $q->getQuery()->getArrayResult();
        return $results;
    }
}
