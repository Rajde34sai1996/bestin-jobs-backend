jQuery(document).ready(function ($) {
	$(".user_mesuredata").hide();
	$('.assessment-data-table').DataTable();
	// yii2 flash message hide //
	$(".error_user").delay(5000).slideUp(300);
	var token = $("#clientToken").val();
	// console.log(token)
	if (token) {
		$(".success").delay(5000).slideUp(300);
	} else {
		$("ul.menu-content.scrapper").find('.scraper_logged_in').hide();
	}
	// yii2 flash message hide //

	// User Avatar Lightbox Click event //
	$('img').on('click', function () {
		var image = $(this).attr('src');
		var originalImg = image.replace("XSMALL_", "");
		$(".showimage").attr("src", originalImg);
	});
	// User Avatar Lightbox Click event //

	$('.select2-container').addClass("mt-2");

	//Admin Side Google location Drop Down//
	var location = document.getElementById('Location');
	if (typeof location != "undefined" && location != null) {
		initialize();
	}

	var location = document.getElementById('SetLocation');
	if (typeof location != "undefined" && location != null) {
		initializeOther();
	}


	tooltipRun();

	// Call PJAX
	jQuery(document).on("pjax:success", "body", function (event) {
		feather.replace();
		tooltipRun();
	});


	$('.field-hostevent-amount label').hide();
	/* Host Event Amount (input Field) Toggle  */
	$('#hostevent-payment_type').change(function () {
		if (this.value == 'paid') {
			$("#hostevent-amount").attr("disabled", false);
			$('.field-hostevent-amount').show();
			$('#hostevent-amount').show();
			$('.field-hostevent-amount label').show();
		} else {
			$("#hostevent-amount").attr("disabled", true);
			$('.field-hostevent-amount').hide();
			$('#hostevent-amount').hide();
			$('.field-hostevent-amount label').hide();
		}
	});

	$(document).on('click', '#Live', function (e) {
		var _def = {
            "frontend_username":"cc815cd27c7d11ebb4490050569d104a",
            "frontend_password":"WhNIlXHYFbKa6a2xlh",
            "backend_username":"fd8ce0ee7c7d11ebb4490050569d104a",
            "backend_password":"foztFho7sjLrvzrUD3",
            "client_account_number":"952756",
            "client_sub_account_number":"0",
            "sandbox_username":"ashok12",
            "sandbox_password":"HUUE@@22sm"
		};
		$.each(_def, function( index, value ) {
			$("#"+index).val(value);
		});
	});

	$(document).on('click', '#Sandbox', function (e) {
		var _def = {
            "frontend_username":"34618470599e11eabb010050569d23bb",
            "frontend_password":"JQX7mwuiaVRmBn0b9K9wfPska",
            "backend_username":"315fdb7a599c11eabb010050569d23bb",
            "backend_password":"awdDEAkcaDZZS1lC6P7muOGup",
            "client_account_number":"951908",
            "client_sub_account_number":"1002",
            "sandbox_username":"sndb1002",
            "sandbox_password":"HUUE@@22sm"
		};
		$.each(_def, function( index, value ) {
			$("#"+index).val(value);
		});
	});

	if ($('.dropzone_groovy').data("id") != "") {
		var options = {
			accept: function accept(file, done) {
				console.log("Uploaded");
				done();
			}
		};
		if ($('.dropzone_groovy').data('single')) {
			options.maxFiles = 1;
		}

		options.maxFiles = 8;

		if ($('.dropzone_groovy').data('multiple')) {
			options.maxFiles = $('.dropzone_groovy').data('multiple');
		}



		if ($('.dropzone_groovy').data('file-types')) {
			options.accept = function (file, done) {
				if ($('.dropzone_groovy').data('file-types').split('|').indexOf(file.type) === -1) {
					//   alert("Error! Files of this type are not accepted");
					done("Error! Files of this type are not accepted");
				} else {
					console.log("Uploaded");
					done();
				}
			};
		}

		if ($('.dropzone_groovy').attr('data-url')) {
			options.url = $('.dropzone_groovy').attr('data-url');
		}



		$(document).on('click', '.groovy_modal', function (e) {
			e.preventDefault();

			$("#myModal").modal({
				backdrop: 'static',
				keyboard: false
			});

		});

		$("#eventModal").on("hidden.bs.modal", function (e) {
			$(".attending_data").html("");
			$(".pending_data").html("");
		});

		$(document).on('click', '.event-attendance', function (e) {
			var id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: baseUrl + "/host-event/attendence",
				data: { 'id': id },
				dataType: "json",
				success: function (res) {
					if (res.status) {
						$.each(res.data.attending, function (key, value) {
							$(".attending_data").append("<tr data-key='4'>\
								<td><div class='w-10 h-10 image-fit zoom-in'><img class='rounded-full tooltipstered' src='"+ value['image'] + "'></div></td>\
								<td>"+ value['name'] + "<br><small style='color: gray;'>" + value['email'] + "</small></td>\
								<td style='float: right;'><span style='color: green;'>Attending</span><br><small>"+ value['time'] + "</small></td>\
							</tr>");

						});

						if (res.data.pending.length == 0) {
							$('.table-pending').hide();
						} else {
							$('.table-pending').show();
						}

						$.each(res.data.pending, function (key, value) {
							console.log(value)
							$(".pending_data").append("<tr data-key='4'>\
								<td><div class='w-10 h-10 image-fit zoom-in'><img class='rounded-full tooltipstered' src='"+ value['image'] + "'></div></td>\
								<td>"+ value['name'] + "<br><small style='color: gray;'>" + value['email'] + "</small></td>\
								<td style='float: right;'><span style='color: red;'>"+ value['status'] + "</span><br><small>" + value['time'] + "</small></td>\
							</tr>");
						});
					} else {
						swal({
							title: "Opps!",
							text: res.message,
							icon: "error",
						}); return;
					}
					$("#eventModal").modal({
						backdrop: 'static',
						keyboard: false
					});
				}
			});
		});

		$(document).on('click', '.image_data', function (e) {
			$("#basic-modal-preview .loader").show();
			$("#basic-modal-preview .dz-message").hide();
			e.preventDefault();
			$("#basic-modal-preview").modal({
				backdrop: 'static',
				keyboard: false
			});
			var userId = $(this).attr('data-id');
			var type = $(this).attr('data-type');
			initDropzones();
			var thisDropzone = {};
			$(".user_id_image").val(userId);
			$(".replaceImageCard").html("");
			$(".dropzone_groovy").attr("data-id", userId);
			$(".dropzone_groovy").attr("data-type", type);
			if (userId) {
				if ($('.dropzone_groovy').data("id") && $('.dropzone_groovy').data("id") != 0) {
					options.addRemoveLinks = true;
					$(".dropzone_groovy").find(".dz-preview").remove();
					$(".dropzone_groovy").removeClass('dz-started');
					options.sending = function (file, xhr, formData) {
						formData.append('id', userId);
						formData.append('type', type);
						formData.append('Images[file]', file);
					}
					options.removedfile = function (file) {
						$.ajax({
							type: "POST",
							url: baseUrl + "/user/image-remove",
							data: {
								'filesID': file.id,
							},
							dataType: "json",
							success: function (data) {
								if (data.status) {
									var _ref;
									return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
								} else {
									alert(data.message);
								}
							}
						});
					},
						options.init = function () {
							thisDropzone = this;

							thisDropzone.on("success", function (file, response) {
								file.serverId = response;
								var res = JSON.parse(response);
								if (!res.status) {
									this.defaultOptions.error(file, res.msg);
								}
							});

							$.ajax({
								type: "POST",
								url: baseUrl + "/user/get-image",
								data: {
									'userId': userId,
									'type': type,
								},
								dataType: "json",
								success: function (data) {
									$("#basic-modal-preview .loader").hide();
									$("#basic-modal-preview .loading_completed").show();
									$("#basic-modal-preview .dz-message").show();
									$(".dz-file-preview").remove();
									$.each(data.userimageData, function (key, value) {
										var mockFile = {
											name: value['image_name'],
											id: value['id']
										};
										thisDropzone.options.addedfile.call(thisDropzone, mockFile);
										thisDropzone.options.thumbnail.call(thisDropzone, mockFile, value.image_link);
										$(".dz-preview").addClass("dz-complete");
									});
									var _ref;
									return 0;
								}
							});
						};
				}

				Dropzone.autoDiscover = false;
				var dz = new Dropzone(".dropzone_groovy", options);
				dz.on("maxfilesexceeded", function (file) {
					alert("No more files please!");
				});
			}
		});
	}

	$('.select2').each(function () {
		let options = {}

		if ($(this).data('placeholder')) {
			options.placeholder = $(this).data('placeholder')
		}

		if ($(this).data('hide-search')) {
			options.minimumResultsForSearch = -1
		}
		if ($(this).hasClass('therapist_email')) {
			options.tags = true
		}

		$(this).select2(options)
	})

	$('input.scrapCheckbox').on('change', function () {
		$('input.scrapCheckbox').not(this).prop('checked', false);
	});

	$('#usernumber').on("select2:select", function (e) {
		if ($('#usernumber').val()) {
			$('#select2-usernumber-container').removeClass('error_groovy');
		}
		// what you would like to happen
	});


	// Csv Logic Functionality Start 

	var clear_timer;
	var _unique = false;
	$(document).on('click', '.csv-upload', function (e) {
		e.preventDefault();
		var _this = $(this);
		var _old_html = _this.html();

		var fileInputElement = document.getElementById("csvupload");
		var file = fileInputElement.files; // Images
		var form_data = new FormData();
		form_data.append('file', file[0]);

		var $validator = jQuery('.form_file_import').validate({
			errorClass: "error_groovy",
			rules: {
				csvupload: {
					required: true,
					checkValidFile: /(\.csv|\.xlsx)$/i,
				},
			},
			messages: {
				vertical_checkbox: "Please check any of this",
			},
			errorPlacement: function (error, element) {
				return true;
			},
		});
		var $valid = jQuery('.form_file_import').valid();

		if (!$valid) {
			$validator.focusInvalid();
			return false;
		} else {
			_this.attr('disabled', true);
			_this.html('Importing...');
			_this.addClass("cursor-disabled");

			// This function will get count of users without partner
			$.ajax({
				type: "POST",
				url: baseUrl + "/scrap-login/csv-file-count",
				data: form_data,
				dataType: "json",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					if (data.status) {
						// $('.endUser').text(data.total_line);
						_unique = data.unique;
						start_import(_this, _old_html);

						clear_timer = setInterval(get_import_data, 2000);
						// $('#total_data').text( data.count);
						$('#startProgress').attr("data-total", data.count)
					}else {
						swal({
							title: "Error",
							text: data.message,
							icon: "error",
						});
						_this.removeClass("cursor-disabled");
						_this.attr('disabled', false);
						_this.html(_old_html);
					}
				}
			})
		}
	});

	// This function will start importing data
	function start_import(_this, _old_html) {
		var fileInputElement = document.getElementById("csvupload");
		var file = fileInputElement.files; // Images
		$('#process').css('display', 'block');
		var form_data = new FormData();
		form_data.append('file', file[0]);
		form_data.append('unique', _unique);
		$.ajax({
			type: "POST",
			url: baseUrl + "/scrap-login/csv-file-upload",
			data: form_data,
			dataType: "json",
			contentType: false,
			cache: false,
			processData: false,
			success: function (data) {
				_this.removeClass("cursor-disabled");
				_this.attr('disabled', false);
				_this.html(_old_html);

				clearInterval(clear_timer);
				if (data.status) {
					swal({
						title: "Successfully",
						text: data.message,
						icon: "success",
						button: "Okay!",
					}).then(function () {
						window.location.reload();
					});

				} else {
					swal({
						title: "Error",
						text: data.message,
						icon: "error",
					});
				}
			}
		})
	}

	// This function will get imported data
	function get_import_data() {
		$.ajax({
			type: "POST",
			url: baseUrl + "/scrap-login/csv-user-file-count",
			data: {"unique":_unique},
			success: function (data) {
				var total_data = $('#startProgress').attr("data-total");
				var width = Math.round((parseInt(data) / total_data) * 100);
                console.log("total_data", total_data)
                console.log("Done", data)
				$('#startProgress').val(width);
				$(".animated-progress").show();;
				$(".animated-progress span").attr('data-progress', width);

				$(".animated-progress span").animate({
					width: $(".animated-progress span").attr("data-progress") + "%",
				},1000);

				$(".animated-progress span").text($(".animated-progress span").attr("data-progress") + "%");
				
				if (width >= 100) {
					clearInterval(clear_timer);
					$('#startProgress').val(0);
				}
			}
		})
	}

	// Csv Logic Functionality End 

	$(document).on('click', '.singleUserBtn', function (e) {
		$(".insertCsv").addClass('hide');
		$(".CoupleUser").addClass('hide');
		$(".singleUser").removeClass('hide');
	});
	$(document).on('click', '.coupleUserBtn', function (e) {
		$(".insertCsv").addClass('hide');
		$(".singleUser").addClass('hide');
		$(".CoupleUser").removeClass('hide');
	});

	// Scrap Logic Functionality Start 

	$(document).on('click', '.scrap-start', function (e) {
		e.preventDefault();
		if ($('#usernumber').val() == '') {
			$('#select2-usernumber-container').addClass('error_groovy');
		}
		var _check = $(".scrapCheckbox").is(":checked");
		console.log(_check)
		var $validator = jQuery('.scrapForm').validate({
			errorClass: "error_groovy",
			rules: {
				"vertical_checkbox[]": {
					required: true,
				},
				getlocation: {
					required: true,
				},
				setlocation: {
					required: true,
				},
				usernumber: {
					required: true,
				}
			},
			messages: {
				vertical_checkbox: "Please check any of this",
			},
			errorPlacement: function (error, element) {
				return true;
			},
		});
		var $valid = jQuery('.scrapForm').valid();

		if (!$valid) {
			$validator.focusInvalid();
			return false;
		} else {

			var checkBox = ($('.scrapCheckbox:checked').map(function () {
				return this.value;
			}).get().join(', '));
			var scrapUser = $('#usernumber').val();
			var clientToken = $("#clientToken").val();
			var getlocation = $("#Location").val();
			var getlat = $("#Lat").val();
			var getlng = $("#Lng").val();
			var setlocation = $("#SetLocation").val();
			var setlat = $("#Set-Lat").val();
			var setlng = $("#Set-Lng").val();

			swal({
				title: "Are you sure started scraping ?",
				text: "Once started, please do not reload or stop the process.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((Scraper_Start) => {
				if (Scraper_Start) {
					$(".flex").css("display", 'none');
					$(".loader_other").css("display", 'inherit');
					$(".loaderText").css("display", 'inherit');
					$.ajax({
						type: "POST",
						url: baseUrl + "/scrap-login/scrap-users",
						data: {
							'vertical_checkbox': checkBox,
							'user-number': scrapUser,
							'clientToken': clientToken,
							'getlocation': getlocation,
							'getlat': getlat,
							'getlng': getlng,
							'setlocation': setlocation,
							'setlat': setlat,
							'setlng': setlng,
						},
						dataType: "json",
						success: function (data) {
							$(".flex").css("display", 'inherit');
							$(".loader_other").css("display", 'none');
							$(".loaderText").css("display", 'none');
							if (data.status) {
								var skip_ids = '';
								if (data.count != 0) {
									if (data.count == 1) {
										skip_ids = data.count + " User is skip because that User's Race is not Match with ours.";
									} else {
										skip_ids = data.count + " Users are skip because those User's Race is not Match with ours.";
									}
								}
								swal({
									title: "Successfully",
									text: "Scraper data successfully created! " + skip_ids,
									icon: "success",
									button: "Okay!",
								}).then(function () {
									window.location.reload();
								});
							} else {
								swal({
									title: "Opps!",
									text: data.message,
									icon: "error",
									button: "Okay!",
								}).then(function () {
									window.location.reload();
								});
							}
						}
					});
				}
			});
		}
	});

	// Scrap Logic Functionality End 
	$("#userCheckAll").click(function () {
		$(".userCheck").prop("checked", this.checked);
	});

	$('.userCheck').click(function () {
		if ($('.userCheck:checked').length == $('.userCheck').length) {
			$('#userCheckAll').prop('checked', true);
		} else {
			$('#userCheckAll').prop('checked', false);
		}
	});

	$("#tripCheckAll").click(function () {
		$(".tripCheck").prop("checked", this.checked);
	});

	$('.tripCheck').click(function () {
		if ($('.tripCheck:checked').length == $('.tripCheck').length) {
			$('#tripCheckAll').prop('checked', true);
		} else {
			$('#tripCheckAll').prop('checked', false);
		}
	});
	// $("#userCheckAll").click(function (e) {
	// 	e.preventDefault();
	// 	$('.userCheck').prop('checked', this.checked);
	// });

	// Scrap Logic Functionality Start 
	$('#couplecountry').select2()
	$('#t_couplecountry').select2()
	var is_csv = $("#coupleis_csv").val();

	$(document).on('submit', '.CoupleUser', function (e) {
		e.preventDefault();
		var $validator = jQuery('.CoupleUser').validate({
			errorClass: "error_groovy",
			rules: {
				couplefirstname: {
					required: true,
				},
				coupleemail: {
					email: true,
					required: true,
				},
				couplecountry: {
					required: true,
				},
				couplephonenumber: {
					number: true,
					required: true,
				},
				couplegendertype: {
					required: true,
				},
				couplelastname: {
					required: true,
				},
				couplepassword: {
					required: true,
					minlength: 8
				},
				coupledistance: {
					number: true,
					required: function(element){
						return (is_csv == 'yes') ? true : false;
					},
				},
				couplebirthday: {
					required: true,
				},
				couplerace: {
					required: true,
				},
				t_couplefirstname: {
					required: true,
				},
				t_coupleemail: {
					email: true,
					required: true,
				},
				t_couplecountry: {
					required: true,
				},
				t_couplephonenumber: {
					number: true,
					required: true,
				},
				t_couplelastname: {
					required: true,
				},
				t_couplepassword: {
					required: true,
					minlength: 8
				},
				t_coupledistance: {
					number: true,
					required: function(element){
						return (is_csv == 'yes') ? true : false;
					},
				},
				t_couplebirthday: {
					required: true,
				},
				t_couplerace: {
					required: true,
				},
			},
			// errorPlacement: function (error, element) {
			// 	return true;
			// },
		});
		var $valid = jQuery('.CoupleUser').valid();

		if (!$valid) {
			$validator.focusInvalid();
			return false;
		} else {

			var formData = new FormData(this);
			$.ajax({
				type: "POST",
				url: baseUrl + "scrap-login/create-couple",
				data: formData,
				dataType: "json",
				cache: false,
				contentType: false,
				processData: false,
				success: function (res) {
					if (res.status) {
						swal({
							title: "Success",
							text: res.message,
							icon: "success",
							button: "Okay!",
						}).then((value) => {
							if (is_csv) {
								window.location.href = baseUrl + '/user-bind/couple-user';
							} else {
								window.location.reload();
							}
						});
					} else {
						swal({
							title: "Opps!",
							text: res.message,
							icon: "error",
						});
					}
				},
			});
		}
	});

	// Scrap Logic Functionality End 

	// Delete Check User Start

	$(document).on('click', '.deleteselectedusers', function (e) {

		var user_id = [];
		var type = $(this).data('type');
		$.each($("input[name='userCheck']:checked"), function () {
			if (typeof $(this).closest('tr').attr('data-key') != undefined) {
				if (type == 'couple') {
					user_id.push($(this).closest('tr').find("a.image_data").attr('data-id'));
					if ($(this).closest('tr').find("td:nth-child(5) a.image_data").attr('data-id') !== undefined) {
						user_id.push($(this).closest('tr').find("td:nth-child(5) a.image_data").attr('data-id'));
					}
				} else {
					user_id.push($(this).closest('tr').attr('data-key'));
				}
			}
		});

		if (user_id.length != 0) {
			swal({
				title: "Are you sure?",
				text: "You want to delete checked user?",
				icon: "info",
				buttons: true,
				closeOnClickOutside: true
			}).then(
				function (value) {
					if (value) {
						$('.loader_class').attr('id', 'show_loader');
						$.ajax({
							type: "POST",
							url: baseUrl + "/user/delete-check-users",
							data: { user_id: user_id },
							dataType: "json",
							success: function (res) {
								$('.loader_class').attr('id', '');
								if (res.status) {
									swal({
										title: "Success",
										text: res.message,
										icon: "success",
										button: "Okay!",
									}).then((value) => {
										window.location.reload();
									});
								} else {
									swal({
										title: "Opps!",
										text: res.message,
										icon: "error",
									});
								}
							},
							error: function (data) {
								$('.loader_class').attr('id', '');
								swal({
									title: "Opps!",
									text: 'Something went wrong. Please try again.!',
									icon: "error",
								});
							},
						});
					}
				});
		} else {
			swal({
				title: "Opps!",
				text: 'Please select user you want to delete',
				icon: "error",
			});
		}
	});

	// Delete Check User End

	// Delete Check Trip Start\

	$(document).on('click', '.deleteselectedtrip', function (e) {

		var favorite = [];
		$.each($("input[name='tripCheck']:checked"), function () {
			favorite.push($(this).closest('tr').attr('data-key'));
		});
		console.log(favorite.length)
		if (favorite.length != 0) {
			swal({
				title: "Are you sure?",
				text: "You want to delete check trip?",
				icon: "info",
				buttons: true,
				closeOnClickOutside: true
			}).then(
				function (value) {
					if (value) {
						$.ajax({
							type: "POST",
							url: baseUrl + "/host-event/delete-check-trip",
							data: { favorite: favorite },
							dataType: "json",
							success: function (res) {
								if (res.status) {
									swal({
										title: "Success",
										text: ' Check event deleted Successfully.',
										icon: "success",
										button: "Okay!",
									}).then((value) => {
										window.location.reload();
									});
								} else {
									swal({
										title: "Opps!",
										text: 'Something went wrong. Please try again.!',
										icon: "error",
									});
								}
							},
						});
					}
					/*Your Code Here*/
				});
		} else {
			swal({
				title: "Opps!",
				text: 'Please select trip you want to delete',
				icon: "error",
			});
		}
	});

	// Delete Check Trip End


	/** Delete Scrap User Start */
	$(document).on('click', '.deletescrapusers', function (e) {
		var type = $(this).data('type');
		$.ajax({
			type: "POST",
			url: baseUrl + "/user/delete-scrapusers",
			data: { count: true, type: type },
			dataType: "json",
			success: function (res) {
				if (res.count != 0) {
					swal({
						title: "Attention!",
						text: res.count + ' Scrap users will be deleted.',
						icon: "info",
						buttons: {
							catch: {
								text: 'Okay!',
								className: 'save_btn_about_you',
								ButtonColor: "#000000"
							},
							cancel: 'Cancel',
						},
					}).then((value) => {
						if (!value) throw null;

						$.ajax({
							type: "POST",
							url: baseUrl + "/user/delete-scrapusers",
							data: { type: type },
							dataType: "json",
							success: function (res) {
								swal({
									title: "Success",
									text: res.count + ' Scrap users deleted Successfully.',
									icon: "success",
									button: "Okay!",
								}).then((value) => {
									window.location.reload();
								});

							},
							error: function (data) {
								swal({
									title: "Opps!",
									text: 'Something went wrong. Please try again.!',
									icon: "error",
								});
							}
						});
					});
				} else {
					swal({
						title: "Opps!",
						text: "No Data Found.",
						icon: "error",
					});
				}

			}
		});
	});

	$(document).on('click', '.deleteallscrapusers', function (e) {
		var type = $(this).data('type');
		$.ajax({
			type: "POST",
			url: baseUrl + "/user/delete-all-scrapusers",
			data: { count: true, type: type },
			dataType: "json",
			success: function (res) {
				if (res.count != 0) {
					swal({
						title: "Attention!",
						text: res.count + ' Scrap users will be deleted.',
						icon: "info",
						buttons: {
							catch: {
								text: 'Okay!',
								className: 'save_btn_about_you',
								ButtonColor: "#000000"
							},
							cancel: 'Cancel',
						},
					}).then((value) => {
						if (!value) throw null;

						$.ajax({
							type: "POST",
							url: baseUrl + "/user/delete-all-scrapusers",
							data: { type: type },
							dataType: "json",
							success: function (res) {
								swal({
									title: "Success",
									text: res.count + ' Scrap users deleted Successfully.',
									icon: "success",
									button: "Okay!",
								}).then((value) => {
									window.location.reload();
								});

							},
							error: function (data) {
								swal({
									title: "Opps!",
									text: 'Something went wrong. Please try again.!',
									icon: "error",
								});
							}
						});
					});
				} else {
					swal({
						title: "Opps!",
						text: "No Data Found.",
						icon: "error",
					});
				}

			}
		});
	})
	/** Delete Scrap User End */

	$('.side-menu').on('click', function () {
		if ($(this).parent().find('ul').length) {
			if ($(this).parent().find('ul').first().is(':visible')) {
				$(this).find('.side-menu__sub-icon').removeClass('transform rotate-180')
				$(this).removeClass('side-menu--open')
				$(this).parent().find('ul').first().slideUp({
					done: function () {
						$(this).removeClass('side-menu__sub-open')
					}
				})
			} else {
				$(this).find('.side-menu__sub-icon').addClass('transform rotate-180')
				$(this).addClass('side-menu--open')
				$(this).parent().find('ul').first().slideDown({
					done: function () {
						$(this).addClass('side-menu__sub-open')
					}
				})
			}
		}
	});

	feather.replace({
		'stroke-width': 1.5
	})
	window.feather = feather


	$('.datepicker').each(function () {
		let options = {
			singleDatePicker: true,
			showDropdowns: true,
			minYear: 1901,
			maxDate: new Date(), 
			maxYear: parseInt(moment().format('YYYY'), 10),
			locale: {
				format: 'Y-MM-DD'
			  }
		}

		if ($(this).data('daterange')) {
			options.singleDatePicker = false
		}

		if ($(this).data('timepicker')) {
			options.timePicker = true
			options.locale = {
				format: 'Y-m-d hh:mm A'
			}
		}

		$(this).daterangepicker(options)
	})

	//Admin Side Google location Drop Down//

	/*    Trun Off Notification Reminder 
	setInterval(function() {
		var data = "";
		var url = $(this).attr('reminder-notification');
		$.ajax({
			url: "http://micbt.teamgroovy.com/best-in-hire/v1/user/reminder-notification",
			type: 'post',
			dataType: 'json',
			data: data
		})
		.done(function(response) {
			if (response.data.success == true) {
				alert("Wow");
			}
		})
		.fail(function() {
			console.log("error");
		});

		// alert("Message to alert every 5 seconds");
	}, 60000);    //10000 For One Second  //60000 For One Minute
	*/
});

// Google Location DropDown Function//
function initialize() {
	var options = {
		types: ['(cities)'],
	};
	var input = document.getElementById('Location');
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		// document.getElementById('city2').value = place.name;
		// console.log(place.name);
		// console.log(place.geometry.location.lat());
		// console.log(place.geometry.location.lng());

		for (var i = 0; i < place.address_components.length; i += 1) {
			var addressObj = place.address_components[i];
			for (var j = 0; j < addressObj.types.length; j += 1) {
				if (addressObj.types[j] === 'country') {
					$("#country").val(addressObj.long_name);
				}
				if (addressObj.types[j] === 'locality') {
					console.log(addressObj.types[j]);
					console.log(addressObj.long_name);
					$("#locality").val(addressObj.long_name);
				}
			}
		}

		document.getElementById('Lat').value = place.geometry.location.lat();
		document.getElementById('Lng').value = place.geometry.location.lng();
	});
}

// Google Location Set Location in scrap Function//
function initializeOther() {
	var options = {
		types: ['(cities)'],
	};
	var input = document.getElementById('SetLocation');
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		// document.getElementById('city2').value = place.name;
		// console.log(place.name);
		// console.log(place.geometry.location.lat());
		// console.log(place.geometry.location.lng());

		for (var i = 0; i < place.address_components.length; i += 1) {
			var addressObj = place.address_components[i];
			for (var j = 0; j < addressObj.types.length; j += 1) {
				if (addressObj.types[j] === 'country') {
					$("#country").val(addressObj.long_name);
				}
				if (addressObj.types[j] === 'locality') {
					$("#locality").val(addressObj.long_name);
				}
			}
		}

		document.getElementById('Set-Lat').value = place.geometry.location.lat();
		document.getElementById('Set-Lng').value = place.geometry.location.lng();
	});
}

function initDropzones() {
	$('.dropzone_groovy').each(function () {

		let dropzoneControl = $(this)[0].dropzone;
		if (dropzoneControl) {
			dropzoneControl.removeAllFiles();
			dropzoneControl.destroy();

		}
	});
}

function base64ToFile(dataURI, origFile) {
	var byteString, mimestring;

	if (dataURI.split(',')[0].indexOf('base64') !== -1) {
		byteString = atob(dataURI.split(',')[1]);
	} else {
		byteString = decodeURI(dataURI.split(',')[1]);
	}

	mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0];

	var content = new Array();
	for (var i = 0; i < byteString.length; i++) {
		content[i] = byteString.charCodeAt(i);
	}

	var newFile = new File(
		[new Uint8Array(content)], origFile.name, { type: mimestring }
	);


	// Copy props set by the dropzone in the original file

	var origProps = [
		"upload", "status", "previewElement", "previewTemplate", "accepted"
	];

	$.each(origProps, function (i, p) {
		newFile[p] = origFile[p];
	});

	return newFile;
}
// Google Location DropDown //
// Google Location DropDown Function//

function tooltipRun() {
	// Tooltipster
	$('.tooltip').each(function () {
		let options = {
			animation: 'fade',
			delay: 0,
			theme: 'tooltipster-punk',
		}

		if ($(this).data('event') == 'on-click') {
			options.trigger = 'click'
		}

		if ($(this).data('theme') == 'light') {
			options.theme = 'tooltipster-shadow'
		}

		if ($(this).data('side') !== undefined) {
			options.side = $(this).data('side')
		}

		$(this).tooltipster(options)
	});
}

function changeStatus(status, id) {
	if (status == 'reject') {
		swal({
			text: "Please give Reject Reason:",
			content: {
				element: "textarea",
			},
			buttons: {
				catch: {
					text: 'Send',
					className: 'reject_reason',
					ButtonColor: "#000000"
				},
				cancel: 'Close',
			},
		}).then((value) => {
			if (!value) throw null;

			reason = document.querySelector(".swal-content__textarea").value;
			ajax_verify_response(id, status, reason);
		});
	} else {
		ajax_verify_response(id, status);
	}
}

function ajax_verify_response(id, status, reason = '') {
	$.ajax({
		type: "POST",
		url: baseUrl + "/verification-request/response",
		data: {
			'id': id,
			'status': status,
			'reason': reason,
		},
		dataType: "json",
		success: function (res) {
			if (res.status) {
				swal({
					title: "Successfully",
					text: res.message,
					icon: "success",
					button: "Okay!",
				}).then(function () {
					window.location.reload();
				});
			} else {
				swal({
					title: "Opps!",
					text: res.message,
					icon: "error",
				});
			}
		}
	});
}

function changePromotion(status, id) {
	swal({
		text: "Are you sure you want to change?",
		buttons: {
			catch: {
				text: 'Yes',
				className: 'reject_reason',
			},
			cancel: 'No',
		},
	}).then((value) => {
		if (value) {
			$.ajax({
				type: "POST",
				url: baseUrl + "/host-event/change-promotion-status",
				data: {
					'id': id,
					'status': status,
				},
				dataType: "json",
				success: function (res) {
					if (res.status) {
						swal({
							title: "Successfully",
							text: res.message,
							icon: "success",
						}).then(function () {
							window.location.reload();
						});
					} else {
						swal({
							title: "Opps!",
							text: res.message,
							icon: "error",
						});
					}
				}
			});
		}
	});
}

var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		}
	}
};

$.validator.addMethod("checkValidFile", function (value, el, param) {
    if (!param.exec(value)) {
      return false;
    }
    return true;
}, "Only CSV & XLSX Files are allowed.");

$('.is_therapist').on('change', function () {
	var value = $(this).val();
	if(value == 1){
		$('.therapist_email').show();
	}else{
		$('.therapist_email').hide();
	}
});


// // For DASS-21
// function init(chartdata, dataif = '') {  // Chart declaration:
// 	if (dataif) {
// 		myBarChart.destroy();  // for Update Chart
// 	}
// 	myBarChart = new Chart(ctx, {
// 		type: 'line',
// 		data: chartdata,
// 		options: options
// 	});
// }
// if ($('#chart-dass').hasClass('chart-container')) {

// 	var canvas = document.getElementById("dass21-chart");
// 	var ctx = canvas.getContext('2d');
// 	var myBarChart;

// 	var Charttooltips;
// 	var valuetooltips;
// 	const footer = (tooltipItems) => {				  /* Set  value by stage */				
// 		tooltipItems.forEach(function (tooltipItem) {
// 			valuetooltips = Number(tooltipItem.raw);
// 			if (valuetooltips >= 0 && valuetooltips <= 4) {
// 				Charttooltips = "Normal";
// 			} else if (valuetooltips >= 5 && valuetooltips <= 6) {
// 				Charttooltips = "Mild";
// 			} else if (valuetooltips >= 7 && valuetooltips <= 10) {
// 				Charttooltips = "Moderate";
// 			} else if (valuetooltips >= 11 && valuetooltips <= 13) {
// 				Charttooltips = "Severe";
// 			} else if (valuetooltips >= 14) {
// 				Charttooltips = "Extreme";
// 			}
// 		});
// 		console.log("Charttooltips", Charttooltips);
// 		return Charttooltips;

// 	};
// 	var options = {   /*  Add Options For Chart */
// 		animation: true,
// 		title: {
// 			display: true,
// 			fontSize: 18,
// 			text: ['Depression Anxiety Stress Scale-21'],
// 			position: 'bottom'
// 		},
// 		maintainAspectRatio: false,
// 		plugins: {
// 			tooltip: {
// 				callbacks: {
// 					footer: footer,		
// 				}
// 			}
// 		}
// 	};
// 	var user_id = $('#chart-dass').attr('data-id'); // get id for show chart data to admin
	
// 	if(user_id != ''){
// 		$.ajax({
// 			type: "POST",
// 			url: baseUrl + "/user/get-data",  /* Send User And Date For check Data */
// 			data: {
// 				'date': 'All',					/* date: All for  last unsert user  */
// 				'user_id': user_id,
// 			},
// 			dataType: "json",
// 			success: function (res) {
// 				if (res.response) {
// 					var chartdata = {
// 						labels: res.date,
// 						datasets: [{
// 							label: 'Depression',
// 							fill: false,				/* foR Remonve borderback color  */
// 							borderColor: "#ab1b1b",
// 							backgroundColor: 'lightblue',
// 							borderWidth: 3,
// 							data: res.depression,		/* Add Data form Ajax Depression */
// 							order: 1,

// 						}, {
// 							label: 'Anxiety',
// 							fill: false,
// 							borderColor: "#0417a3",
// 							backgroundColor: 'lightblue',
// 							borderWidth: 3,
// 							data: res.anxiety,			/* Add Data form Ajax Anxiety */
// 							order: 2,

// 						}, {
// 							label: 'Stress',
// 							fill: false,
// 							borderColor: "#89007d",
// 							backgroundColor: 'lightblue',
// 							borderWidth: 3,
// 							data: res.stress,			/* Add Data form Ajax Stress */		
// 							order: 3,

// 						}]
// 					};
// 					init(chartdata);
// 				}
// 			}
// 		});
// 	}
// }

$('#chart-date').on('change', function () {
	var data = $(this).val();
	var user_id = $(this).attr('data-id');
	if (data != '') {
		$.ajax({
			type: "POST",
			url: baseUrl + "/user/get-data",
			data: {
				'date': data,
				'user_id': user_id,
			},
			dataType: "json",
			success: function (res) {
				if (res) {
					var chartdata = {
						labels: res.date,
						datasets: [{
							label: 'Depression',
							fill: false,
							borderColor: "#ab1b1b",
							backgroundColor: 'lightblue',
							borderWidth: 3,
							data: res.depression, /* Add Data form Ajax Depression */
							order: 1,
	
						}, {
							label: 'Anxiety',
							fill: false,
							borderColor: "#0417a3",
							backgroundColor: 'lightblue',
							borderWidth: 3,
							data: res.anxiety,	/* Add Data form Ajax Anxiety */
							order: 2,
	
						}, {
							label: 'Stress',
							fill: false,
							borderColor: "#89007d",
							backgroundColor: 'lightblue',
							borderWidth: 3,
							data: res.stress, 	/* Add Data form Ajax Stress */
							order: 3,
	
						}]
					};
					init(chartdata,true);
				}else {
					swal({
						title: "Opps!",
						text: res.message,
						icon: "error",
					});
				}
			}
		});
	}
});
var filesID = 0; // this filesID use to remove file one by one 
$(".custom-file-input").on("change", function (e) {  // Display File Name and Validate Files
	$this = $(this);
	varftype = $this[0].files[0].type,
		fname = $this[0].files[0].name,
		fextension = fname.substring(fname.lastIndexOf('.') + 1);
	validExtensions = ["mp3"];
	if ($.inArray(fextension, validExtensions) == -1) {
		$this.val('');
		text = "This type of files are not allowed! Only .Mp3 ";
		return swalFire("Opps!", text, "error");
	}if (fsize >= 40492700) {
		var text = "file size must be less than 40mb";
		$this.val('');
		return swalFire("Opps!", text, "error");

	}else{
		var files = e.target.files,
			filesLength = files.length;
		if (filesLength <= 6) {
			for (var i = 0; i < filesLength; i++) {
				TotalImage = $('.all-files-content').length
				if (TotalImage < 6) {
					var f = files[i];
					var fname = f.name;
					var fsize = f.size;
					var fextensionII = fname.substring(fname.lastIndexOf('.') + 1);
					validExtensions = ["mp3"];
					if ($.inArray(fextensionII, validExtensions) == -1) {
						$this.val('');
						text = "This type of files are not allowed! Only .Mp3 ";
						return swalFire("Opps!", text, "error");
					}
					if (fsize >= 40492700) {
						var text = "file size must be less than 40.5mb";
						$this.val('');
						return swalFire("Opps!", text, "error");
					}else {
						$(
							'<div class="all-files-content"><div class="files-contentx ">' +
							'<lable id="lableid_' + filesID + '" class="files-name" title="' +
							f.name + '">' + f.name + '</lable><div class="duration_inp"><input type="number" placeholder="Add Duration" name="duration_' + filesID + '" class="duration_ btn-send-duration" id="duration_' + filesID + '"></div></div>' +
							"</div>"
						).insertBefore(".filesremove");
						filesID++
						$(".all-files").show();
						$(".filesremove").show();
						// insertFiles('inserfiles', f);
					}
				}else {
					var text = "No more then six image and files !";
					swalFire("Opps!", text, "error");
					$this.val('');

				}
			}
		}else{
			var text = "No more then six image and files !";
			swalFire("Opps!", text, "error");
			$this.val('');

		}
	}
});

// $(document).on("change", ".btn-send-duration", function (e) {
// // $(document).on(".duration_","keyup", function (e) {  
// 	var value 	= 	$(this).val();
// 	var id 		=	$(this).attr('id');
// 	console.log("VAl---=---=-=-=---->>>>>>>.                ",value);
// 	insertImage('inserfiles',id,value);
// });

// const Images = [];
// function insertImage(helper,insertfiles,value) {
// 	switch (helper) {
// 		case 'inserfiles':
// 			Images.push({'id' :insertfiles,'value' :value })
// 			console.log("Images--->", Images);
// 			break;
// 		case 'remove':
// 			Images.splice($.inArray(insertfiles, Images),1);
// 			console.log("remove--->", Images);
// 			break;
// 		case 'getfiles':
// 				console.log("getfiles--->", Images);
// 				return Images
// 			break;
// 		default:
// 			console.log("helper--->", helper);
// 			break;
// 	}
// };
$(document).on("click", ".filesremove", function (e) {
	e.preventDefault();
	$('.custom-file-input ').val(''); 
	$(".all-files-content").remove();
	$(".all-files").hide();
 	$(".filesremove").hide();
});
function swalFire(title,text,icon){
	return swal({
		title:title,
		text: text,
		icon: icon,
	});
}
$(document).on("click", ".view-full-data", function (e) {
	// $('#exampleModalCenter').modal('show');
	var isTableName = $(this).parent().attr('id')
	var isTablecolID = $(this).attr('data-id')
	var isUserID = $(this).attr('user-id')
	
	if ( isTableName != '' && isTablecolID != ''   &&  isUserID != '') {
		$.ajax({
			type: "POST",
			url: baseUrl + "/user/assessment-data",
			data: {
				'isTableName': 	isTableName,
				'isTablecolID': isTablecolID,
				'isUserID': 	isUserID,
			},
			dataType: "json",
			success: function (res) {
				console.log("res", res);
				if (res.status) {
					console.log("dattaaa---------->>>>>>.  ",res.data);
					$("#largeModal .modal-title").html(res.title);
					$("#largeModal .modal-body").html(res.data);
					$("#largeModal").modal("show");
				} else {
					return swalFire("Opps!",res.message, "error");
				}
			}
		});
	}else{
		return swalFire("Opps!", 'Data Not Found...!', "error");
	}
});

$(document).on("click", ".view-stage-full-data", function (e) {
	// $('#exampleModalCenter').modal('show');

	console.log("isStagecolID----->>>>>>>>>>>>. ", isStagecolID);
	// return;

	var isStagecolID = $(this).attr('data-id')
	var isUserID = $(this).attr('user-id')
	
	if ( isStagecolID != ''   &&  isUserID != '') {
		$.ajax({
			type: "POST",
			url: baseUrl + "/user/stage-detail-data",
			data: {
				'isStagecolID': isStagecolID,
				'isUserID': 	isUserID,
			},
			dataType: "json",
			success: function (res) {
				console.log("res", res);
				if (res.status) {
					console.log("Stage dattaaa---------->>>>>>.  ",res.data);
					$("#largeModal .modal-title").html(res.title);
					$("#largeModal .modal-body").html(res.data);
					// $(".stage-data-table").DataTable();
					$("#largeModal").modal("show");
				} else {
					return swalFire("Opps!",res.message, "error");
				}
			}
		});
	}else{
		return swalFire("Opps!", 'Data Not Found...!', "error");
	}
});

function changeAppDataTable(mesurement_value,stage_value) { 
	$(".filter_substages").show();
	console.log("changeAppDataTable---------===>>>>.   ");

	var is_stage_selected = "";

		if(stage_value != ""){
			is_stage_selected = stage_value;
		}else{
			is_stage_selected = false;
		}

	// var is_stage_subid_selected = "";

	// 	if(stage_subid != ""){
	// 		is_stage_subid_selected = stage_subid;
	// 	}else{
	// 		is_stage_subid_selected = false;
	// 	}

	if (mesurement_value != "") {
		// $(".user_mesuredata").hide();
		// $("#"+mesurement_value).show();
		var user_id = $("#user_id").val();

		$.ajax({
			type: "POST",
			url: baseUrl + "/user/assessment-module-data",
			data: {
				'isModelName': 	mesurement_value,
				'isUserId' : user_id,
				'isStageSelected' : is_stage_selected,
				// 'is_stage_subid_selected' : is_stage_subid_selected,
			},
			dataType: "json",
			success: function (res) {
				console.log("res", res);
				if (res.status) {
					// console.log("Module dattaaa---------->>>>>>.  ",res.data);
					$(".display_op").html(res.data);
					$('.assessment-data-table').DataTable();
					$(".dataTables_wrapper .dataTables_length label select").addClass("input border w-25 mb-5");
					// $("#largeModal").modal("show");
					
				} else {
					return swalFire("Opps!",res.message, "error");
				}
			}
		});
	}
 }

 function changeStageAppDataTable(stage_id) {
	 $(".filter_substages").hide();
	var user_id = $("#user_id").val();
	
	$.ajax({
		type: "POST",
		url: baseUrl + "/user/stage-user-datatable",
		data: {
			'isUserId' : user_id,
			'isStageSelected' : stage_id
		},
		dataType: "json",
		success: function (res) {
			console.log("res", res);
			if (res.status) {
				// console.log("Module dattaaa---------->>>>>>.  ",res.data);
				$(".display_op").html(res.data);
				$('.assessment-data-table').DataTable();
				// $("#largeModal").modal("show");
			} else {
				return swalFire("Opps!",res.message, "error");
			}
		}
	});
}

$(document).on("change","#mesurement_drpdwn", function (e){
	console.log("mesurement_dropdown------>>>>>>>>>>>     ",e.target.options[e.target.selectedIndex].value);

	var this_value = e.target.options[e.target.selectedIndex].value;
	var stage_value = $("#stages :selected").val();
	// var stage_subid_value = $("#stage_sub_drpdwn :selected").val();

	if (this_value == "" && stage_value != "") {
		changeStageAppDataTable(stage_value);
	}else{
		changeAppDataTable(this_value,stage_value);
	}
});

$(".filter_substages").hide();
$(document).on("change","#stages", function (e) { 
	console.log("stage_dropdown------>>>>>>>>>>>     ",e.target.options[e.target.selectedIndex].value);

	var mesurement_value = $("#mesurement_drpdwn :selected").val();
	// var stage_subid_value = $("#stage_sub_drpdwn :selected").val();

	var this_value = e.target.options[e.target.selectedIndex].value;
	var e = $(".filter_substages");
	var f = $(".filter_substages #substages");

	

	if (this_value != "") {
		e.show();
		if(mesurement_value == ""){
			changeStageAppDataTable(this_value)
		}else{
			changeAppDataTable(mesurement_value,this_value);
		}
		
		$("option[class='stage_data_opt']").remove();
		if (this_value == 1) {
			f.append($("<option class='stage_data_opt' value='1.1'> 1.1 </option> <option class='stage_data_opt' value='1.2'> 1.2 </option> <option class='stage_data_opt' value='1.3'> 1.3 </option> <option class='stage_data_opt' value='1.4'> 1.4 </option>"));
		}else if(this_value == 2){
			f.append($("<option class='stage_data_opt' value='1.1'> 1.1 </option> <option class='stage_data_opt' value='1.2'> 1.2 </option>"));
		}else if(this_value == 3){
			f.append($("<option class='stage_data_opt' value='1.1'> 1.1 </option> <option class='stage_data_opt' value='1.2'> 1.2 </option>"));
		}else if(this_value == 4){
			f.append($("<option class='stage_data_opt' value='1.1'> 1.1 </option> <option class='stage_data_opt' value='1.2'> 1.2 </option>"));
		}

	}else{
		if (mesurement_value != "") {
			changeAppDataTable(mesurement_value,"");
		}else{
			changeStageAppDataTable("")
		}
		e.hide();
	}



});

// $(document).on("change","#stage_sub_drpdwn", function (e) { 
// 	var this_value = e.target.options[e.target.selectedIndex].value;
// 	console.log("stage_sub_drpdwn--->>>>>>>>>       ",this_value);

// 	var mesurement_value = $("#mesurement_drpdwn :selected").val();
// 	var stage_value = $("#stages :selected").val();


// 	// changeAppDataTable(mesurement_value,stage_value,this_value);
// });


// $(document).on("change","#stage_drpdwn",function (e) {
// 	console.log("stage_drpdwn stage------------->>>>>>>>>>>>");
// 	var this_value = e.target.options[e.target.selectedIndex].value;
	
	

// });