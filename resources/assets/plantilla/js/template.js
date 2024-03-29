// var firebaseConfig = {
//   apiKey: "AIzaSyDgvieNQcyHU0su0TjcFvMjeOJTd6nVKBo",
//   authDomain: "ascinalss-movil.firebaseapp.com",
//   databaseURL: "https://ascinalss-movil.firebaseio.com",
//   projectId: "ascinalss-movil",
//   storageBucket: "ascinalss-movil.appspot.com",
//   messagingSenderId: "361410683377",
//   appId: "1:361410683377:web:ed3a5b6a0465461d08e450",
//   measurementId: "G-3YRJMYB905"
//   }; 
//   firebase.initializeApp(firebaseConfig);  
//   firebase.auth().signInWithEmailAndPassword('ascinalss.dep.sistemas@gmail.com','desarrollo20201'); 
 
$(window).on('keydown', function (e) {

  if (e.keyCode == 39) {
    

    if ($(document.activeElement).is("input") && $(document.activeElement).hasClass("inputnext")) {
      e.preventDefault();
      var numero = parseInt($(document.activeElement).attr("inputvalued"));
      numero = numero + 1;
      if ($(document.activeElement).parents(".filacontable").find('#input' + numero).attr("disabled") == 'disabled') {
        numero = numero + 1;
      }  
      var out = $(document.activeElement).parents(".filacontable").find('#input' + numero);
      out.focus();
    }
  } else if (e.keyCode == 37) {
    

    if ($(document.activeElement).is("input") && $(document.activeElement).hasClass("inputnext")) {
      e.preventDefault();
      var numero = parseInt($(document.activeElement).attr("inputvalued"));
      numero = numero - 1;

      if ($(document.activeElement).parents(".filacontable").find('#input' + numero).attr("disabled") == 'disabled') {
        numero = numero - 1;
      }
      var out = $(document.activeElement).parents(".filacontable").find('#input' + numero);
      out.focus();
    }
  }else if (e.keyCode == 40) {
    

    if ($(document.activeElement).is("input") && $(document.activeElement).hasClass("inputnext")) {
      e.preventDefault();
     var numero= parseInt($(document.activeElement).parents(".filacontable").attr("filaindex"));
     numero = numero + 1;

     if ($(document.activeElement).parents("#contenidoValue").find('#filaRow' + numero).find('#'+$(document.activeElement).attr('id')).attr("disabled") == 'disabled') {
       numero = numero + 1;
     }
     $('#filaRow' + numero).find('#'+$(document.activeElement).attr('id')).focus();
    }
  }else if (e.keyCode == 38) {
   

    if ($(document.activeElement).is("input") && $(document.activeElement).hasClass("inputnext")) {
      e.preventDefault();
     var numero= parseInt($(document.activeElement).parents(".filacontable").attr("filaindex"));
     numero = numero - 1;

     if ($(document.activeElement).parents("#contenidoValue").find('#filaRow' + numero).find('#'+$(document.activeElement).attr('id')).attr("disabled") == 'disabled') {
       numero = numero - 1;
     }
     $('#filaRow' + numero).find('#'+$(document.activeElement).attr('id')).focus();
    }
  }else if (e.keyCode == 13) {
   

    if ($(document.activeElement).is("input") && $(document.activeElement).hasClass("inputnext")) {
      e.preventDefault(); 
      var numero = parseInt($(document.activeElement).attr("inputvalued"));
      numero = numero + 1;
      if ($(document.activeElement).parents(".filacontable").find('#input' + numero).attr("disabled") == 'disabled') {
        numero = numero + 1;
      }  
      var out = $(document.activeElement).parents(".filacontable").find('#input' + numero); 
      if(out.length>0){
        out.focus();
      }else{
        $(document.activeElement).parents("#contenglobalButton").find('#botonAddAjax').click();
      }
    }
    
  }
});
/*****
* CONFIGURATION
*/
  // Active ajax page loader
  $.ajaxLoad = true;

  //required when $.ajaxLoad = true
  //$.defaultPage = 'main.html';
  //$.subPagesDirectory = 'views/';
  //$.page404 = 'views/pages/404.html';
  $.mainContent = $('#ui-view');

  //Main navigation
  $.navigation = $('nav > ul.nav');

  $.panelIconOpened = 'icon-arrow-up';
  $.panelIconClosed = 'icon-arrow-down';

  //Default colours
  $.brandPrimary =  '#20a8d8';
  $.brandSuccess =  '#4dbd74';
  $.brandInfo =     '#63c2de';
  $.brandWarning =  '#f8cb00';
  $.brandDanger =   '#f86c6b';

  $.grayDark =      '#2a2c36';
  $.gray =          '#55595c';
  $.grayLight =     '#818a91';
  $.grayLighter =   '#d1d4d7';
  $.grayLightest =  '#f8f9fa';

'use strict';

/*****
* ASYNC LOAD
* Load JS files and CSS files asynchronously in ajax mode
*/
function loadJS(jsFiles, pageScript) {

  var i;
  for(i = 0; i<jsFiles.length;i++){

    var body = document.getElementsByTagName('body')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = false;
    script.src = jsFiles[i];
    body.appendChild(script);
  }

  if (pageScript) {
    var body = document.getElementsByTagName('body')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = false;
    script.src = pageScript;
    body.appendChild(script);
  }

  init();
}

function loadCSS(cssFile, end, callback) {

  var cssArray = {};

  if (!cssArray[cssFile]) {
    cssArray[cssFile] = true;

    if (end == 1) {

      var head = document.getElementsByTagName('head')[0];
      var s = document.createElement('link');
      s.setAttribute('rel', 'stylesheet');
      s.setAttribute('type', 'text/css');
      s.setAttribute('href', cssFile);

      s.onload = callback;
      head.appendChild(s);

    } else {

      var head = document.getElementsByTagName('head')[0];
      var style = document.getElementById('main-style');

      var s = document.createElement('link');
      s.setAttribute('rel', 'stylesheet');
      s.setAttribute('type', 'text/css');
      s.setAttribute('href', cssFile);

      s.onload = callback;
      head.insertBefore(s, style);

    }

  } else if (callback) {
    callback();
  }

}

/****
* AJAX LOAD
* Load pages asynchronously in ajax mode
*/

if ($.ajaxLoad) {

  var paceOptions = {
    elements: false,
    restartOnRequestAfter: false
  };
  Pace.on("done", function() {  
    $('.onload').fadeOut( "slow", function() { 
      $("#app").fadeIn("slow");
    }); 
    $('.onloaditem').fadeOut("slow"); 
 });
 Pace.on("start", function() {  
   if($('#app').is(':visible')){
    $(".onloaditem").fadeIn("slow");
   }else{
    $(".onload").fadeIn("slow");
   }
});
  var url = location.hash.replace(/^#/, '');

  if (url != '') {
    setUpUrl(url);
  } else {
    setUpUrl($.defaultPage);
  }

  $(document).on('click', '.nav a[href!="#"]', function(e) {
    if ( $(this).parent().parent().hasClass('nav-tabs') || $(this).parent().parent().hasClass('nav-pills') ) {
      e.preventDefault();
    } else if ( $(this).attr('target') == '_top' ) {
      e.preventDefault();
      var target = $(e.currentTarget);
      window.location = (target.attr('href'));
    } else if ( $(this).attr('target') == '_blank' ) {
      e.preventDefault();
      var target = $(e.currentTarget);
      window.open(target.attr('href'));
    } else {
      e.preventDefault();
      var target = $(e.currentTarget);
      setUpUrl(target.attr('href'));
    }
  });

  $(document).on('click', 'a[href="#"]', function(e) {
    e.preventDefault();
  });
}

function setUpUrl(url) {

  $('nav .nav li .nav-link').removeClass('active');
  $('nav .nav li.nav-dropdown').removeClass('open');
  //$('nav .nav li:has(a[href="' + url.split('?')[0] + '"])').addClass('open');
  //$('nav .nav a[href="' + url.split('?')[0] + '"]').addClass('active');

  loadPage(url);
}

function loadPage(url) {

  $.ajax({
    type : 'GET',
    url : $.subPagesDirectory + url,
    dataType : 'html',
    cache : false,
    async: false,
    beforeSend : function() {
      $.mainContent.css({ opacity : 0 });
    },
    success : function() {
      Pace.restart(); 
      $('html, body').animate({ scrollTop: 0 }, 0);
      //$.mainContent.load($.subPagesDirectory + url, null, function (responseText) {
      //  window.location.hash = url;
      //}).delay(250).animate({ opacity : 1 }, 0);
    },
    error : function() {
      //window.location.href = $.page404;
    }

  });
}


/****
* MAIN NAVIGATION
*/

$(document).ready(function($){
  $("·closeItem").on('click',function(event) {
    event.preventDefault();
    console.log('entrooooo');
});

  // Add class .active to current link - AJAX Mode off
  // $.navigation.find('a').each(function(){

  //   var cUrl = String(window.location).split('?')[0];

  //   if (cUrl.substr(cUrl.length - 1) == '#') {
  //     cUrl = cUrl.slice(0,-1);
  //   }

  //   if ($($(this))[0].href==cUrl) {
  //     $(this).addClass('active');

  //     $(this).parents('ul').add(this).each(function(){
  //       $(this).parent().addClass('open');
  //     });
  //   }
  // });

  // Dropdown Menu
  $.navigation.on('click', 'a', function(e){ 
    if ($.ajaxLoad) {
      e.preventDefault();
    }
    if ($(this).hasClass('nav-dropdown-toggle')) { 
      if ($(this).parent().hasClass('open')) {
        $(this).parent().removeClass("open"); 
      }else{ 
        $(".nav-dropdown").removeClass("open");
        $(this).parent().toggleClass('open');
        resizeBroadcast();
      }
    } 
  });

  function resizeBroadcast() {

    var timesRun = 0;
    var interval = setInterval(function(){
      timesRun += 1;
      if(timesRun === 5){
        clearInterval(interval);
      }
      window.dispatchEvent(new Event('resize'));
    }, 62.5);
  }

  /* ---------- Main Menu Open/Close, Min/Full ---------- */
  $('.sidebar-toggler').click(function(){
    $('#app').toggleClass('sidebar-hidden');
    resizeBroadcast();
  });

  $('.sidebar-minimizer').click(function(){
    $('#app').toggleClass('sidebar-minimized');
    resizeBroadcast();
  });

  $('.brand-minimizer').click(function(){
    $('#app').toggleClass('brand-minimized');
  });

 

  $('.mobile-sidebar-toggler').click(function(){
    $('#app').toggleClass('sidebar-mobile-show');
    resizeBroadcast();
  });

  $('.sidebar-close').click(function(){
    $('#app').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
  });

  /* ---------- Disable moving to top ---------- */
  $('a[href="#"][data-top!=true]').click(function(e){
    e.preventDefault();
  });

});

/****
* CARDS ACTIONS
*/

$(document).on('click', '.card-actions a', function(e){
  e.preventDefault();

  if ($(this).hasClass('btn-close')) {
    $(this).parent().parent().parent().fadeOut();
  } else if ($(this).hasClass('btn-minimize')) {
    var $target = $(this).parent().parent().next('.card-block');
    if (!$(this).hasClass('collapsed')) {
      $('i',$(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
    } else {
      $('i',$(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
    }

  } else if ($(this).hasClass('btn-setting')) {
    $('#myModal').modal('show');
  }

});

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url) {

  /* ---------- Tooltip ---------- */
  $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

  /* ---------- Popover ---------- */
  $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}
