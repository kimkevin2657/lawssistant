'use strict';

(function($){

    $.fn.orgContorller = function(){

    };

    $.fn.orgTree = function(options){

        if( ! $(this).find('.orgchart') ) {
            $(this).append('<div class="orgchart"/>');
        }

        var orgTree = {

            options    : $.extend({ data : {}, visibleLevel : 0 }, options),
            maxDepth   : 1,
            element    : $(this),
            container  : $(this).find('.orgchart'),

            showDepth: function(depth) {
                this.options.visibleLevel = depth;

                for( var i = 1; i <= depth ; i++ ) {
                    this.container.find('li.depth-' + i).removeClass('close');
                }
                this.container.find('li.depth-' + i ).addClass('close');
            },

            init     : function(options){

                var options = options || {};

                this.container.removeClass('l2r');

                this.options = $.extend(this.options, options);

                this.empty();

                var $ul = $('<ul/>');

                $ul.addClass('first-tree');

                this.container.append($ul);

                this.appendTree($ul, this.options.data, this.maxDepth);

                if( this.options.visibleLevel > 0 ) {
                    this.showDepth(this.options.visibleLevel);
                }
            },

            empty   : function(){
                this.container.empty();
            },

            treeUl  : function(){
                var $ul = $('<ul/>');
                $ul.addClass('tree');
                return $ul;
            },

            treeLi : function(){
                var $li = $('<li/>');
                $li.addClass('child');

                return $li;
            },

            card : function(data){
                var that  = this;
                var $card = $('<div/>');

                $card.addClass('card');

                var $icon =$('<i class="fa"></i>');

                $icon.addClass( data.childcnt > 0 ? 'fa-users' : 'fa-user');
                $card.append($icon);

                $card.append($('<span class="name">'+data.name+'</span>'));
                if( data.childcnt > 0 ) {
                    $card.append($('<span class="childinfo"></span>'));
                }

                $card.append($('<span class="title">'+data.title+'</span>'));
                $card.append($('<span class="mb-id">'+data.plain_id+'</span>'));
                $card.append($('<span class="cellphone">휴대폰 : '+data.cellphone+'</span>'));
                $card.append($('<span class="reg-time">가입 : '+new String(data.reg_time).substr(0, 10)+'</span>'));
                $card.append($('<span class="login-sum">로그인 : '+data.login_sum+'</span>'));
                $card.find('>i.fa').on('click', function(){
                    $(this).closest('li').toggleClass('close');
                });

                $card.find('>span.name').on('click', function(){

                    var user = $(this).closest('li').data('user');

                    if( that.options.onnameclick ) {
                        that.options.onnameclick.apply(null, [user]);
                    } else {
                        win_open('pop_memberform.php?mb_id='+user.id,'pop_member','1200','600','yes');
                    }
                });

                return $card;
            },

            appendTree : function($ul, data, depth){
                var $li = this.treeLi();
                var $card = this.card(data);
                $li.addClass('depth-'+depth);
                $li.append( $card );
                $li.data('user', data);
                $ul.append($li);
                data.descendants = data.childcnt;
                data.depth = depth;
                data.maxdepth = depth;
                if( data.childcnt > 0 ){
                    var $ul = this.treeUl();
                    $li.append( $ul );
                    $li.addClass('has-child');
                    depth++;
                    for(var i = 0 ; i < data.childcnt; i++ ){
                        this.appendTree($ul, data.children[i], depth);
                        data.descendants += parseInt(data.children[i].descendants);
                        if( data.children[i].maxdepth > data.maxdepth )
                        {
                            data.maxdepth = data.children[i].maxdepth;
                        }
                    }

                    $card.find('.childinfo').append($('<span>직후원: '+data.childcnt+', 하위전체: '+data.descendants+', 하위레벨: '+(data.maxdepth-data.depth)+'</span>'));
                }

                if( data.maxdepth  > this.maxDepth ) {
                    this.maxDepth = data.maxdepth ;
                }
            }

        };

        orgTree.init(options);

        return orgTree;

    }
}(jQuery));
