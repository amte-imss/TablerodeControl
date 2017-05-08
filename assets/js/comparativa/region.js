// $num = '3',$year='2016', $type=Comparativa_model::PERFIL
// PERFIL = 1,
// TIPO_CURSO = 2;
function comparativa(num,year,type,mod,region){
	var region_url = site_url+"/comparativa/"+mod+"/"+num+"/"+year+"/"+type;
	if(region > 0){
		region_url += "/"+region;
	}
	//alert(region_url)
 	window.location.replace(region_url);
}
$(document).ready(function (){
	var reporte= "tc";
	var year = new Date().getFullYear()-1;
	var num = "0";
	var region = 0;
	$(".tipo_reporte").click(function(){
		//alert($(this).data("id"))
		reporte = $(this).data("id");
		// alert($(this).text())
		if("tc" == reporte){
			$("#div_tc").show();
			$("#div_p").hide();
		}else if("p" == reporte){
			$("#div_p").show();
			$("#div_tc").hide();
		}
		$("#span_num").text("");
		$("#span_type").text($(this).text()+": ");
	});
	$(".perfil, .tipo_curso ").click(function(){
		//alert($(this).data("id"))
		// alert(reporte)
		$("#span_num").text($(this).text());
		num  = $(this).data("id");
		//region(num,year,reporte);
	});
	$(".anio").click(function(){
		//alert($(this).data("id"))
		year  = $(this).data("id");
		$("#span_anio").html("<b>Año: </b>"+$(this).text());
	});
	$(".region").click(function(){
		//alert($(this).data("id"))
		region  = $(this).data("id");
		$("#span_region").html("<b>Región: </b>"+$(this).text());
	});
	$("#submit").click(function (){
		var mod = $(this).data("id");
		if(num < 1){
			alert("Debe seleccionar los filtros, antes de realizar una comparación");
		}else{
			comparativa(num,year,reporte,mod,region);
		}
	});
});