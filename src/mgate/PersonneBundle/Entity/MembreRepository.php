<?php

namespace mgate\PersonneBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MembreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembreRepository extends EntityRepository
{
    public function getIntervenantsParPromo()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('m')->from('mgatePersonneBundle:Membre', 'm')
          ->innerJoin('m.missions', 'mi')
          ->orderBy('m.promotion', 'ASC');
        return $query->getQuery()->getResult();
    }
    
    /**
     * 
     * @return array
     */
    
    public function getMembresParPromo()
    {
        $entities = $this->findBy(array(), array('promotion' => 'desc'));
        $membresParMandat = array();
        foreach($entities as $membre){
            $promo = $membre->getPromotion();
            if(array_key_exists($promo, $membresParMandat))
                $membresParMandat[$promo][] = $membre;
            else
                $membresParMandat[$promo] = array($membre);
        }        
        return $membresParMandat;
    }
    
    public function getCotisants()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('m')->from('mgatePersonneBundle:Membre', 'm')
          ->innerJoin('m.mandats', 'ma')
          ->innerJoin('ma.poste', 'p')
          ->where('p.intitule LIKE :membre')
          ->andWhere('ma.finMandat > CURRENT_DATE()')
          ->setParameter('membre', 'Membre');
        return $query->getQuery()->getResult();
    }
}
