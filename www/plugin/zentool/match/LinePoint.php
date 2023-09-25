<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-01
 * Time: 03:03
 */

class LinePoint
{

    var $total_page = 0;
    var $page       = 1;
    var $result        ;
    var $total_count= 0;
    var $current       ;

    /**
     * @param $page
     * @return LinePoint
     */
    public static function getList($page)
    {
        global $member;

        $instance = new self;

        $sql_common = " from shop_partner_line_point ";
        $sql_search = " where mb_id = '{$member['id']}' ";
        $sql_order = " order by  lp_datetime desc ";

        $sql = " select count(*) as cnt $sql_common $sql_search ";
        $row = sql_fetch($sql);
        $instance->total_count = $row['cnt'];

        $rows = 30;

        $instance->total_page = ceil($instance->total_count / $rows); // 전체 페이지 계산

        if ($page == "") {
            $page = 1;
        } // 페이지가 없으면 첫 페이지 (1 페이지)
        $from_record = ($page - 1) * $rows; // 시작 열을 구함

        $sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";

        $instance->result = sql_query($sql);

        return $instance;
    }

    public function next()
    {
        $row = sql_fetch_array($this->result);

        if( ! $row ) return null;


        $this->current = (object) $row;


        return $this;

    }

    public function point()
    {
        return $this->current->lp_point;
    }

    public function regDate()
    {
        return $this->current->lp_datetime;
    }

    public function message()
    {
        return $this->current->lp_content;
    }

    public function displayLineId()
    {
        //return Match::displayLineId($this->current->line_id);

        return str_replace(Match::MATCH_ID_SEPARATOR, ", ",
               str_replace(Match::LINE_DOWN_SEPARATOR, " (",
               str_replace(Match::LINE_ID_SEPARATOR, ')<br/>'
                   , $this->current->line_id
               ))).')';
    }
}