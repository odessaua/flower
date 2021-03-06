$(document).ready(function(){
	initArcticModal();
    initSliderBox();
    sortPopup();
    checkSymbol();
    datePicker();
    uploadPhoto();
    $("#slider").easySlider({});
    $("#product-slider").productSlider({
        auto: false,
        continuous: false,
        controlsFade: true
    });
    $(".tooltip").easyTooltip({ useElement: "tip-info" });
});
var $menu = $("#productsListGridActions");
        $(window).scroll(function(){

            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){

                $menu.removeClass("default").addClass("fixed");

            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {

                $menu.removeClass("fixed").addClass("default");

            }

        });//scroll

function initArcticModal() {
    if($('.box-modal').length) {
        $('.call-back-order').click(function() {
            $('#call-back-modal').arcticmodal({
                overlay: {
                    css: {
                        backgroundColor: '#fff',
                        opacity: .05
                    }
                }
            });
        });
        $('.link-del-way').click(function() {
            $('#payment-modal').arcticmodal({
                overlay: {
                    css: {
                        backgroundColor: '#fff',
                        opacity: .05
                    }
                }
            });
        });
    }
}
function initSliderBox(){
    $('.btn-search').click(function(){
        $('.search-popup').toggle();
        return false;
    });
    $(document).click(function(event) {
        if ($(event.target).closest(".search-popup").length) return;
        $(".search-popup").hide();
        event.stopPropagation();
    });

    $('.popup-close').click(function(event) {
        $(this).parents('.header-popup').hide();
    });
}
function sortPopup() {

    var hidden = '.sort-popup';

    $('.drop-link').click(function(e) {
        e.preventDefault();
        if($(this).siblings(hidden).hasClass('hidden')) {
            $(hidden).addClass('hidden');
            $(this).siblings(hidden).removeClass('hidden');
        } else { $(this).siblings(hidden).addClass('hidden'); }

    });
    $(document).click(function(event) {
        if ($(event.target).closest(".sort-popup").length) return;
        $(".sort-popup").addClass('hidden');
        event.stopPropagation();
    });
    $('.drop-link').on('click', function(e) {
        e.stopImmediatePropagation();
    });
}
function checkSymbol() {
    $(".check-symbol").keydown(function (event) {
        if ((event.keyCode > 57 || event.keyCode <48) && (event.keyCode<35 || event.keyCode>39) && (event.keyCode > 105 || event.keyCode < 96) && event.keyCode!=8 && event.keyCode!=9)
            return false;
    });
}
function datePicker() {
    if ($('.datepicker').length){

        $('.input-append.date, .date.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            weekStart: 1
        });
    }
}
function uploadPhoto() {
    $(".upload-photo").click(function(){
        $(this).siblings(".file-input").trigger('click');
    });
    $(".file-input")
        .change(function() {
            var str = "";
            str += $( this ).val();
            $(this).siblings( ".file-input-text" ).val( str );
        })
        .change();
}
(function($) {

    /* placeholder (begin) */
    jQuery.support.placeholder = false;
    test = document.createElement('input');
    if('placeholder' in test) jQuery.support.placeholder = true;

    if(!$.support.placeholder) {
        var active = document.activeElement;
        $("input[type='text'], input[type='email']").focus(function () {
            if ($(this).attr('placeholder') != '' && $(this).val() == $(this).attr('placeholder')) {
                $(this).val('').removeClass('hasPlaceholder');
            }
        }).blur(function () {
                if ($(this).attr('placeholder') != '' && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {
                    $(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
                }
            });
        $("input[type='text'], input[type='email']").blur();
        $(active).focus();
        $('form').submit(function () {
            $(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
        });
    }
    /* placeholder (end) */

    $.fn.easyTooltip = function(options){

        // default configuration properties
        var defaults = {
            xOffset: -5,
            yOffset: 25,
            tooltipId: "tip",
            clickRemove: false,
            content: "",
            useElement: ""
        };

        var options = $.extend(defaults, options);
        var content;

        this.each(function() {
            var tipHeight = 0;
            var title = $(this).attr("title");
            $(this).hover(function(e){
                    content = (options.content != "") ? options.content : title;
                    content = (options.useElement != "") ? $(this).find($("." + options.useElement)).html() : content;
                    $(this).attr("title","");
                    if (content != "" && content != undefined){
                        $("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");


                        tipHeight = $("#" + options.tooltipId).outerHeight();
                        $("#" + options.tooltipId)
                            .css("position","absolute")
                            .css("top",(e.pageY - tipHeight - options.yOffset) + "px")
                            .css("left",(e.pageX + options.xOffset) + "px")
                            .css("display","none")
                            .fadeIn("fast")
                    }
                },
                function(){
                    $("#" + options.tooltipId).remove();
                    $(this).attr("title",title);
                });
            $(this).mousemove(function(e){
                tipHeight = $("#" + options.tooltipId).outerHeight();
                $("#" + options.tooltipId)
                    .css("top",(e.pageY -  tipHeight - options.yOffset) + "px")
                    .css("left",(e.pageX + options.xOffset) + "px")
            });
            if(options.clickRemove){
                $(this).mousedown(function(e){
                    $("#" + options.tooltipId).remove();
                    $(this).attr("title",title);
                });
            }
        });

    };

    $.fn.easySlider = function(options){

        // default configuration properties
        var defaults = {
            prevId: 		'prevBtn',
            prevText: 		'',
            nextId: 		'nextBtn',
            nextText: 		'',
            controlsShow:	true,
            controlsBefore:	'',
            controlsAfter:	'',
            controlsFade:	true,
            firstId: 		'firstBtn',
            firstText: 		'First',
            firstShow:		false,
            lastId: 		'lastBtn',
            lastText: 		'Last',
            lastShow:		false,
            vertical:		false,
            speed: 			800,
            auto:			false,
            pause:			2000,
            continuous:		true,
            numeric: 		false,
            numericId: 		'controls'
        };

        var options = $.extend(defaults, options);

        this.each(function() {
            var obj = $(this);
            var s = $("li", obj).length;
            var w = $("li", obj).width();
            var h = $("li", obj).height();
            var clickable = true;
            obj.width(w);
            obj.height(h);
            obj.css("overflow","hidden");
            var ts = s-1;
            var t = 0;
            $("ul", obj).css('width',s*w);

            if(options.continuous){
                $("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
                $("ul", obj).append($("ul li:nth-child(2)", obj).clone());
                $("ul", obj).css('width',(s+1)*w);
            };

            if(!options.vertical) $("li", obj).css('float','left');

            if(options.controlsShow){
                var html = options.controlsBefore;
                if(options.numeric){
                    html += '<ol id="'+ options.numericId +'"></ol>';
                } else {
                    if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
                    html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
                    html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
                    if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
                };

                html += options.controlsAfter;
                $(obj).after(html);
            };

            if(options.numeric){
                for(var i=0;i<s;i++){
                    $(document.createElement("li"))
                        .attr('id',options.numericId + (i+1))
                        .html('<a rel='+ i +' href=\"javascript:void(0);\">'+ (i+1) +'</a>')
                        .appendTo($("#"+ options.numericId))
                        .click(function(){
                            animate($("a",$(this)).attr('rel'),true);
                        });
                };
            } else {
                $("a","#"+options.nextId).click(function(){
                    animate("next",true);
                });
                $("a","#"+options.prevId).click(function(){
                    animate("prev",true);
                });
                $("a","#"+options.firstId).click(function(){
                    animate("first",true);
                });
                $("a","#"+options.lastId).click(function(){
                    animate("last",true);
                });
            };

            function setCurrent(i){
                i = parseInt(i)+1;
                $("li", "#" + options.numericId).removeClass("current");
                $("li#" + options.numericId + i).addClass("current");
            };

            function adjust(){
                if(t>ts) t=0;
                if(t<0) t=ts;
                if(!options.vertical) {
                    $("ul",obj).css("margin-left",(t*w*-1));
                } else {
                    $("ul",obj).css("margin-left",(t*h*-1));
                }
                clickable = true;
                if(options.numeric) setCurrent(t);
            };

            function animate(dir,clicked){
                if (clickable){
                    clickable = false;
                    var ot = t;
                    switch(dir){
                        case "next":
                            t = (ot>=ts) ? (options.continuous ? t+1 : ts) : t+1;
                            break;
                        case "prev":
                            t = (t<=0) ? (options.continuous ? t-1 : 0) : t-1;
                            break;
                        case "first":
                            t = 0;
                            break;
                        case "last":
                            t = ts;
                            break;
                        default:
                            t = dir;
                            break;
                    };
                    var diff = Math.abs(ot-t);
                    var speed = diff*options.speed;
                    if(!options.vertical) {
                        p = (t*w*-1);
                        $("ul",obj).animate(
                            { marginLeft: p },
                            { queue:false, duration:speed, complete:adjust }
                        );
                    } else {
                        p = (t*h*-1);
                        $("ul",obj).animate(
                            { marginTop: p },
                            { queue:false, duration:speed, complete:adjust }
                        );
                    };

                    if(!options.continuous && options.controlsFade){
                        if(t==ts){
                            $("a","#"+options.nextId).hide();
                            $("a","#"+options.lastId).hide();
                        } else {
                            $("a","#"+options.nextId).show();
                            $("a","#"+options.lastId).show();
                        };
                        if(t==0){
                            $("a","#"+options.prevId).hide();
                            $("a","#"+options.firstId).hide();
                        } else {
                            $("a","#"+options.prevId).show();
                            $("a","#"+options.firstId).show();
                        };
                    };

                    if(clicked) clearTimeout(timeout);
                    if(options.auto && dir=="next" && !clicked){;
                        timeout = setTimeout(function(){
                            animate("next",false);
                        },diff*options.speed+options.pause);
                    };

                };

            };
            // init
            var timeout;
            if(options.auto){;
                timeout = setTimeout(function(){
                    animate("next",false);
                },options.pause);
            };

            if(options.numeric) setCurrent(0);

            if(!options.continuous && options.controlsFade){
                $("a","#"+options.prevId).hide();
                $("a","#"+options.firstId).hide();
            };

        });

    };

    $.fn.productSlider = function(options){

        var defaults = {
            prevId: 		'prevBtn',
            prevText: 		'',
            nextId: 		'nextBtn',
            nextText: 		'',
            controlsShow:	true,
            controlsBefore:	'',
            controlsAfter:	'',
            controlsFade:	false,
            firstId: 		'firstBtn',
            firstText: 		'First',
            firstShow:		false,
            lastId: 		'lastBtn',
            lastText: 		'Last',
            lastShow:		false,
            vertical:		false,
            speed: 			300,
            auto:			false,
            pause:			5000,
            continuous:		true,
            numeric: 		false,
            numericId: 		'controls'
        };

        var options = $.extend(defaults, options);

        this.each(function() {
            var obj = $(this);
            var s = $("li", obj).length;
            var w = $("li", obj).width();
            var h = $("li", obj).height();
            var wz =$(this, obj).width();
            var hz = $(this, obj).height();
            var clickable = true;
            obj.width(wz);
            obj.height(hz);
            obj.css("overflow","hidden");
            var number = wz/w;
            var n = Math.floor(number)
            var ts = s-n;
            var t = 0;
            $("ul", obj).css('width',s*w);

            if(options.continuous){
                $("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
                $("ul", obj).append($("ul li:nth-child(2)", obj).clone());
                $("ul", obj).css('width',(s+1)*w);
            };

            if(!options.vertical) $("li", obj).css('float','left');

            if(options.controlsShow){
                var html = options.controlsBefore;
                if(options.numeric){
                    html += '<ol id="'+ options.numericId +'"></ol>';
                } else {
                    if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
                    html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
                    html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
                    if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
                };

                html += options.controlsAfter;
                $(obj).after(html);
            };

            if(options.numeric){
                for(var i=0;i<s;i++){
                    $(document.createElement("li"))
                        .attr('id',options.numericId + (i+1))
                        .html('<a rel='+ i +' href=\"javascript:void(0);\">'+ (i+1) +'</a>')
                        .appendTo($("#"+ options.numericId))
                        .click(function(){
                            animate($("a",$(this)).attr('rel'),true);
                        });
                };
            } else {
                $("a","#"+options.nextId).click(function(){
                    animate("next",true);
                });
                $("a","#"+options.prevId).click(function(){
                    animate("prev",true);
                });
                $("a","#"+options.firstId).click(function(){
                    animate("first",true);
                });
                $("a","#"+options.lastId).click(function(){
                    animate("last",true);
                });
            };
            function setCurrent(i){
                i = parseInt(i)+1;
                $("li", "#" + options.numericId).removeClass("current");
                $("li#" + options.numericId + i).addClass("current");
            };
            function adjust(){
                if(t>ts) t=0;
                if(t<0) t=ts;
                if(!options.vertical) {
                    $("ul",obj).css("margin-left",(t*w*-1));
                } else {
                    $("ul",obj).css("margin-left",(t*h*-1));
                }
                clickable = true;
                if(options.numeric) setCurrent(t);
            };
            function animate(dir,clicked){
                if (clickable){
                    clickable = false;
                    var ot = t;
                    switch(dir){
                        case "next":
                            t = (ot>=ts) ? (options.continuous ? t+1 : ts) : t+1;
                            break;
                        case "prev":
                            t = (t<=0) ? (options.continuous ? t-1 : 0) : t-1;
                            break;
                        case "first":
                            t = 0;
                            break;
                        case "last":
                            t = ts;
                            break;
                        default:
                            t = dir;
                            break;
                    };
                    var diff = Math.abs(ot-t);
                    var speed = diff*options.speed;
                    if(!options.vertical) {
                        p = (t*w*-1);
                        $("ul",obj).animate(
                            { marginLeft: p },
                            { queue:false, duration:speed, complete:adjust }
                        );
                    } else {
                        p = (t*h*-1);
                        $("ul",obj).animate(
                            { marginTop: p },
                            { queue:false, duration:speed, complete:adjust }
                        );
                    };
                    if(!options.continuous && options.controlsFade){
                        if(t==ts){
                            $("a","#"+options.nextId).hide();
                            $("a","#"+options.lastId).hide();
                        } else {
                            $("a","#"+options.nextId).show();
                            $("a","#"+options.lastId).show();
                        };
                        if(t==0){
                            $("a","#"+options.prevId).hide();
                            $("a","#"+options.firstId).hide();
                        } else {
                            $("a","#"+options.prevId).show();
                            $("a","#"+options.firstId).show();
                        };
                    };

                    if(clicked) clearTimeout(timeout);
                    if(options.auto && dir=="next" && !clicked){
                        timeout = setTimeout(function(){
                            animate("next",false);
                        },diff*options.speed+options.pause);
                    };
                };
            };
            // init
            var timeout;
            if(options.auto){
                timeout = setTimeout(function(){
                    animate("next",false);
                },options.pause);
            };
            if(options.numeric) setCurrent(0);
            if(!options.continuous && options.controlsFade){
                $("a","#"+options.prevId).hide();
                $("a","#"+options.firstId).hide();
            };
        });
    };

})(jQuery);



