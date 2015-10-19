$(window).load(function() {
        $('#gallery').nivoSlider({
                effect:'sliceUpDown',
                slices:6,
                animSpeed:350,
                pauseTime:4000,
                startSlide:0,
                directionNav:true,
                directionNavHide:false,
                controlNav:true,
                controlNavThumbs:false,
                controlNavThumbsFromRel:true,
                controlNavThumbsSearch: '.png',
                controlNavThumbsReplace: '_thumb.jpg',
                keyboardNav:true,
                pauseOnHover:true,
                manualAdvance:false,
                captionOpacity:0.8,
                beforeChange: function(){},
                afterChange: function(){},
                slideshowEnd: function(){}
        });
        // --> jQuery('#gallery').data('nivo:vars').stop = true; //Stop the Slider
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        $('#frmLogin').submit();
    }
});