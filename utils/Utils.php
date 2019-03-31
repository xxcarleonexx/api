<?php


namespace utils;


class Utils
{

    /**
     * @param $page
     * @param $totalRows
     * @param $perPage
     * @param $pageUrl
     * @return array
     */
    public function getPaging($page, $totalRows, $perPage, $pageUrl)
    {

        $paging = [];

        $paging['first'] = $page > 1 ? "{$pageUrl}page=1" : '';

        $totalPages = ceil($totalRows / $perPage);

        $range = 2;

        $initial = $page - $range;
        $conditionLimit = ($page + $range) + 1;

        $paging['pages'] = [];
        $pageCount = 0;

        for ($currentPage = $initial; $currentPage < $conditionLimit; $currentPage++) {
            if (($currentPage > 0) && ($currentPage <= $totalPages)) {
                $paging['pages'][$pageCount]['page'] = $currentPage;
                $paging['pages'][$pageCount]['url'] = "{$pageUrl}page={$currentPage}";
                $paging['pages'][$pageCount]['current_page'] = $currentPage == $page ? 'yes' : 'no';
                $pageCount++;
            }
        }

        $paging['last'] = $page < $totalPages ? "{$pageUrl}page={$totalPages}" : '';

        return $paging;
    }

}
