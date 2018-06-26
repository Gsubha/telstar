$(function() {
 
 /* Sidebar tree view */
    $('ul').tree("trigger");
    
 var height = $('.content-wrapper:visible').css('height');
 $(".customsidebar").height(height);
});