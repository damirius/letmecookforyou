<?php
 
namespace AppBundle\Repository;

use AppBundle\Location\LocationSearchParameters;
use AppBundle\Entity\User as UserEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class Event extends EntityRepository
{

    public function searchWithLocation(LocationSearchParameters $location, $time, $tags, $whoPays, $whosePlace, $limit, $offset)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id', 'integer');

        $q = 'SELECT DISTINCT event.id,
                    IFNULL(( :unit * acos( cos( radians(:lat) ) * cos( radians( latitude ) )
                    * cos( radians( longitude ) - radians(:lng) ) + sin( radians(:lat) ) * sin(radians(latitude)) ) ), 0) AS distance
                FROM event';

        $where = [];
        if ($tags) {
            $q .= ' INNER JOIN event_tag ON event.id = event_tag.event_id
                INNER JOIN tag ON tag.id = event_tag.tag_id';
            $where []= 'tag.name IN (:tags)';
        }
        if ($whoPays) {
            $where []= 'event.who_pays = :whoPays';
        }
        if ($whosePlace) {
            $where []= 'event.whose_place = :whosePlace';
        }
        if ($time) {
            $where []= 'event.when BETWEEN NOW() AND DATE_ADD(CURDATE(), INTERVAL :time DAY)';
        } else {
            $where []= 'event.when > NOW()';
        }

        if (count($where)) {
            $q .= ' WHERE ' . implode(' AND ', $where);
        }

        $q .= ' HAVING distance < :radius
                ORDER BY distance
                LIMIT :offset , :limit';

        $query = $this->getEntityManager()->createNativeQuery($q, $rsm);

        $query->setParameter('lat', $location->getLatitude());
        $query->setParameter('lng', $location->getLongitude());
        $query->setParameter('radius', $location->getRadius());
        $query->setParameter('unit', $location->getUnit());
        $query->setParameter('limit', $limit);
        $query->setParameter('offset', $offset);
        if ($whoPays) {
            $query->setParameter('whoPays', $whoPays);
        }
        if ($whosePlace) {
            $query->setParameter('whosePlace', $whosePlace);
        }
        if ($tags) {
            $query->setParameter('tags', $tags);
        }
        if ($time) {
            $query->setParameter('time', $time);
        }

        $result = $query->getResult();

        $ids = [];
        foreach ($result as $row) {
            $ids []= $row['id'];
        }

        return $this->findBy([
            'id' => $ids
        ], ['when' => 'ASC']);
    }

    public function search($time, $tags, $whoPays, $whosePlace, $limit, $offset)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id', 'integer');

        $q = 'SELECT DISTINCT event.id FROM event';

        $where = [];
        if ($tags) {
            $q .= ' INNER JOIN event_tag ON event.id = event_tag.event_id
                INNER JOIN tag ON tag.id = event_tag.tag_id';
            $where []= 'tag.name IN (:tags)';
        }
        if ($whoPays) {
            $where []= 'event.who_pays = :whoPays';
        }
        if ($whosePlace) {
            $where []= 'event.whose_place = :whosePlace';
        }
        if ($time) {
            $where []= 'event.when BETWEEN NOW() AND DATE_ADD(CURDATE(), INTERVAL :time DAY)';
        } else {
            $where []= 'event.when > NOW()';
        }

        if (count($where)) {
            $q .= ' WHERE ' . implode(' AND ', $where);
        }

        $q .= ' LIMIT :offset , :limit';

        $query = $this->getEntityManager()->createNativeQuery($q, $rsm);

        $query->setParameter('limit', $limit);
        $query->setParameter('offset', $offset);
        if ($whoPays) {
            $query->setParameter('whoPays', $whoPays);
        }
        if ($whosePlace) {
            $query->setParameter('whosePlace', $whosePlace);
        }
        if ($tags) {
            $query->setParameter('tags', $tags);
        }
        if ($time) {
            $query->setParameter('time', $time);
        }

        $result = $query->getResult();

        $ids = [];
        foreach ($result as $row) {
            $ids []= $row['id'];
        }

        return $this->findBy([
            'id' => $ids
        ], ['when' => 'ASC']);
    }

    public function findEventsHostedBy(UserEntity $user) {
        $dql = "SELECT e FROM AppBundle:Event e WHERE e.host = :user ORDER BY e.when ASC";

        return $this->getEntityManager()->createQuery($dql)->setParameter('user', $user)->getResult();
    }

    public function findEventsAppliedBy(UserEntity $user) {
        $dql = "SELECT e FROM AppBundle:Event e JOIN e.applications a WHERE a.applicant = :user ORDER BY e.when ASC";

        return $this->getEntityManager()->createQuery($dql)->setParameter('user', $user)->getResult();
    }

    public function findEventsAttending(UserEntity $user) {
        $dql = "SELECT e FROM AppBundle:Event e JOIN e.applications a WHERE a.applicant = :user AND a.hostConfirmed = true AND a.guestConfirmed = true ORDER BY e.when ASC";

        return $this->getEntityManager()->createQuery($dql)->setParameter('user', $user)->getResult();
    }
}
