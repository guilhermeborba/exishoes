<?php if ( ! defined( 'ABSPATH' ) ) exit;

$this_popup_unique_Id = '';
if (isset($isPopUpFlyInTemplate) || $selecteOptinType == 'Fly In' ) {
  

  $pageMaxWidth = '55';
  $pageMaxWidthU = '%';
  if ( isset( $data['pageOptions']['pageMaxWidth'] )) {
    if ($data['pageOptions']['pageMaxWidth'] != '' && $data['pageOptions']['pageMaxWidth'] != '0') {
      $pageMaxWidth = $data['pageOptions']['pageMaxWidth'];
      $pageMaxWidthU = $data['pageOptions']['pageMaxWidthU'];
    }
  }
  $pageMaxWidthT = '65';
  $pageMaxWidthUT = '%';
  if ( isset( $data['pageOptions']['pageMaxWidthT'] )) {
    if ($data['pageOptions']['pageMaxWidthT'] != '' && $data['pageOptions']['pageMaxWidthT'] != '0') {
      $pageMaxWidthT = $data['pageOptions']['pageMaxWidthT'];
      $pageMaxWidthUT = $data['pageOptions']['pageMaxWidthUT'];
    }
    
  }
  $pageMaxWidthM = '70';
  $pageMaxWidthUM = '%';
  if ( isset( $data['pageOptions']['pageMaxWidthM'] )) {
    if ($data['pageOptions']['pageMaxWidthM'] != '' && $data['pageOptions']['pageMaxWidthM'] != '0') {
      $pageMaxWidthM = $data['pageOptions']['pageMaxWidthM'];
      $pageMaxWidthUM = $data['pageOptions']['pageMaxWidthUM'];
    }
      
  }

  if(!empty($popupDisplayDelay)){
    echo "<script> var popUpDisplayActionType = 'delay'; </script>";
    $popupDisplayOnScroll = ''; $popupDisplayOnExit = ''; $popupDisplayOnClick = '';
  }else if(!empty($popupDisplayOnScroll)){
    echo "<script> var popUpDisplayActionType = 'onscroll'; </script>";
    $popupDisplayDelay = ''; $popupDisplayOnExit = ''; $popupDisplayOnClick = '';
  }
  else if(!empty($popupDisplayOnExit)){
    echo "<script> var popUpDisplayActionType = 'onexit'; </script>";
    $popupDisplayDelay = ''; $popupDisplayOnScroll = ''; $popupDisplayOnClick = '';
  }
  else if(!empty($popupDisplayOnClick)){
    echo "<script> var popUpDisplayActionType = 'onclick'; </script>";
    $popupDisplayDelay = ''; $popupDisplayOnScroll = ''; $popupDisplayOnExit = '';
  }else{
    echo "<script> var popUpDisplayActionType = 'delay'; </script>";
    $popupDisplayDelay = ''; $popupDisplayOnScroll = ''; $popupDisplayOnExit = ''; $popupDisplayOnClick = '';
  }

  if (!isset($isPopUpFlyInTemplate)) {
    $isPopUpFlyInTemplate = '';
  }

  if ($isPopUpFlyInTemplate == true || $selecteOptinType == 'Fly In') {
    $this_popup_unique_Id = 'popup_'.$current_pageID;
    if(isset($popupDisplayDelay)){
    }else{
      $popupDisplayDelay = '0';
    }

    ?>
    <style type="text/css">
      #popb_popup_close_<?php echo($this_popup_unique_Id); ?>:before {
        transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -o-transform: rotate(45deg);
      }
      #popb_popup_close_<?php echo($this_popup_unique_Id); ?>:after {
        transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
      }
      #popb_popup_close_<?php echo($this_popup_unique_Id); ?>:after, #popb_popup_close_<?php echo($this_popup_unique_Id); ?>:before {
        background-color: #414141;
        content: '';
        position: absolute;
        left: 14px;
        height: 14px;
        top: 8px;
        width: 2px;
      }

      #popb_popup_close_<?php echo($this_popup_unique_Id); ?> {
        width: 30px;
        height: 30px;
        background-color: #fff;
        border-radius: 100%;
        box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.2);
        cursor: pointer;
        position: relative;
        right: 15px;
        top: 15px;
        z-index: 2;
        float: left;
      }

      @media screen and (max-width: 1920px) and (min-width: 1280px) {
        #POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>{
          max-width:<?php echo $pageMaxWidth.$pageMaxWidthU; ?> !important;
        }
        #pluginops-modal-content_<?php echo($this_popup_unique_Id); ?>{
          min-width:90% !important;
        }
      }
      @media screen and (max-width: 1280px) and (min-width: 980px) {
        #POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>{
          max-width:<?php echo $pageMaxWidthT.$pageMaxWidthUT; ?> !important;
        }
        #pluginops-modal-content_<?php echo($this_popup_unique_Id); ?>{
          min-width:90% !important;
        }
      }
      @media screen and (max-width: 975px) and (min-width: 280px) {
        #POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>{
          max-width:<?php echo $pageMaxWidthM.$pageMaxWidthUM; ?> !important;
          overflow: visible;
        }
        #pluginops-modal-content_<?php echo($this_popup_unique_Id); ?>{
          min-width:90% !important;
          margin:0 auto;
          padding: 0 !important;
        }
      }

      <?php
        if (isset($popupPosition)) {
          if ($popupPosition == 'bRight') {
            echo "#POPB-modal-overlay_$this_popup_unique_Id {
              right: 0 !important;
              bottom: 0 !important;
            }";
          }
          if ($popupPosition == 'bLeft') {
            echo "#POPB-modal-overlay_$this_popup_unique_Id {
              left: 0 !important;
              bottom: 0 !important;
              padding: 10px 10px 0 0 !important;
            }
            #popb_popup_close_$this_popup_unique_Id {
              float: right !important;
              right: -10px !important;
            }
            ";
          }
          if ($popupPosition == 'bCenter') {
            echo "#POPB-modal-overlay_$this_popup_unique_Id {
              bottom: 0 !important;
            }";
          }
        }else{
          echo "#POPB-modal-overlay_$this_popup_unique_Id {
              right: 0 !important;
              bottom: 0 !important;
            }";
        }
      ?>
    </style>

      <div class="pluginops-modal" id="POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>" style=" position: fixed; z-index: 999999999999; display: none; padding: 10px 0 0 10px;">
        
        <div class="pluginops-modal-content" id="pluginops-modal-content_<?php echo($this_popup_unique_Id); ?>" style=''>
          <div class="popb_popup_close" id='popb_popup_close_<?php echo($this_popup_unique_Id); ?>' style="display:<?php echo $data['pageOptions']['hideCloseBtn']; ?>;" ></div>


        <?php ob_start(); ?>

        <script>
          ( function( $ ) {

                if ($.cookie) {
                  var testUserSubscribed = $.cookie("pluginops_user_subscribed_form<?php echo $current_pageID; ?>");
                  var testPopUpClosed = $.cookie("pluginops_user_closed_form<?php echo $current_pageID; ?>");
                }else{
                  var testUserSubscribed = '';
                  var testPopUpClosed = '';
                }
                if (typeof(testUserSubscribed) == 'undefined') {
                  testUserSubscribed = '';
                }
                if (typeof(testPopUpClosed) == 'undefined') {
                  testPopUpClosed = '';
                }
                
                var <?php echo "popupClosed_$current_pageID"; ?> = 'false';
                <?php if (current_user_can('publish_pages')) {
                  echo "var testUserSubscribed = 'false'; ";
                  echo "var testPopUpClosed = 'false';";
                } 
                if ($hideAfterCampaignClosed != 'true') {
                  echo "var testPopUpClosed = 'false'; ";
                }

                ?>

                if (testUserSubscribed == 'yes' || testPopUpClosed == 'yes') {
                  $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','none');
                }else{
                    
                  if (popUpDisplayActionType == 'delay') {
                    setTimeout(function(){
                      $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                      $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                      $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                    }, <?php echo $popupDisplayDelay.'000'; ?>);
                  }

                  if (popUpDisplayActionType == 'onscroll') {
                    $(window).on('scroll', function(){
                      var s = $(window).scrollTop(),
                          d = $(document).height(),
                          c = $(window).height();
                      var PoPb_scrollTopPercentage = (s / (d - c)) * 100;
                      var popUpOnScroll = parseInt(<?php echo $popupDisplayOnScroll; ?>);

                      if ( PoPb_scrollTopPercentage > popUpOnScroll && <?php echo "popupClosed_$current_pageID"; ?> != 'closed') {
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                      }
                    });
                  }

                  if (popUpDisplayActionType == 'onexit') {
                    
                      window.addEventListener("mouseout", function(ev) {
                          ev = ev ? ev : window.event;
                          var from = ev.relatedTarget || ev.toElement;
                          if (!from || from.nodeName == "HTML") {
                            if ( <?php echo "popupClosed_$current_pageID"; ?> != 'closed') {
                              $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                              $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                            }
                          }
                      });
                  }
                  
                  if (popUpDisplayActionType == 'onclick') {
                    var onclickElId = '<?php echo "$popupDisplayOnClick"; ?>';
                    if (onclickElId != '') {
                      $('#'+onclickElId).on('click', function(){
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                      });
                      $('.'+onclickElId).on('click', function(){
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                      });
                    }

                  }

                }

            $(document).ready(function(){
              if (popUpDisplayActionType == 'onclick') {
                    var onclickElId = '<?php echo "$popupDisplayOnClick"; ?>';

                    if (onclickElId != '') {
                      $('#'+onclickElId).on('click', function(){
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                      });
                      $('.'+onclickElId).on('click', function(){
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').fadeIn('slow');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' animated  <?php echo($popUpEntranceAnimation); ?>');
                        $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','block');
                      });
                    }

              }
              $('#popb_popup_close_<?php echo($this_popup_unique_Id); ?>').on('click',function(){

                $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' <?php echo($popUpExitAnimation); ?>').one('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd', function(){
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').removeClass(' animated  <?php echo($popUpExitAnimation); ?>');
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','none');
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').unbind('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd');
                  });
                <?php echo "popupClosed_$current_pageID"; ?> = 'closed';
              });

              $('.popb_popup_close_<?php echo($this_popup_unique_Id); ?>').on('click',function(){

                $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').addClass(' <?php echo($popUpExitAnimation); ?>').one('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd', function(){
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').removeClass(' animated  <?php echo($popUpExitAnimation); ?>');
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').css('display','none');
                    $('#POPB-modal-overlay_<?php echo($this_popup_unique_Id); ?>').unbind('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd');
                  });
                <?php echo "popupClosed_$current_pageID"; ?> = 'closed';
              });

            });
          })(jQuery);
        </script>

    <?php

      $mainOptinTriggerScript = ob_get_contents();
      ob_end_clean();

      $mainOptinTriggerScript = str_replace('<script>', ' ',$mainOptinTriggerScript);
      $mainOptinTriggerScript = str_replace('</script>', ' ',$mainOptinTriggerScript);
      array_push($POPBallWidgetsScriptsArray, $mainOptinTriggerScript);

  }
}

?>