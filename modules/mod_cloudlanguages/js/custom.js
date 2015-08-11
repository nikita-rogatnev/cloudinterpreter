jQuery(document).ready(function() {

    jQuery('.earth').click(function(e) {
        jQuery('.ListWithLanguages').toggle("slow");
        e.stopPropagation();
    })

    jQuery(document).click(function(){
        jQuery('.ListWithLanguages').hide("slow");

    });


});

