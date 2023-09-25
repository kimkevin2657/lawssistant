<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 21/11/2018
 * Time: 10:43 AM
 */

class Organization
{
    const MAX_DEPTH = 100000;
    const MAX_NODE  = 100000;

    var $max_depth = 100000;
    var $max_node  = 1000;

    var $curr_max_depth = 1;
    var $mb_id = "";
    var $up_nm = "";
    var $data  = null;

    var $enqueue_style = "";


    public static function printChart($mb_id, $up_nm = 'pt_id', $max_depth = 100000, $max_node = 100000)
    {
        $instance = new self($mb_id, $up_nm, $max_depth, $max_node);
        $instance->enqueue_style="-rapid";
        $instance->chart();
    }

    public function __construct($mb_id, $up_nm = 'pt_id', $max_depth = 100000, $max_node = 1000)
    {
        $this->mb_id = $mb_id;
        $this->curr_max_depth = 1;
        $this->max_depth = $max_depth;
        $this->max_node  = $max_node;
        $this->data = $this->mb_chart($mb_id, $up_nm);
    }

    public function Organization($mb_id, $up_nm = 'pt_id')
    {
        $this->__construct($mb_id, $up_nm);
    }


    // 출처: http://pixxie.tistory.com/entry/PHPjsonencode-유니코드-오류-php-53버전 [너부리공작소]
    function raw_json_encode($input, $flags = 0) {
        $fails = implode('|', array_filter(array(
            '\\\\',
            $flags & JSON_HEX_TAG ? 'u003[CE]' : '',
            $flags & JSON_HEX_AMP ? 'u0026' : '',
            $flags & JSON_HEX_APOS ? 'u0027' : '',
            $flags & JSON_HEX_QUOT ? 'u0022' : '',
        )));
        $pattern = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
        $callback = function ($m) {
            return html_entity_decode("&#x$m[1];", ENT_QUOTES, 'UTF-8');
        };
        return preg_replace_callback($pattern, $callback, json_encode($input, $flags));
    }

    function enqueue_style(){
        ?>
        <link rel="stylesheet" href="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/css/jquery.orgchart{$this->enqueue_style}.css"?>">
        <link rel="stylesheet" href="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/css/jquery.orgtree{$this->enqueue_style}.css"?>">
        <?php
    }

    function tree(){

        $this->enqueue_style();
        ?>
        <div id="chart-container"></div>
        <script type="text/javascript" src="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/js/jquery.orgtree.js"?>"></script>
        <script>

            (function($){
                $(document).ready(function(){
                    var data = <?php echo $this->toJson() ?>;
                    var ot = $('#chart-container').orgTree({
                        data : data
                    });
                });
            }(jQuery));

        </script>
        <?php
    }

    function chart(){
        $this->enqueue_style();
        ?>
<style>
    #chart-container { position:relative;overflow: auto; }
    #m_chart #chart-container {}
    .orgchart{background: none;}
    .orgchart.l2r{position: static;}
    .oc-export-btn { left: 5px; z-index: 9999; }
    .dpn { display: none; margin:0;}
    .zoom-controller-place { width: 55px;}
    .zoom-controller{
        position:absolute;
        z-index:999;
    }
    .zoom-controller li { width: 55px; display: none; float:none; text-align: center !important;}
    .zoom-controller li.active { display: block ; background: #0ca2b8; color:#fff !important;}
    .zoom-controller:hover li{display:block;}

</style>

<ul id="chart-controller">
    <li class="zoom-controller-place"><ul class="zoom-controller">
            <li class="btn_lsmall bx-white" >200%</li>
            <li class="btn_lsmall bx-white" >150%</li>
            <li class="btn_lsmall bx-white active">100%</li>
            <li class="btn_lsmall bx-white" >75%</li>
            <li class="btn_lsmall bx-white" >50%</li>
            <li class="btn_lsmall bx-white" >25%</li>
            <li class="btn_lsmall bx-white" >10%</li>
        </ul></li>
    <li class="separator"></li>
    <li><button class="btn-up-nm btn_lsmall bx-white bx-green" data-up-nm="pt_id">추천</button></li>
<!--    <li><button class="btn-up-nm btn_lsmall bx-white " data-up-nm="up_id">추천</button></li>-->
    <li class="separator"></li>
    <li><button id="btn-l2r" class="btn-chart btn_lsmall bx-white bx-red" data-direction="l2r">세로</button></li>
    <li><button id="btn-t2b" class="btn-chart btn_lsmall bx-white" data-direction="t2b">가로</button></li>
    <li><button id="btn-tree" class="btn-chart btn_lsmall bx-white" data-direction="tree">트리</button></li>
    <li class="dpn"><button id="btn-reset" class="btn_lsmall bx-white" data-mb-id="<?php echo $this->mb_id ?>" data-direction="tree">초기데이터</button></li>
    <li class="separator"></li>
    <?php if( false ) : ?>
    <li><select id="sel-chart3">
            <option value="999">전체</option>
            <?php for($i = 1; $i < 21; $i++ ) : ?>
                <option value="<?php echo ($i +1 ) ?>"><?php echo $i; ?> UP</option>
            <?php endfor; ?>
        </select></li>
    <?php endif; ?>
</ul>

<div id="chart-container"></div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.1.1/es6-promise.auto.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.rawgit.com/stefanpenner/es6-promise/master/dist/es6-promise.auto.min.js"></script>-->
<script type="text/javascript" src="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/js/html2canvas.min.js"?>"></script>
<script type="text/javascript" src="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/js/jspdf.min.js"?>"></script>
<script type="text/javascript" src="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/js/jquery.orgchart.js"?>"></script>
<script type="text/javascript" src="<?php echo MS_PLUGIN_URL."/zentool/orgchart/assets/js/jquery.orgtree.js"?>"></script>
<script type="text/javascript">
    var oc;
    var tc;
    (function($) {

        $(document).ready(function(){
            var data = (<?php echo $this->toJson() ?>) || {maxdepth :0};

            var $container = $('#chart-container');

            var $zoomController = $('.zoom-controller');
            $zoomController.find('li').on('click', function(){
                $container.css('zoom' , $(this).text());
                $zoomController.find('li').removeClass('active');
                $(this).addClass('active');
            });
            oc = $container.orgchart({
                data : data,
                nodeContent: 'title',
                direction: 'l2r' ,
                // 'draggable': true,
                // 'dropCriteria': function($draggedNode, $dragZone, $dropZone) {
                //     if($draggedNode.find('.content').text().indexOf('manager') > -1 && $dropZone.find('.content').text().indexOf('engineer') > -1) {
                //         return false;
                //     }
                //     return true;
                // },
                // 'pan':true,
                // 'zoom':true,
                // 'zoominLimit' : 2,
                // 'zoomoutLimit' : 0.1,
                // 'verticalLevel' : 20,
                // 'exportButton': true,
                // 'exportFilename': '조직도',
                nodeTemplate: function(data){

                    var data = $.extend({
                        name : '', id :'', plain_id :'', title : '', childcnt : 0, reg_time : ''
                    }, data);

                    if( typeof(data.childcnt) == 'undefined'  ) data.childcnt = 0;

                    return '<div class="title grade-'+data.grade+'">'+data.name+'('+data.childcnt+')</div>' +
                        '<div class="content grade-'+data.grade+' pos-1 user-id"><a href="javascript:;" data-mb-id="'+data.id+'">'+data.plain_id+'</a></div>' +
                        '<div class="content grade-'+data.grade+' pos-2 grade">'+data.title+'</div>' +
                        '<div class="content grade-'+data.grade+' pos-3 reg-at">'+new String(data.reg_time).substring(0,10)+'</div>' ;
                },
                initCompleted : function(){
                    $('.content.user-id>a').on('click', function(){
                        initData($(this).data('mbId'));
                    });
                }
            });

            oc.$chart.on('nodedropped.orgchart', function(event) {
                console.log('draggedNode:' + event.draggedNode.children('.title').text()
                    + ', dragZone:' + event.dragZone.children('.title').text()
                    + ', dropZone:' + event.dropZone.children('.title').text()
                );
            });

            $('#btn-reset').on('click', function(){
                initData($(this).data('mbId'));
                // oc.opts.data = data;
                // initController(data);
                // ocInitDirection();
            });

            var initData = function(mb_id){
                if( mb_id != $('#btn-reset').data('mbId')) {
                    $('#btn-reset').closest('li').removeClass('dpn');
                } else {
                    $('#btn-reset').closest('li').addClass('dpn');
                }
                var options = {
                    url : '/plugin/zentool/orgchart/ajax.orgdata.php',
                    data: {'mb_id': mb_id, 'up_nm' : $('.btn-up-nm.bx-green').data('upNm'), 'max_depth' : '<?php echo $this->max_depth; ?>', 'max_node':'<?php echo $this->max_node; ?>'},
                    type: 'POST',
                    dataType : 'json',
                    beforeSend: function(){
                    },
                    success : function(data){
                        initController(data);
                        oc.opts.data = data;
                        ocInit({ data:data });
                    }
                };

                $.ajax(options);
            };

            var setHeight = function(){

                setTimeout(function(){
                    var $orgChart = $('#chart-container>.orgchart');
                    var $table = $orgChart.find('>table');
                    var height = $orgChart.height();
                    var width  = $orgChart.width();

                    if( $orgChart.is('.l2r')){
                        height = $orgChart.width();
                        width = $orgChart.height();
                    }

                    /*console.log({
                        height: height,
                        width: width,
                        table : {
                            height : $table.height(),
                            width  : $table.width()
                        }
                    });*/

                    $container.css('height', ( height + 100 ) + 'px');
                    // $orgChart.width(width);
                }, 500);

            };

            var initController = function(data){

                var $controller= $('#chart-controller');


                $controller.find('li.up-btn').remove();

                for( var i = 1 ; i <= data.maxdepth; i++) {
                    var $item = $('<li class="up-btn" data-depth="'+i+'"><button data-depth="'+i+'" class="btn-up btn_lsmall bx-white">' + i + ' UP</button></li>');
                    $item.find('button').on('click', function(){

                        $(this).closest('ul').find('button.btn-up').removeClass('bx-blue');
                        $(this).addClass('bx-blue');

                        if( $('.btn-chart.bx-red').data('direction') == 'tree' ) {
                            tc.showDepth($('.btn-up.bx-blue').data('depth'))
                        } else {
                            ocInit();
                        }

                    });

                    $controller.append($item);
                }

                $controller.find('li.up-btn:last-of-type').find('button').addClass('bx-blue');
            };

            initController(data);

            var ocInit = function(options){

                $container.css('height' , 'auto');
                $container.css('width' , 'auto');

                var options = options || {}

                oc.init( $.extend(
                    {   direction    : $('.btn-chart.bx-red').data('direction'),
                        visibleLevel : $('.btn-up.bx-blue').data('depth') + 1 }, options));

                setHeight();

            };

            var ocInitDirection = function(){

                $container.css('height' , 'auto');
                $container.css('width' , 'auto');

                if ($('.btn-chart.bx-red').data('direction') != 'tree' ) {
                    ocInit();
                } else {
                    tc = $container.orgTree({data : oc.opts.data,
                        visibleLevel: $('.btn-up.bx-blue').data('depth'),
                        onnameclick : function(user){
                            var options = {
                                url : '/plugin/zentool/orgchart/ajax.orgdata.php',
                                data: {'mb_id': user.id, 'up_nm' : $('.btn-up-nm.bx-green').data('upNm')},
                                type: 'POST',
                                dataType : 'json',
                                beforeSend: function(){
                                },
                                success : function(data){
                                    $('#btn-reset').closest('li').removeClass('dpn');
                                    initController(data);
                                    oc.opts.data = data;
                                    ocInitDirection();
                                }
                            };

                            $.ajax(options);
                         }});
                };

            };

            $('#btn-l2r, #btn-t2b, #btn-tree').on('click', function () {
                $('.btn-chart').removeClass('bx-red');
                $(this).addClass('bx-red');

                ocInitDirection();

            });

            $('.btn-up-nm').on('click', function(){
                if( $(this).is('bx-green') ) return;

                $('.btn-up-nm').removeClass('bx-green');
                $(this).addClass('bx-green');

                var options = {
                    url : '/plugin/zentool/orgchart/ajax.orgdata.php',
                    data: {'mb_id': oc.opts.data.id, 'up_nm' : $('.btn-up-nm.bx-green').data('upNm'), 'max_depth' : '<?php echo $this->max_depth; ?>', 'max_node':'<?php echo $this->max_node; ?>'},
                    type: 'POST',
                    dataType : 'json',
                    beforeSend: function(){
                    },
                    success : function(data){

                        $('#btn-reset').closest('li').removeClass('dpn');

                        initController(data);
                        oc.opts.data = data;
                        if ( $('.btn-chart.bx-red').data('direction') != 'tree' ) {
                            ocInit({ data:data });
                        } else {
                            ocInitDirection();
                        }

                    }
                };

                $.ajax(options);

            });

            setHeight();

        })

    }(jQuery));
</script>
<?php
    }

    /**
     * @return false|mixed|string
     */
    function toJson(){
        // 5.4 이상
        // return json_encode($this->data, JSON_UNESCAPED_UNICODE);
        // return $this->raw_json_encode(json_encode($this->data));
        return json_encode($this->data);
    }


    private function childResult($level, $up_nm, $pt_id)
    {
        $fetch = "a.index_no, a.id, a.name, a.gender, a.grade, a.point, a.cellphone, a.reg_time, a.login_sum, a.email, a.pt_id, a.up_id, b.gb_name title, a.job_title";
        if( $up_nm == 'pt_id'){
            $sql = "select c.up_lv depth, {$fetch} from shop_member a, shop_member_grade b, shop_minishop_hierarchy_pt c where a.grade = b.gb_no and a.id = c.dn_id and c.pt_id = '{$pt_id}' and c.up_lv <= ".$this->max_depth." order by c.up_lv asc limit 0, ".$this->max_node;
        } else if( $up_nm == 'up_id'){
            $sql = "select c.up_lv depth, {$fetch} from shop_member a, shop_member_grade b, shop_minishop_hierarchy_up c where a.grade = b.gb_no and a.id = c.dn_id and c.up_id = '{$pt_id}' and c.up_lv <= ".$this->max_depth." order by c.up_lv asc limit 0, ".$this->max_node;
        } else {
            $sql = "select {$level} depth, {$fetch} from shop_member a, shop_member_grade b where a.grade = b.gb_no and a.${up_nm} = '$pt_id'";
        }

        return sql_query($sql);
    }

    /**
     * @param $mb_id
     * @param string $up_nm
     * @param int $level
     * @return array
     */
    function mb_childes($mb_id, $up_nm = 'pt_id', $level = 0) {

        $childes = array();

        if( $level > $this->max_depth ) return $childes;

        $result = $this->childResult($level, $up_nm, $mb_id);

        while($row = sql_fetch_array($result)) {
            if( $this->curr_max_depth < $row['depth'] ) $this->curr_max_depth = $row['depth'];
            $obj = (object) $row;
            $rows[$row['id']]= $obj;
            $rows[$row['id']]->plain_id = $row['id'];
            $rows[$row['id']]->children = [];
            $rows[$row['id']]->childcnt = count($rows[$row['id']]->children);
            if( isset($rows[$row[$up_nm]])) {
                array_push($rows[$row[$up_nm]]->children, $rows[$row['id']]);
                $rows[$row[$up_nm]]->childcnt = count($rows[$row[$up_nm]]->children);
            } else {
                array_push($childes, $rows[$row['id']]);
            }
        }

        return $childes;
    }

    /**
     * @param $mb_id
     * @param string $up_nm
     * @return array|null
     */
    function mb_chart($mb_id, $up_nm = 'pt_id')
    {
        global $_SESSION;
        $mb = get_member( $_SESSION['ss_mb_id'] );

        // 관리자나 가맹점이 아니면 조직도를 볼수 없다.
        if( ! is_admin($mb['grade']) && !is_minishop($mb['id'])) return;

        /**
         * TODO: 내 조직 인가 확인, 내 ID(패미리ID) 인가 확인
         */

        $r = sql_fetch_array($this->childResult(1, 'id',  $mb_id));
        // sql_fetch("select index_no, id, name, gender, grade, point, cellphone, reg_time, login_sum, email  from shop_member where id='${mb_id}' ");

        if( $r ) {
            $r['maxdepth'] = $this->curr_max_depth;
            $r['plain_id'] = $r['id'];
            $r['children'] = $this->mb_childes($r['id'], $up_nm, 1);
            $r['childcnt'] = count($r['children']);
            $r['maxdepth'] = $this->curr_max_depth;
        }

        return $r;

    }
}