/**
 * File Upload
 */

"use strict";

(function () {
    // previewTemplate: Updated Dropzone default previewTemplate
    // ! Don't change it unless you really know what you are doing
    const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

    // ? Start your code from here

    // Basic Dropzone
    // --------------------------------------------------------------------

    var myDropzone = new Dropzone("#dropzone-basic", {
        url: "/service-reports",
        paramName: "images",
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 2,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        maxFiles: 4,
        autoProcessQueue: false,
        autoProcess: false,
        autoQueue: false,
        init: function () {
            var myDropzone = this;
            //form submission code goes here
            $("form[name='serviceReports']").submit(function (event) {
                //Make sure that the form isn't actully being sent.
                event.preventDefault();

                // URL = $("#demoform").attr("action");
                // formData = $("#demoform").serialize();
                // $.ajax({
                //     type: "POST",
                //     url: URL,
                //     data: formData,
                //     success: function (result) {
                //         if (result.status == "success") {
                //             // fetch the useid
                //             var userid = result.user_id;
                //             $("#userid").val(userid); // inseting userid into hidden input field
                //             //process the queue
                //             myDropzone.processQueue();
                //         } else {
                //             console.log("error");
                //         }
                //     },
                // });
            });
        },
        accept: function (file, done) {
            if (file.size == 0) {
                done("Empty files will not be uploaded.");
            } else {
                done();
            }
        },
    });

    // Multiple Dropzone
    // --------------------------------------------------------------------
    const dropzoneMulti = new Dropzone("#dropzone-multi", {
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5,
        addRemoveLinks: true,
    });
})();
