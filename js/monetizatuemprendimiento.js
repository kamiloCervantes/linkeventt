/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var index = {};

var clock;
index.init = function(){
    new WOW().init();    
    

    clock = $('.clock').FlipClock({
    clockFace: 'DailyCounter',
    autoStart: false,
    language:'es-es',
    callbacks: {
            stop: function() {
                    $('.message').html('Se ha terminado la espera!')
            }
    }
    });
    
    var t1 = new Date('2019/07/27');
    var t2 = new Date();
    var dif = t1.getTime() - t2.getTime();

    var Seconds_from_T1_to_T2 = dif / 1000;
    var Seconds_Between_Dates = Math.abs(Seconds_from_T1_to_T2);
    console.log(Seconds_Between_Dates);
    clock.setTime(Seconds_Between_Dates);
    clock.setCountdown(true);
    clock.start();

	
    
    var scrollLink = $('.scroll');
  
    // Smooth scrolling
    scrollLink.click(function(e) {
      e.preventDefault();
      $('body,html').animate({
        scrollTop: $(this.hash).offset().top
      }, 1000 );
    });
    
    $('#carouselExampleControls').carousel({
        interval: 3000
    });
    
    $('.count-text').each(function() {
        var $this = $(this),
            countTo = $this.attr('data-count');

        $({ countNum: $this.text()}).animate({
          countNum: countTo
        },

        {

          duration: 8000,
          easing:'linear',
          step: function() {
            $this.text(Math.floor(this.countNum));
          },
          complete: function() {
            $this.text(this.countNum);
            //alert('finished');
          }

        });  

    });
    
    $(document).on('click', 'a.nav-link-scroll', function (event) {
        event.preventDefault();

        $('html, body').animate({
            scrollTop: $($(this).data('target')).offset().top
        }, 500);
    });
    
    $('.animate-hover').on('mouseover', function(e){
        console.log("animate");
        $(this).addClass('animated heartBeat').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', 
        function(){
            console.log("stop animate");
            $(this).removeClass('animated heartBeat');
        });
    })
}


$(index.init);

