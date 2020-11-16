	var myVar;
	
	function play(event,idA){
		
		
		var i = false;
		var flag = true;
		var g = 4;
		var x = event.target;
		
		
		
		if(document.getElementById(idA).getElementsByTagName("circle")[5].style.fill != "red" && document.getElementById(idA).getElementsByTagName("circle")[5].style.fill != "blue" ){
		
		document.getElementById(idA).getElementsByTagName("circle")[5].style.fill = "red";
		
		}else{
			while(i == false){
				if(document.getElementById(idA).getElementsByTagName("circle")[g].style.fill == "red" || document.getElementById(idA).getElementsByTagName("circle")[g].style.fill == "blue"){
					g --;
					
				}else if(g < 0){
					var flag = false;
				}
				else{
					document.getElementById(idA).getElementsByTagName("circle")[g].style.fill = "red";
					i = true;
					Wewin(idA,g);
				}
			}
		}
		
		if(flag = true){
			 
			 
			 myVar = setInterval(AIplays, 1000);
		}
}
	
	function Wewin(idA,g){
	
		var clr = document.getElementById(idA).getElementsByTagName("circle")[g].style.fill; 
		
		for (var i = 0; i <=5; i ++){
			if(document.getElementById(idA).getElementsByTagName("circle")[i].style.fill = clr){
				
			}			
		}
			
	}


	function AIplays(){
	
		clearTimeout(myVar);
	
		var myArray = ["column-1","column-2","column-3","column-4","column-5","column-6","column-0",];

		var idA = myArray[Math.floor(Math.random()*myArray.length)];

		if(document.getElementById(idA).getElementsByTagName("circle")[0].style.fill == "red" || document.getElementById(idA).getElementsByTagName("circle")[0].style.fill == "blue"){
			AIplays();
		}else{
			AIplaysNow(idA);
		}
	
		
		
	}
	
	function AIplaysNow(idA){
		
		
		var i = false;
		var g = 4;
		
		
		
		if(document.getElementById(idA).getElementsByTagName("circle")[5].style.fill != "red" && document.getElementById(idA).getElementsByTagName("circle")[5].style.fill != "blue" ){
		
		document.getElementById(idA).getElementsByTagName("circle")[5].style.fill = "blue";
		
		}else{
			while(i == false){
			
				if(g >=0){
				if(document.getElementById(idA).getElementsByTagName("circle")[g].style.fill == "red" || document.getElementById(idA).getElementsByTagName("circle")[g].style.fill == "blue"){
					g --;
				}else{
					document.getElementById(idA).getElementsByTagName("circle")[g].style.fill = "blue";
					i = true;
				}
				}
				}
		}

	}
	
		
	
