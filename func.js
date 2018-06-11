function submitNew(){
  var sInput = $('#form1').serializeArray();
  
  $.ajax({
    type: "POST",
    url: "adminFunc.php",
    data: sInput
  }).done(function(resp){
    if (resp.indexOf("Hiba")>-1){
      alert(resp);
    }else{
      window.location.href = "admin.php";
    }
  }).fail(function() {
    alert('Error!');
  });   
}

function submitModify(){
  var sInput = $('#form1').serializeArray();
  
  $.ajax({
    type: "POST",
    url: "adminFunc.php",
    data: sInput
  }).done(function(resp){
    if (resp.indexOf("Hiba")>-1){
      alert(resp);
    }else{
      window.location.href = "admin.php";
    }
  }).fail(function() {
    alert('Error!');
  });   
}

function submitFinished(id){
  var sInput = "Type=finished&id="+id;

  $.ajax({
    type: "POST",
    url: "adminFunc.php",
    data: sInput,
  }).done(function(resp){
    if (resp.indexOf("Hiba")>-1){
      alert(resp);
    }else{
      window.location.href = "admin.php";
    }
  }).fail(function() {
    alert('Error!');
  });   

}

function submitDelete(id){
  if (confirm("Biztos, hogy törli?")==false){
    return;
  }
  
  var sInput = "Type=delete&id="+id;

  $.ajax({
    type: "POST",
    url: "adminFunc.php",
    data: sInput,
  }).done(function(resp){
    if (resp.indexOf("Hiba")>-1){
      alert(resp);
    }else{
      window.location.href = "admin.php";
    }
  }).fail(function() {
    alert('Error!');
  });   
  
}

