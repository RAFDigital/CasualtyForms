/*
 * Application
 */

$(document).tooltip({
    selector: "[data-toggle=tooltip]"
})


/*
 * Auto hide navbar
 */
jQuery(document).ready(function($){
    var $header = $('.navbar-autohide'),
        scrolling = false,
        previousTop = 0,
        currentTop = 0,
        scrollDelta = 10,
        scrollOffset = 150;

    $(window).on('scroll', function(){
        if (!scrolling) {
            scrolling = true

            if (!window.requestAnimationFrame) {
                setTimeout(autoHideHeader, 250)
            }
            else {
                requestAnimationFrame(autoHideHeader)
            }
        }
    })

    function autoHideHeader() {
        var currentTop = $(window).scrollTop()

        // Scrolling up
        if (previousTop - currentTop > scrollDelta) {
            $header.removeClass('is-hidden')
        }
        else if (currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
            // Scrolling down
            $header.addClass('is-hidden')
        }

        previousTop = currentTop
        scrolling = false
    }
});


/**
 * InactivityTimeout
 * Module for the inactivity timeout.
 */
var InactivityTimeout = (function(exports) {
    'use strict';

    /* Vars. */
    var inactivityTime = 5*1000,
        timeout = setTimeout(userLogout, inactivityTime);

    /**
     * Function to action the user logout.
     */
    function userLogout() {
        //alert('Your session has timed out.');
        console.log('Your session has timed out.');
    }

    /**
     * Event handler for mousemove to detect inactivity.
     */
    document.onclick = function() {
        console.log('Reset timeout.');
        clearTimeout(timeout);
        timeout = setTimeout(userLogout, inactivityTime);
    }

  return exports;
}(InactivityTimeout || {}));


/**
 * ImageZoom
 * Module for the form image zoom and pan.
 */
var ImageZoom = (function(exports) {
    'use strict';

    /* Vars. */
    var canvas = document.getElementById('image-zoom'),
        formImg = new Image,
        scaleFactor = 1.1,
        dragStart, dragged;

    /**
     * Initialise image zoom.
     */
    function initialise() {
        var ctx = canvas.getContext('2d'),
            lastX = canvas.width / 2,
            lastY = canvas.height / 2;

        // Assign the width and height.
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        // Get the image source.
        formImg.src = canvas.firstElementChild.src;

        // Track the movement.
        trackTransforms(ctx);

        // Redraw canvas.
        redraw(ctx);

        canvas.addEventListener('mousedown',function(evt) {
            document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = 'none';
            lastX = evt.offsetX || (evt.pageX - canvas.offsetLeft);
            lastY = evt.offsetY || (evt.pageY - canvas.offsetTop);

            dragStart = ctx.transformedPoint(lastX,lastY);

            dragged = false;
        },false);

        canvas.addEventListener('mousemove',function(evt) {
            lastX = evt.offsetX || (evt.pageX - canvas.offsetLeft);
            lastY = evt.offsetY || (evt.pageY - canvas.offsetTop);
            dragged = true;

            if (dragStart) {
                var pt = ctx.transformedPoint(lastX,lastY);
                ctx.translate(pt.x-dragStart.x,pt.y-dragStart.y);
                redraw(ctx);
            }
        },false);

        canvas.addEventListener('mouseup',function(evt) {
            dragStart = null;
            if (!dragged) zoom(evt.shiftKey ? -1 : 1 );
        },false);

        function zoom(clicks) {
            var pt = ctx.transformedPoint(lastX,lastY);
            ctx.translate(pt.x, pt.y);
            var factor = Math.pow(scaleFactor,clicks);
            ctx.scale(factor,factor);
            ctx.translate(-pt.x, -pt.y);

            redraw(ctx);
        }

        function handleScroll(evt) {
            var delta = evt.wheelDelta ? evt.wheelDelta/40 : evt.detail ? -evt.detail : 0;
            if (delta) zoom(delta);
            return evt.preventDefault() && false;
        }

        canvas.addEventListener('DOMMouseScroll', handleScroll, false);
        canvas.addEventListener('mousewheel', handleScroll, false);
    }

    /**
     * Redraw the canvas.
     */
    function redraw(ctx) {
        // Clear the entire canvas
        var p1 = ctx.transformedPoint(0, 0);
        var p2 = ctx.transformedPoint(canvas.width, canvas.height);
        ctx.clearRect(p1.x, p1.y, p2.x - p1.x, p2.y - p1.y);

        ctx.save();
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.restore();

        ctx.drawImage(formImg, 0, 0, canvas.width, canvas.width * formImg.height / formImg.width);
    }

    /**
     * Adds ctx.getTransform() - returns an SVGMatrix
     * Adds ctx.transformedPoint(x,y) - returns an SVGPoint
     */
    function trackTransforms(ctx) {
        var svg = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
        var xform = svg.createSVGMatrix();
        ctx.getTransform = function(){ return xform; };

        var savedTransforms = [];
        var save = ctx.save;
        ctx.save = function() {
            savedTransforms.push(xform.translate(0, 0));
            return save.call(ctx);
        };

        var restore = ctx.restore;
        ctx.restore = function() {
            xform = savedTransforms.pop();
            return restore.call(ctx);
        };

        var scale = ctx.scale;
        ctx.scale = function(sx, sy) {
            xform = xform.scaleNonUniform(sx, sy);
            return scale.call(ctx, sx, sy);
        };

        var rotate = ctx.rotate;
        ctx.rotate = function(radians) {
            xform = xform.rotate(radians * 180 / Math.PI);
            return rotate.call(ctx, radians);
        };

        var translate = ctx.translate;
        ctx.translate = function(dx, dy) {
            xform = xform.translate(dx, dy);
            return translate.call(ctx, dx, dy);
        };

        var transform = ctx.transform;
        ctx.transform = function(a, b, c, d, e, f) {
            var m2 = svg.createSVGMatrix();
            m2.a = a; m2.b = b; m2.c = c; m2.d = d; m2.e = e; m2.f = f;
            xform = xform.multiply(m2);
            return transform.call(ctx, a, b, c, d, e, f);
        };

        var setTransform = ctx.setTransform;
        ctx.setTransform = function(a, b, c, d, e, f) {
            xform.a = a;
            xform.b = b;
            xform.c = c;
            xform.d = d;
            xform.e = e;
            xform.f = f;
            return setTransform.call(ctx, a, b, c, d, e, f);
        };

        var pt  = svg.createSVGPoint();
        ctx.transformedPoint = function(x, y) {
            pt.x = x; pt.y = y;
            return pt.matrixTransform(xform.inverse());
        }
    }

    /**
     * Event handler for initialisation
     */
    window.onload = function() {
        // Initialise the zoom panel if it's present on the page.
        if( canvas ) initialise();
    }

  return exports;
}(ImageZoom || {}));
