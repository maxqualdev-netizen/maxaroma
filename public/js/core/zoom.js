/*!
 * jZoom.js
 *
 * Version 1.1.6
 *
 * https://github.com/pciapcib/jZoom
 *
 * MIT licensed
 *
 * Copyright (c) 2016 Shen Ting
 */
(function($) {
    'use strict';

    $.fn.jzoom = function(options) {
        return this.each(function() {
            // Set up default options
            var defaultOptions = {
                width: 400,
                height: 400,
                position: 'right',
                offsetX: 20,
                offsetY: 0,
                opacity: 0.6,
                bgColor: '#fff',
                //loading: 'Loading...'
            };

            // Custom options
            options = $.extend({}, defaultOptions, options);

            // Get container to add position and make the image having same width and height with container
            var $jzoom = $(this);
            var jzoomPosition = $jzoom.css('position');

            if (jzoomPosition === 'static') {
                $jzoom.css('position', 'relative');
            }
            $jzoom.find('img').css({
                //width: $jzoom.width() + 'px',
                //height: $jzoom.height() + 'px'
            });

            // Get lens div and add css
            var $jzoomLens = $('<div></div>');
            $jzoomLens.css({
                position: 'absolute',
                zIndex: '990',
                opacity: options.opacity,
                cursor: 'move',
                border: '1px solid #ccc',
                backgroundColor: options.bgColor
            });
			
			$jzoomLens.click(function(){
				$jzoomLens.css("display","none");
				$("#ExtraImages").trigger("click");
			});

            // Get zooming window and add css
            var $jzoomDiv = $('<div></div>');
            var jzoomDivLeft, jzoomDivTop;

            switch (options.position) {
                case 'top':
                    jzoomDivLeft = 0;
                    jzoomDivTop = -options.height - options.offsetY;
                    break;
                case 'right':
                    jzoomDivLeft = $jzoom.width() + options.offsetX;
                    jzoomDivTop = 0;
                    break;
                case 'bottom':
                    jzoomDivLeft = 0;
                    jzoomDivTop = $jzoom.height() + options.offsetY;
                    break;
                case 'left':
                    jzoomDivLeft = -options.width - options.offsetX;
                    jzoomDivTop = 0;
                    break;
            }

            $jzoomDiv.css({
                left: jzoomDivLeft + 'px',
                top: jzoomDivTop + 'px',
                width: options.width + 'px',
                height: options.height + 'px',
                position: 'absolute',
                zIndex: '999',
                overflow: 'hidden',
                border: '1px solid #ccc',
                fontSize: '20px',
                textAlign: 'center',
                lineHeight: options.height + 'px'
            });
            
            $jzoomDiv.addClass("setDiv");

            // Create <img> of big image and add loading text
            var $zoomImg = createZoomImg(options.suffixName, options.imgType);
            $jzoomDiv.append($zoomImg).append(options.loading);

            // Variables
            var flag = 0,
                JzoomOffset = $jzoom.offset(),
                CriticalX, CriticalY,
                finalX, finalY,
                DistProportionX, DistProportionY;

            // Mouse events
            $jzoom.mouseenter(function() {
                    $jzoomLens.show();
                    $jzoomDiv.show();

                    if (flag === 0) {
                        firstEnter();
                        flag++;
                    }				
					$(".sptitle").html("Click to expand");
					setTimeout(function(){$(".sptitle").hide()},600);
                })
                .mousemove(function(e) {
                    // Calculate coordinates of lens div
                    finalX = calcDistance(e.pageX, JzoomOffset.left, $jzoomLens.width(), CriticalX);
                    finalY = calcDistance(e.pageY, JzoomOffset.top, $jzoomLens.height(), CriticalY);

                    $jzoomLens.css({
                        left: finalX + 'px',
                        top: finalY + 'px'
                    });

                    // Calculate offsets of big images
                    $zoomImg.css({
                        left: -finalX * DistProportionX + 'px',
                        top: -finalY * DistProportionY + 'px'
                    });
                })
                .mouseleave(function() {
                    $jzoomLens.hide();
                    $jzoomDiv.hide();
					$(".sptitle").show();
					$(".sptitle").html("Hover to zoom");
					
                });

            /**
             * Create <img> of big image
             * @param  {String} suffixName 大图后缀 suffix name of big image
             * @param  {String} imgType    图片格式 image type of big image
             * @return {jQuery}            返回大图 big image
             */
            function createZoomImg(suffixName, imgType) {
                var imgSrc = $jzoom.find('img').attr('src');

                //suffixName = suffixName || '_big';
               // suffixName = suffixName;

                var point = imgSrc.lastIndexOf('.');

                imgType = imgType || imgSrc.substring(point + 1);

               // var newImgSrc = imgSrc.substring(0, point) + suffixName + '.' + imgType;
                var newImgSrc = suffixName;
                //var newImg = $('<img>').attr('src', newImgSrc).css('position', 'absolute');
                var newImg = $('<img>').attr('src', newImgSrc).css({"position":"absolute","max-width":"inherit"});

                return newImg;
            }

            /**
             * Trigger first mouse event
             * Only get the width and height of big image on first mouse event
             * so some calculation of variables are put in the function which depend on them
             */
            function firstEnter() {
                $jzoom.append($jzoomLens).append($jzoomDiv);

                // Calculate proportions of lens div
                var VolProportionX = $zoomImg.width() / $jzoom.width();
                var VolProportionY = $zoomImg.height() / $jzoom.height();

                $jzoomLens.css({
                    width: $jzoomDiv.width() / VolProportionX + 'px',
                    height: $jzoomDiv.height() / VolProportionY + 'px'
                });
				
                // Calculate critical coordinates of lens div
                CriticalX = $jzoom.width() - $jzoomLens.outerWidth();
                CriticalY = $jzoom.height() - $jzoomLens.outerHeight();

                // Calculate proportions of offsets of big image
                DistProportionX = ($zoomImg.width() - $jzoomDiv.width()) / CriticalX;
                DistProportionY = ($zoomImg.height() - $jzoomDiv.height()) / CriticalY;
            }

            /**
             * Calculate distance
             * @param  {Mumber} pageD     coordinates of mouse
             * @param  {Number} offsetD   offsets of container
             * @param  {Number} lensW     width or height of lens div
             * @param  {Number} criticalD critical coordinates of lens div
             * @return {Number}           coordinates of lens div
             */
            function calcDistance(pageD, offsetD, lensW, criticalD) {
                var finalD,
                    distance = pageD - offsetD - lensW / 2;

                if (distance >= 0 && distance <= criticalD) {
                    finalD = distance;
                } else if (distance < 0) {
                    finalD = 0;
                } else {
                    finalD = criticalD;
                }

                return finalD;
            }

            return this;
        });
    };
})(jQuery);
