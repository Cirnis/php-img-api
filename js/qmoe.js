$(document).ready(function(){
	$("#btn").click(function(){
		$("#upform").ajaxForm(function(data,status){
			if(data.code == 'success') {
				var url = data.url;
				$("#show").show();
				document.getElementById("linkurl").value = url;
				document.getElementById("imgurl").src = url;
			}	
			else {
				var msg = data.msg;
				$("#uperror").html(msg);
				$("#uperror").show();
				$("#uperror").fadeOut(3000);
			}
		});
	});
});
