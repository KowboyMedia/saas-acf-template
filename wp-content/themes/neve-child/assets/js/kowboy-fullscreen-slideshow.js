( function() {
    function setActiveSlide( slideshow, index, animate ) {
        var track = slideshow.querySelector( '.kowboy-fullscreen-slideshow__track' );
        var slides = slideshow.querySelectorAll( '.kowboy-fullscreen-slideshow__slide' );
        var dots = slideshow.querySelectorAll( '.kowboy-fullscreen-slideshow__dot' );
        var total = slides.length;

        if ( !total || !track ) {
            return 0;
        }

        var activeIndex = ( ( index % total ) + total ) % total;
        track.style.transition = animate ? 'transform 0.6s ease' : 'none';
        track.style.transform = 'translate3d(' + ( -activeIndex * 100 ) + '%, 0, 0)';

        slides.forEach( function( slide, slideIndex ) {
            slide.classList.toggle( 'is-active', slideIndex === activeIndex );
        } );

        dots.forEach( function( dot, dotIndex ) {
            dot.classList.toggle( 'is-active', dotIndex === activeIndex );
        } );

        return activeIndex;
    }

    function initSlideshow( slideshow ) {
        var track = slideshow.querySelector( '.kowboy-fullscreen-slideshow__track' );
        var slides = slideshow.querySelectorAll( '.kowboy-fullscreen-slideshow__slide' );
        var dots = slideshow.querySelectorAll( '.kowboy-fullscreen-slideshow__dot' );
        var autoplay = slideshow.getAttribute( 'data-autoplay' ) !== 'false';
        var interval = parseInt( slideshow.getAttribute( 'data-interval' ), 10 );
        var delay = Number.isFinite( interval ) && interval >= 1000 ? interval : 5000;
        var timer = null;
        var current = 0;
        var pointerDown = false;
        var startX = 0;
        var dragOffset = 0;
        var baseTranslate = 0;

        if ( slides.length <= 1 || !track ) {
            return;
        }

        current = setActiveSlide( slideshow, 0, false );

        function goTo( index ) {
            current = setActiveSlide( slideshow, index, true );
        }

        function updateDragPosition() {
            track.style.transform = 'translate3d(' + ( baseTranslate + dragOffset ) + 'px, 0, 0)';
        }

        function start() {
            if ( !autoplay || timer ) {
                return;
            }

            timer = window.setInterval( function() {
                goTo( current + 1 );
            }, delay );
        }

        function stop() {
            if ( !timer ) {
                return;
            }
            window.clearInterval( timer );
            timer = null;
        }

        dots.forEach( function( dot ) {
            dot.addEventListener( 'click', function() {
                var target = parseInt( dot.getAttribute( 'data-slide-index' ), 10 );
                if ( Number.isFinite( target ) ) {
                    goTo( target );
                    stop();
                    start();
                }
            } );
        } );

        slideshow.addEventListener( 'pointerdown', function( event ) {
            if ( event.pointerType === 'mouse' && event.button !== 0 ) {
                return;
            }

            pointerDown = true;
            startX = event.clientX;
            dragOffset = 0;
            baseTranslate = -current * slideshow.clientWidth;
            track.style.transition = 'none';
            stop();
        } );

        slideshow.addEventListener( 'pointermove', function( event ) {
            if ( !pointerDown ) {
                return;
            }

            dragOffset = event.clientX - startX;
            updateDragPosition();
        } );

        function endDrag() {
            if ( !pointerDown ) {
                return;
            }

            pointerDown = false;

            var width = slideshow.clientWidth || 1;
            var threshold = Math.max( 60, width * 0.15 );

            if ( dragOffset <= -threshold ) {
                goTo( current + 1 );
            } else if ( dragOffset >= threshold ) {
                goTo( current - 1 );
            } else {
                goTo( current );
            }

            dragOffset = 0;
            start();
        }

        slideshow.addEventListener( 'pointerup', endDrag );
        slideshow.addEventListener( 'pointercancel', endDrag );
        slideshow.addEventListener( 'pointerleave', function() {
            if ( pointerDown ) {
                endDrag();
            }
        } );

        window.addEventListener( 'resize', function() {
            setActiveSlide( slideshow, current, false );
        } );

        slideshow.addEventListener( 'mouseenter', stop );
        slideshow.addEventListener( 'mouseleave', start );

        start();
    }

    function boot() {
        var slideshows = document.querySelectorAll( '.wp-block-kowboy-fullscreen-slideshow' );
        slideshows.forEach( initSlideshow );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', boot );
    } else {
        boot();
    }
} )();
