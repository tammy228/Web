<?php


namespace App\Service\Entity;


use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractEntityService
{
    const PAGER_LIMIT = "limit";
    const PAGER_OFFSET = "offset";

    const OPTIONS_CRITERIA = "criteria";
    const OPTIONS_SEARCH_SCOPE = 'searchScope';
    const OPTIONS_ORDER_BY = 'orderBy';

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function handleLimit(int $limit) : ?int
    {
        return $limit === 0 ? null : $limit;
    }

    public static function handleOffset(int $limit, int $page) : ?int {
        return ($page - 1) * $limit;
    }

    protected function pager($limit, $page) : array
    {
        $_limit = $limit;
        if($_limit < 0) $_limit = 0;

        $_offset = ($page - 1) * $_limit;
        if($_offset < 0) $_offset = 0;

        return array(
            self::PAGER_LIMIT => $_limit === 0 ? null : $_limit,
            self::PAGER_OFFSET => $_offset === 0 ? null : $_offset
        );
    }

    protected function optionsHandler($options, $default = array())
    {
        $defaultCriteria = isset($default[self::OPTIONS_CRITERIA]) ? $default[self::OPTIONS_CRITERIA] : array();
        $defaultOrderBy = isset($default[self::OPTIONS_ORDER_BY]) ? $default[self::OPTIONS_ORDER_BY] : array();
        $defaultSearchScope = isset($default[self::OPTIONS_SEARCH_SCOPE]) ? $default[self::OPTIONS_SEARCH_SCOPE] : null;

        $returnData = array();
        $returnData[self::OPTIONS_CRITERIA] =
            isset($options[self::OPTIONS_CRITERIA]) ? $options[self::OPTIONS_CRITERIA] : $defaultCriteria;
        $returnData[self::OPTIONS_ORDER_BY] =
            isset($options[self::OPTIONS_ORDER_BY]) ? $options[self::OPTIONS_ORDER_BY] : $defaultOrderBy;

        $returnData[self::OPTIONS_SEARCH_SCOPE] =
            (isset($options[self::OPTIONS_SEARCH_SCOPE])
            && is_array($options[self::OPTIONS_SEARCH_SCOPE])
            && (count($options[self::OPTIONS_SEARCH_SCOPE]) > 0)) ?

                $options[self::OPTIONS_SEARCH_SCOPE] : $defaultSearchScope;

        return $returnData;
    }
}