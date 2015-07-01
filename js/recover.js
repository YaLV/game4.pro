$(document).ready(function() {
  
  $.post("/recover",document.location.search.substr(1,document.location.search.length), function(data){
    res=$.parseJSON(data);
    pop(res.response,"document.location.href='/'");
  });
});