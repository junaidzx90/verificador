jQuery(function( $ ) {
	'use strict';

	$(document).on("click", ".verify_btn", function(e){
		let btn1 = $(this);
		if($("#vccoupon").val() !== ""){
			e.preventDefault();
			$.ajax({
				type: "get",
				url: verificador.ajaxurl,
				data: {
					action: "verficador_coupon_validation",
					coupon: $("#vccoupon").val()
				},
				dataType: "json",
				beforeSend: ()=>{
					$(".verificador_step_1").find('.vc_alert').addClass("dnone");
					btn1.parent().find('.vcloader').removeClass("dnone");
					btn1.attr("disabled", "disabled")
				},
				success: function (response) {
					e.stopImmediatePropagation()

					if(response.success){
						$(".verificador_step_1").addClass("vcnone");
						$(".verificador_step_2").removeClass("vcnone");
					}
					if(response.invalid){
						btn1.removeAttr("disabled")
						btn1.parent().find('.vcloader').addClass("dnone");
						$(".verificador_step_1").find('.vc_alert').removeClass("dnone");
					}
				}
			});
		}
	});

	$(document).on("click", ".usenowbtn", function(e){
		e.preventDefault();
		$(".verificador_step_2").addClass("vcnone");
		$(".verificador_step_3").removeClass("vcnone");
	});

	$(document).on("click", ".vcsend_btn", function(e){
		let btn2 = $(this);
		if($("#username").val() !== "" && $("#useremail").val() !== "" && $("#store_manager_name").val() !== ""){
			e.preventDefault();
			
			let data = {
				coupon: $("#vccoupon").val(),
				username: $("#username").val(),
				useremail: $("#useremail").val(),
				manager_name: $("#store_manager_name").val()
			}

			$.ajax({
				type: "post",
				url: verificador.ajaxurl,
				data: {
					action: "send_validation_data",
					data: data
				},
				dataType: "json",
				beforeSend: ()=>{
					btn2.parent().find('.vcloader').removeClass("dnone");
					btn2.attr("disabled", "disabled")
				},
				success: function (response) {
					if(response.success){
						location.href = response.success;
					}
				}
			});
		}
	});
});

