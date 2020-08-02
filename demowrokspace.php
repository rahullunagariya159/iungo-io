
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="css/demoworkspace.css" />
<style>
</style>

  
<body>

  
  
<!-- multistep form -->
<form id="msform">
  <!-- progressbar -->
  <!-- <ul id="progressbar">
    <li class="active">Account Setup</li>
    <li>Social Profiles</li>
    <li>Personal Details</li>
    <li>test</li>
  </ul> -->
  <!-- fieldsets -->
   <fieldset>
    <h2 class="fs-title">Create your account</h2>
    <h3 class="fs-subtitle">This is step 1</h3>
    <input type="text" name="email" placeholder="Email" />

    <input type="button" name="next" class="action-button btnEmail" value="Next" />
  </fieldset>
  <fieldset>
    <h2 class="fs-title">Create your account</h2>
    <h3 class="fs-subtitle">This is step 2</h3>
    <input type="text" name="code" placeholder="code" />
   
    <input type="button" name="previous" class="previous action-button" value="Previous" />
    <input type="button" name="next" class="action-button fBtn" value="Next" />
  </fieldset>
  <fieldset>
    <h2 class="fs-title">Social Profiles</h2>
    <h3 class="fs-subtitle">Your presence on the social network</h3>
    <input type="text" name="twitter" placeholder="Twitter" />
    <input type="text" name="facebook" placeholder="Facebook" />
    <input type="text" name="gplus" placeholder="Google Plus" />
    <input type="button" name="previous" class="previous action-button" value="Previous" />
    <input type="button" name="next" class="next action-button" value="Next" />
  </fieldset>
  <fieldset>
    <h2 class="fs-title">Personal Details</h2>
    <h3 class="fs-subtitle">We will never sell it</h3>
    <input type="text" name="fname" placeholder="First Name" />
    <input type="text" name="lname" placeholder="Last Name" />
    <input type="text" name="phone" placeholder="Phone" />
    <textarea name="address" placeholder="Address"></textarea>
    <input type="button" name="previous" class="previous action-button" value="Previous" />
    <input type="submit" name="submit" class="submit action-button" value="Submit" />
  </fieldset>
</form>



<!--===============================================================================================-->  
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
  <script src="vendor/bootstrap/js/popper.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
  </body>

  <script>

     $(document).ready(function(){

          //jQuery time
          var current_fs, next_fs, previous_fs; //fieldsets
          var left, opacity, scale; //fieldset properties which we will animate
          var animating; //flag to prevent quick multi-click glitches


            function perfomNextClickEvent(currBtnVal){
               if(animating) return false;
            animating = true;
            
            current_fs = $(currBtnVal).parent();
            next_fs = $(currBtnVal).parent().next();
            
            //activate next step on progressbar using the index of next_fs
            // $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            
            //show the next fieldset
            next_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
              step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                  'transform': 'scale('+scale+')',
                  'position': 'absolute'
                });
                next_fs.css({'left': left, 'opacity': opacity});
              }, 
              duration: 800, 
              complete: function(){
                current_fs.hide();
                animating = false;
              }, 
              //this comes from the custom easing plugin
              easing: 'easeInOutBack'
            });
            }


          $(".next").click(function(){
             var currBtn = this;
            perfomNextClickEvent(currBtn);
          });

             $(".fBtn").click(function(){
             var currBtn = this;
            perfomNextClickEvent(currBtn);
          });

              $(".btnEmail").click(function(){
             var currBtn = this;
            perfomNextClickEvent(currBtn);
          });


             

          $(".previous").click(function(){
            if(animating) return false;
            animating = true;
            
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();
            
            //de-activate current step on progressbar
            // $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
            
            //show the previous fieldset
            previous_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
              step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
              }, 
              duration: 800, 
              complete: function(){
                current_fs.hide();
                animating = false;
              }, 
              //this comes from the custom easing plugin
              easing: 'easeInOutBack'
            });
          });

          $(".submit").click(function(){
            return false;
          })


      });


  </script>

</html>