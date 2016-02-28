
    
 $(document).ready(function() {
        //$('#loading').hide();

        document.getElementById('myVideo').style.display='none';
        document.getElementById('downloadButton').style.display='none';
        document.getElementById('trackUploadSuccess').style.display='none';
        document.getElementById('imageUploadSuccess').style.display='none';
        document.getElementById('imageOption').style.display='none';
        document.getElementById('warning').style.display='none';
        document.getElementById('warningDuplicate').style.display='none';
        document.getElementById('warningLargeFile').style.display='none';
        document.getElementById('warningImage').style.display='none';

        var downloadLink="";
        var download=1;
        var imageUploaded=1;
        var trackUploaded=1; 
        var convertPressed=1;
        var duplicateValue=1; 
        var overSizedTrack=1;

        // $("#myVideo").hide();
        // $("#downloadButton").hide();
        // $("#trackUploadSuccess").hide();
        // $("#imageUploadSuccess").hide();
        // $("#warning").hide();
        // $("#imageOption").hide();
        // $("#warningImage").hide();
        // $("#warningDuplicate").hide();
        // $("#warningLargeFile").hide();
        // $("#warningImage").hide();


        //TU means 'track uploaded'
        var TU = localStorage.getItem("trackUploaded");

        //IU means 'image uploaded'
     //   var IU = localStorage.getItem("imageUploaded");

        //alert(TU);

        // if(TU==1){
        //     alert('Please upload a track first!');

        // }

        // if(trackUploaded==1 && imageUploaded == 1 && convertPressed == 0){
        //     alert('Please upload a track first!');
        // }

         if(trackUploaded==0){
            hideUploadTrack();
            document.getElementById('imageOption').style.display='block';
        }

        if(imageUploaded==0){
            hideUploadTrack();
            hideUploadImage();
        }

        if(duplicateValue==0){
            document.getElementById("warningDuplicate").style.display='block';
        }

        if(overSizedTrack==0){
            document.getElementById("overSizedTrack").style.display='block';
        }

        //if(larg)

        if(download==0){
            // hideMainContent();
             var vid = document.getElementById("myVideo");
             var downloadButton = document.getElementById("downloadButton");
             var extension = ".mp4";
            hideFileUploadContent();
            var link=  downloadLink.concat(extension);
            vid.src=link;
            downloadButton.href=link;
            //document.write(downloadLink);
           // alert(downloadLink);
            // //document.getElementById('download').innerHtml= downloadContent;
            // document.querySelector("#myVideo > source").src = downloadLink;

            // var video = document.getElementById('video');
            // var source = document.createElement('source');

            // source.setAttribute('src', downloadLink);

            // video.appendChild(source);
          //  video.load();



            document.getElementById('myVideo').style.display='block';
            document.getElementById('downloadButton').style.display='block';


        }

       



     });

    function checkUpload(){
        var track=checkTrackUpload();
       var image;
        //alert(track);
     //  alert("image = " + image + " track " + track); 

       if(track===true && IU == 'block'){
         image=checkImageUpload();
         return false;
       }

       if(track==true && IU == 'none'){
         return true;
       }

       //if (typeof image == 'undefined')

        return false;

        // if (track){
        //     return true;
        // }
        // else{
        //     return false; 
        // }
    }


    function checkTrackUpload(){
          
    if(localStorage.getItem("trackUploaded") == 1 || localStorage.getItem("trackUploaded") === null ) {

       // alert("track can't upload " + localStorage.getItem("trackUploaded") );
       document.getElementById('warning').style.display='block';
        return false;
     }
     else{
        return true;
     }
    }

    function checkImageUpload(){
        if(document.getElementById('imageUpload').style.display='block' && (localStorage.getItem("imageUploaded" )== 1 || localStorage.getItem("imageUploaded") == null) ){
          //alert(localStorage.getItem("imageUploaded"));
        //  alert(document.getElementById('imageUpload').style.display);
           showImageWarning();
            return false;
        }
        else{
           // alert("hi");
            hideImageWarning();
            return true;
        }



    }

    function showImageWarning(){
        document.getElementById('warningImage').style.display='block';
    }

    function hideImageWarning(){
        document.getElementById('warningImage').style.display='none';
    }

    function hideUploadTrack(){
        // document.getElementById("trackUploadSuccess").style.display="none";
     //    var elem1 = document.createElement("img");
     //    elem1.setAttribute("src", "./img/successful-track-upload.JPG");     
     //    elem1.setAttribute("alt", "Track uploaded successfully");
     //    document.getElementById("uploadTrack").innerHtml='';
     // document.getElementById("uploadTrack").appendChild(elem1);
     document.getElementById("uploadTrack").style.display="none";
     document.getElementById("trackUploadSuccess").style.display="block";
    }

    function hideUploadImage(){
        // document.getElementById("imageOption").style.display="none";
        //  var elem2 = document.createElement("img");
        // elem2.setAttribute("src", "./img/successful-image-upload.JPG");     
        // elem2.setAttribute("alt", "Image uploaded successfully");
        // document.getElementById("imageOption").innerHtml='';
        // document.getElementById("imageOption").appendChild(elem2);
        // document.getElementById("imageOption").style.display="block";
        document.getElementById("uploadTrack").style.display="none";
        document.getElementById("imageOption").style.display="none";
        document.getElementById("trackUploadSuccess").style.display="block"; 
        document.getElementById("imageUploadSuccess").style.display="block";
    }




                            


    var myEl = document.getElementById('convert');

   

    function showImageUpload(e){
        document.getElementById('imageUpload').style.display=e.checked && e.id =='yes' ? 'block' : 'none';  

        //IU = localStorage.setItem(document.getElementById('imageUpload').style.display);
       // alert(document.getElementById('imageUpload').style.display);

       IU=document.getElementById('imageUpload').style.display;

       if(IU=='none'){
        hideImageWarning();
       }

     //  alert("ImageUpload (IU) is " + IU);         
    }

    function hideFileUploadContent(){
        document.getElementById('fileUpload').style.visibility='hidden';
    }
   

    $(document).on('ready', function() {
        $("#input-21").fileinput({
            previewFileType: "image",
            browseClass: "btn btn-primary",
            browseLabel: "Pick Image",
            browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
            removeClass: "btn btn-danger",
            removeLabel: "Delete",
            removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "Upload",
            uploadUrl: "./userInterface.php",
            uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> "

        });
    });





  


    // $('#file-fr').fileinput({
    //     language: 'fr',
    //     uploadUrl: '#',
    //     allowedFileExtensions : ['jpg', 'png','gif'],
    // });
    // $('#file-es').fileinput({
    //     language: 'es',
    //     uploadUrl: '#',
    //     allowedFileExtensions : ['jpg', 'png','gif'],
    // });
    $("#file-0").fileinput({
        'allowedFileExtensions' : ['jpg', 'png','gif'],
    });
    $("#file-1").fileinput({
        uploadUrl: '#', // you must set a valid URL here else you will get an error
        allowedFileExtensions : ['jpg', 'png','gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
    /*
    $(".file").on('fileselect', function(event, n, l) {
        alert('File Selected. Name: ' + l + ', Num: ' + n);
    });
    */
    $("#file-3").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-primary btn-lg",
        fileType: "any",
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
    });

    $("#file-4").fileinput({
        uploadExtraData: {kvId: '10'}
    });

    $(".btn-warning").on('click', function() {
        if ($('#file-4').attr('disabled')) {
            $('#file-4').fileinput('enable');
        } else {
            $('#file-4').fileinput('disable');
        }
    });    
    
    $(".btn-info").on('click', function() {
        $('#file-4').fileinput('refresh', {previewClass:'bg-info'});
    });
    /*
    $('#file-4').on('fileselectnone', function() {
        alert('Huh! You selected no files.');
    });
    $('#file-4').on('filebrowse', function() {
        alert('File browse clicked for #file-4');
    });
    */
    $(document).ready(function() {
        $("#test-upload").fileinput({
            'showPreview' : false,
            'allowedFileExtensions' : ['jpg', 'png','gif'],
            'elErrorContainer': '#errorBlock'
        });
        /*
        $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
            alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
        });
        */
    });
  

