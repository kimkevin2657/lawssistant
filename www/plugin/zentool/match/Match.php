<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 13:29
 */

class Match
{
    const MAX_LINE_DEPTH      = 2;
    const MATCH_ID_SEPARATOR  = "∥";
    const LINE_ID_SEPARATOR   = "§";
    const LINE_DOWN_SEPARATOR = "≫";
    const MATCH_PREFIX        = '';
    const POINT_PER_LINE      = 1;
    const LINE_MAX_ROLLUP     = 1;

    public static $tb_match   = "shop_partner_matching";

    /**
     * @return array|null
     */
    public static function config()
    {
        global $config;
        return $config;
    }

    /**
     * @param $pt_id
     * @param $mb_id
     * @param $reg_price
     * @param $anew_grade
     * @return bool|void
     */
    public static function matchUp($pt_id, $mb_id, $reg_price, $anew_grade)
    {
        if(! is_partner($pt_id) ) return false;

        $config = self::config();

        $tb_match   = self::$tb_match;
        $tb_member  = "shop_member";

        $match_use  = 1;//$config['pf_anew_match_use'];

        $pt = get_partner($pt_id);

        /** @var 관리 수수료%(매칭 수수료%) $match_pay_common */
        $grade = Member::get_grade($anew_grade);
        $pt_grade = Member::get_grade($pt['anew_grade']);
        $match_pay_common = 7.5;//$config['pf_anew_match_pay'];
        $match_pay_rate  = $grade['gb_pf_per_match_pay'];//$config['pf_anew_match_pay_'.$anew_grade];

        /** @var 상위 등급으로 결정됨 $line_point_gold_matched */
        $line_point_gold_matched = $pt_grade['gb_line_point_gold_matched'];//$config['pf_line_point_gold_matched_'.$pt['anew_grade']];
        if( empty($match_pay) ) $match_pay = $match_pay_common;

        // 매칭수당은 2이상 이여야 함.
        $match_per  = 2;//$config['pf_anew_match_per'] - 1;
        $match_per_other = $match_per - 1;

        // 매칭 시스템에 사용자 등록.
        $exist_mb_id = sql_fetch("select a.mb_id from ${tb_match} a where a.mb_id = '${mb_id}'");
        if( ! $exist_mb_id ) {
            sql_query("INSERT INTO ${tb_match}(pt_id, mb_id, reg_price, grade) values('${pt_id}', '${mb_id}', ${reg_price}, {$anew_grade})");
        }

        // 매칭 수당 미지급 사용자가 있는지 확인.
        $sql = "select a.mb_id, a.reg_price, b.grade from ${tb_match} a, ${tb_member} b where a.pt_id = b.id and a.pt_id = '${pt_id}' and a.mb_id <> '${mb_id}' and a.match_id is null limit 0, {$match_per_other}";
        $rslt = sql_query($sql);
        $pre  = array();
        $match_ids = $mb_id;
        $match_plain_ids = $mb_id.'('.get_grade($anew_grade).')';
        $match_id_glue = ', ';

        $min_grade = $anew_grade;
        $min_reg_price = $reg_price;
        $sum_reg_price = $reg_price;
        $gold_matched  = $anew_grade == Partner::LEVEL_GOLDEN ? true : false;

        $sum_match_pay     = $reg_price / 11 *  10 * ($match_pay_rate/100);
        while( $row = sql_fetch_array( $rslt ) ) {
            if( $row['grade'] != Partner::LEVEL_GOLDEN ) {
                $gold_matched = false;
            }
            $row_grade = Member::get_grade($row['grade']);
            $sum_match_pay+= ($row['reg_price'] / 11 * 10 * ($row_grade['gb_pf_per_match_pay'] / 100));
            $sum_reg_price+= $row['reg_price'];
            if( $row['grade'] < $min_grade ){
                $min_grade = $row['grade'];
            }
            if( $row['reg_price'] < $min_reg_price ) {
                $min_reg_price = $row['reg_price'];
            }
            array_push( $pre, $row );
            $match_ids .=$match_id_glue.$row['mb_id'];
            $match_plain_ids .=$match_id_glue.$row['mb_id'].'('.get_grade($row['grade']).')';
        }

        $match_pay = $sum_match_pay;//$match_per;

        if( count( $pre ) == $match_per_other ) {

            $match_id = self::MATCH_PREFIX . str_replace($match_id_glue, self::MATCH_ID_SEPARATOR, $match_plain_ids);

            // 매칭수당 지급 완료 처리
            foreach($pre as $row ) {
                $prv_id = $row['mb_id'];
                sql_query("UPDATE ${tb_match} set match_id = '${match_id}', match_at = now(), upd_dt = now() where mb_id = '${prv_id}'");
            }

            // 매칭수당 지금 완료 처리
            sql_query("UPDATE ${tb_match} set match_id = '${match_id}', match_at = now(), upd_dt = now() where mb_id = '${mb_id}'");

            // 매칭수당을 사용을 하지 않는다면 리턴
            if($match_use) {
                self::matchUpPay($pt_id, $match_pay, $match_plain_ids, $match_id);
                if( $gold_matched ) {
                    insert_line_point_matched_rollup($grade['gb_line_point_gold_matched_rollup_level'], $pt_id, $line_point_gold_matched, $match_plain_ids.' 골드회원 매칭', 'anew_match', $match_id_glue, 'matched', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'] );
                }
            }

            self::matchUpCnt($pt_id);

        }

        if( USE_LINE_UP ) self::lineUp($pt_id, $anew_grade);

        return true;

    }

    public static function matchUpCnt($pt_id)
    {
        $tb_match   = self::$tb_match;

        sql_query("UPDATE shop_member  
                        SET match_cnt = (
                            SELECT  COUNT(1) match_cnt FROM ( SELECT COUNT(1), match_id FROM {$tb_match} WHERE pt_id = '{$pt_id}' AND match_id IS NOT NULL GROUP BY match_id) b
                          ) WHERE id = '{$pt_id}'" );

    }

    /**
     * @param $pt_id
     * @param $match_pay (% or unit)
     * @param $match_plain_ids
     * @param $match_id
     */
    public static function matchUpPay($pt_id, $match_pay, $match_plain_ids, $match_id)
    {
        /**
         * 매칭 수당 적립 사용 여부 타법인 설정 확인
         */
        $pt= get_partner($pt_id);
        $ptype = get_partner_type($pt['from_biz_type']);
        if( ! ( $ptype && $ptype['use_partner_pay'] == 0 )) {
            // 매칭수당 지급
            insert_pay($pt_id, $match_pay, "{$match_plain_ids} 님 매칭 축하", 'anew_match', $match_id, '매칭수당', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'], 7);
        }

    }

    /**
     * @param $pt_id
     * @param $anew_grade
     * @param int $recursive
     * @return bool
     */
    public static function lineUp($pt_id, $anew_grade, $recursive = 0)
    {
        $line_depth = Match::lineDepth($anew_grade);

        if( $line_depth ==  2 ) {

            $mb = get_member($pt_id);
            $pt_id = $mb['pt_id'];

            $sql = "
            SELECT *
            FROM (
                   SELECT a.match_id
                       ,  a.line_id
                       ,  count(1) cnt
                       ,  case
                            when b.pt_id = @pt_id and a.pt_id = @mb_id then @rownum := @rownum + 1
                            else
                              @rownum := 1
                            end    rnum
                       ,  case
                            when b.pt_id <> @pt_id then
                              @pt_id := b.pt_id
                            else b.pt_id
                            end    pt_id
                       ,  case
                            when a.pt_id <> @mb_id then
                              @mb_id := a.pt_id
                            else a.pt_id
                            end    mb_id
                   FROM shop_partner_matching a,
                        shop_member b,
                        (
                          select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
                        ) c
                   WHERE a.pt_id = b.id
                     AND a.match_id is not null
                     AND b.pt_id = '{$pt_id}'
                   GROUP BY b.pt_id, a.pt_id, a.match_id
                   ORDER BY b.pt_id, a.pt_id, case when a.line_id is null then 1 else 0 end, a.line_id
                 ) A
            WHERE rnum = 1
              and line_id is null
            limit 0, {$line_depth}
          ";

            $match_up_id = 'mb_id';
        } else if($line_depth == 1 ) {

            $sql = "
            SELECT *
            FROM (
                   SELECT a.match_id
                       ,  a.line_id
                       ,  count(1) cnt
                       ,  case
                            when a.pt_id = @pt_id and a.mb_id = @mb_id then @rownum := @rownum + 1
                            else
                              @rownum := 1
                            end    rnum
                       ,  case
                            when a.pt_id <> @pt_id then
                              @pt_id := a.pt_id
                            else a.pt_id
                            end    pt_id
                       ,  case
                            when a.mb_id <> @mb_id then
                              @mb_id := a.mb_id
                            else a.mb_id
                            end    mb_id
                   FROM shop_partner_matching a,
                        (
                          select @rownum := 0 rnum, @pt_id := '', @mb_id := ''
                        ) c
                   WHERE 1 = 1
                     AND a.match_id is not null
                     AND a.pt_id = '{$pt_id}'
                   GROUP BY a.mb_id, a.match_id
                   ORDER BY a.mb_id, case when a.line_id is null then 1 else 0 end, a.line_id
                 ) A
            WHERE rnum = 1
              and line_id is null
            limit 0, {$line_depth}
            ";

            $match_up_id = 'pt_id';
        } else {
            return false;
        }

        $result = sql_query($sql);
        $result_count = sql_num_rows($result);

        if( $result_count == $line_depth ) {

            $line = (object) array(
                'pt_id' => $pt_id,
                'line_id'=>'',
                'mb_ids' => array(),
                'match_ids'=>array(),
                'keys'=>array()
            );

            $i = 0;
            while( $row = sql_fetch_array($result) ) {
                $obj = (object) $row;

                array_push($line->mb_ids, $obj->mb_id);
                array_push($line->match_ids, $obj->match_id);
                array_push($line->keys, $obj->$match_up_id.self::LINE_DOWN_SEPARATOR.$obj->match_id);

                $i++;
                if( $i > 10 ) { die($i); }
            }

            $line->line_id = join(self::LINE_ID_SEPARATOR, $line->keys);

            $sql = "
            UPDATE shop_partner_matching 
               SET line_id = '{$line->line_id}',
                   line_at = now(),
                   upd_dt  = now()
             WHERE line_id IS NULL 
               AND match_id IN ('" .   join("','", $line->match_ids)       ."')    
            ";


            UnitTest::debugging(compact('recursive', 'anew_grade', 'result_count', 'line_point', 'line_depth', 'line', 'sql'));

            sql_query($sql);

            $line_point = self::linePoint($line);

            self::lineUpPoint($pt_id, $line, $line_point, self::POINT_PER_LINE);

            // 오류 등으로 처리 되지 않은 건이 있을 지도 몰라서??
            // if( $recursive < 5 ) self::lineUp($pt_id, $anew_grade, ++$recursive);
        }

        return true;
    }

    /**
     * @param $pt_id
     * @param $line
     * @param $line_point
     * @param int $line_cnt
     * @param int $depth
     *
     * $line_point 는 직라인 쇼핑포인트와 토탈 쇼핑포인트가 있고
     * 직라인쇼핑포인트만 line_cnt 로 부여 한다.
     */
    public static function lineUpPoint($pt_id, $line, $line_point, $line_cnt = 0, $depth = 1)
    {

        if(! is_partner($pt_id) ) return;

        $memo = $pt_id . ' 님 라인 점수 (' . $line->line_id . ') 획득 축하합니다. ';

        $fields = array();
        $fields['line_id'] = $line->line_id;
        $fields['mb_id']   = $pt_id;
        $fields['point']   = $line_point;
        $fields['memo']    = $memo;
        $fields['reg_date']= date('Y-m-d H:i:s');

        $queries = array();

        foreach($fields as $field=>$value) {
            array_push($queries, "{$field} = '{$value}'");
        }

        $sql = "INSERT INTO shop_partner_line_point SET ";
        $sql.= join($queries, ",");

        sql_query($sql);
        $sql = "UPDATE shop_member SET line_cnt = line_cnt + {$line_cnt}, line_point = line_point + {$line_point}, total_line_cnt = total_line_cnt + 1 WHERE id = '{$pt_id}'";
        // var_dump($sql);
        sql_query($sql);

        self::promotion($pt_id);
        $mb = (object) get_member($pt_id);

        if( ++$depth <= self::LINE_MAX_ROLLUP ) {

            $up_pt_id = $mb->pt_id;

            if( $up_pt_id != $pt_id ) {
                self::lineUpPoint($up_pt_id, $line, $line_point,0, $depth);
            }
        }

    }

    /**
     * @param $mb_id
     */
    public static function promotion($mb_id)
    {
        return;
        $mb = (object) get_member($mb_id);

        $sql = "SELECT IFNULL(max(up_point),0) up_point FROM shop_partner_bonus_history where mb_id = '{$mb_id}'";
        $max  = sql_fetch($sql);
        $up_point  = ( $max ) ? $max['up_point'] : 0;
        $sql = "SELECT a.*, b.job_title FROM shop_partner_bonus a, shop_partner_bonus_title b WHERE a.job_no = b.job_no and a.up_point <= {$mb->line_point} and a.up_point > {$up_point} ORDER BY a.up_point";
        $rslt = sql_query($sql);
        while($bonus = sql_fetch_array($rslt)){
            self::levelUp($mb_id, (object) $bonus);
        }

    }

    /**
     * @param $pt_id
     * @param $bonus
     */
    public static function levelUp($pt_id, $bonus)
    {
        return;
        $sql = "UPDATE shop_member SET job_no = {$bonus->job_no}, job_title = '{$bonus->job_title}' WHERE id = '{$pt_id}'";
        sql_query($sql);

        insert_pay($pt_id, $bonus->bonus_pay, "{$bonus->up_point} 달성 축하합니다.", "line-up", "{$pt_id}_{$bonus->up_point}");

        sql_query("UPDATE shop_partner_bonus SET vi_cnt = vi_cnt + 1 WHERE up_point = {$bonus->up_point}");

        $fields = array(
            'mb_id'=>$pt_id,
            'up_point'=>$bonus->up_point,
            'bonus_at'=>MS_TIME_YMDHIS
        );
        insert('shop_partner_bonus_history', $fields);
    }

    /**
     * @param $pt_id
     */
    public static function linePointSum($pt_id)
    {
        $sch = "pt_id = '{$pt_id}'";
        $qry = "select '1up' depth, id, pt_id, name, match_cnt, line_cnt, point, total_line_cnt from shop_member where {$sch}";
        $ext = "pt_id in (select id from shop_member where {$sch})";
        $pieces = "\nunion all\n";

        $queries = array();
        array_push($queries, $qry);
        for($i = 2; $i <= 20; $i++ ) {
            $qry = str_replace(($i-1).'up', ($i).'up', $qry);
            $qry = str_replace($sch, $ext, $qry);
            array_push($queries, $qry);
        }

        $qry = join($queries, $pieces);

        sql_query($qry);
    }

    /**
     * @param $name
     * @param $gb_line_depth
     * @param array $attrs
     * @return false|string
     */
    public static function selectBoxLineDepth($name, $gb_line_depth, $attrs = array())
    {
        ob_start();

        ?>
        <select id="<?php echo preg_replace('\[\]', '', $name);?>" name="<?php echo $name?>"
            <?php foreach($attrs as $attr_name => $attr_value ) : ?>
            <?php echo $attr_name; ?>="<?php echo $attr_value; ?>"
            <?php endforeach; ?>
        >
            <option value="0">-</option>
            <?php for( $i = 1 ; $i <= self::MAX_LINE_DEPTH; $i++ ) : ?>
            <option value="<?php echo $i; ?>" <?php if( $i == $gb_line_depth ) echo ' selected="selected"'; ?>>
                <?php echo $i; ?>
            </option>
            <?php endfor; ?>
        </select>
        <?php

        return ob_get_clean();

    }

    /**
     * @param $anew_grade
     * @return mixed
     */
    public static function lineDepth($anew_grade)
    {
        $row = sql_fetch("SELECT gb_line_depth FROM shop_member_grade WHERE gb_no = {$anew_grade}");

        return $row['gb_line_depth'];
    }

    public static function displayMatchId($match_id)
    {
        $matches = explode(self::MATCH_ID_SEPARATOR, $match_id);
        echo '<div class="sub">'.join("</div><div class='sub'>", array_map(array(self::class, 'displayMember'), $matches)).'</div>';
    }

    public static function matchAjaxUrl()
    {
        return MS_PLUGIN_URL."/zentool/match/ajax.matching.php";
    }

    public static function displayMember($mb_id)
    {
        $mb = get_member($mb_id);
        return $mb['name'] . "({$mb_id})" . get_grade($mb['grade']);
    }

    public static function displayLineId($line_id)
    {
        $lines = explode(self::LINE_ID_SEPARATOR, $line_id);
        foreach( $lines as $key => $line ) {
            $line_match_id = explode(self::LINE_DOWN_SEPARATOR, $line);
            $line = '<li><span class="sup">'. self::displayMember($line_match_id[0]) . '</span><ul class="matched"><li><span class="sub">' . join('</span></li><li><span class="sub">', array_map(array(get_class(self::class), 'displayMember'), explode(self::MATCH_ID_SEPARATOR, $line_match_id[1])))."</span></li></ul>";
            $lines[$key] = $line;
        }
        return '<ul class="line"><li>'.join("</li><li>", $lines)."</li></ul>";
    }

    public static function lineAjaxUrl()
    {
        return MS_PLUGIN_URL."/zentool/match/ajax.lining.php";
    }

    /**
     * @param $line
     * @return int
     */
    public static function linePoint($line)
    {
        $sql = "SELECT a.pt_id, c.gb_line_point gb_line_point, IFNULL(e.gb_line_point, 0) gb_line_point_top
                 FROM ".self::$tb_match." a 
                INNER JOIN shop_member b ON a.pt_id = b.id 
                INNER JOIN shop_member_grade c ON b.grade = c.gb_no
                 LEFT JOIN shop_member d ON b.pt_id = d.id
                 LEFT jOIN shop_member_grade e ON d.grade = e.gb_no
                WHERE a.line_id = '{$line->line_id}' 
                GROUP BY a.pt_id, c.gb_line_point, e.gb_line_point";
        $rslt= sql_query($sql);
        $row = sql_fetch_array($rslt);
        if( sql_num_rows($rslt) > 1 ) {

            $gb_line_point  = (int)$row['gb_line_point'];
            $gb_line_point += (int)$row['gb_line_point_top'];
            while($row = sql_fetch_array($rslt)) {
                $gb_line_point += (int)$row['gb_line_point'];
            }
            return $gb_line_point;
        } else {
            return $row['gb_line_point'];
        }

    }
}