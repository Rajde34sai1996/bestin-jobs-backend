$(document).ready(function () {
  var postData = {
    '<?=Yii::$app->request->csrfParam?>': '<?=Yii::$app->request->getCsrfToken()?>'
 }
 
  console.log("Loading  ........");
  $(document).on("change", ".update-skill-status", function (e) {
    console.log("e", e);
    e.preventDefault();
    console.log("this", $(this));
    var id = $(this).attr("data-id");
    console.log("id", id);
    var status = $(this).attr("status");
    $.ajax({
      type: "POST",
      url: baseUrl + "/skills/update-status",
      data: {
        id: id,
        status:status,
        postData
      },
      dataType: "json",
      success: function (data) {
        Swal.fire({
          title: data.message,
          icon: data.type,
          showCancelButton: false,
          confirmButtonText: "Ok",
        });
      },
    });
  });
});
