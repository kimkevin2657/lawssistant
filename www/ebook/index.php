<?php
include_once('./_common.php');
$no = $_GET['no'];
$row = sql_fetch(" select * from shop_ebook where no='$no' "); //db에 등록되었었는지 여부 검사 
if($row['no']){
	$title = $row['title'];
	$bpage = $row['bpage'];
}else{
	$title = "건강백세 복지몰";
}
?>
<html>
  <head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
   
    <!-- viewport -->
    <meta content="width=device-width,initial-scale=1" name="viewport">
    
    <!-- title -->
    <title><?php echo $title;?></title>        
        
    <!-- add css style -->
    <link type="text/css" href="css/style.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Play:400,700">
    <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet">
    
    <!-- add js code -->
	<script src="js/jquery.js"></script>
    <script src="js/jquery_no_conflict.js"></script>
    <script src="js/turn.js"></script>              
    <script src="js/wait.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
	<script src="js/jquery.fullscreen.js"></script>
    <script src="js/jquery.address-1.6.min.js"></script>
	<script src="js/pdf.js"></script>
	<script src="js/onload.js"></script>


	<style>
    html, body {
        margin: 0;
        padding: 0;
		overflow:auto !important;
    }
    </style> 
      
  </head> 
<body>
  
<?php
if($row['no']){
?>
<!-- begin flipbook  -->
<div id="fb5-ajax" data-cat="your_book_name" data-template="true"> 			

    <!-- BACKGROUND FLIPBOOK -->
	<div class="fb5-bcg-book"></div> 
    
	<!-- BEGIN PRELOADER -->
    <div class="fb5-preloader"></div>
    <!-- END PRELOADER -->
        
    <!-- BEGIN STRUCTURE HTML FLIPBOOK -->      
    <div class="fb5" id="fb5">      
        
        <!-- CONFIGURATION BOOK -->
        <section id="config">
          <ul>
            <li key="page_width">918</li>               <!-- width for page -->
            <li key="page_height">1298</li>             <!-- height for page -->
            <li key="gotopage_width">25</li>            <!-- width for field input goto page -->
            <li key="zoom_double_click">1</li>          <!-- value zoom after double click -->
            <li key="zoom_step">0.06</li>				<!-- zoom step ( if click icon zoomIn or zoomOut -->
            <li key="toolbar_visible">true</li>			<!-- enabled/disabled toolbar -->
            <li key="tooltip_visible">true</li>			<!-- enabled/disabled tooltip for icon -->
            <li key="deeplinking_enabled">true</li>   	<!-- enabled/disabled deeplinking -->  
            <li key="lazy_loading_pages">false</li>		<!-- enabled/disabled lazy loading for pages in flipbook -->
            <li key="lazy_loading_thumbs">false</li>	<!-- enabled/disabled lazdy loading for thumbs -->
            <li key="double_click_enabled">true</li> 	<!-- enabled/disabled double click mouse for flipbook -->                 
            <li key="rtl">false</li>					<!-- enabled/disabled 'right to left' for eastern countries -->
            <li key="pdf_url"></li>		                <!-- pathway to a pdf file ( the file will be read live ) -->
            <li key="pdf_scale">1</li>					<!-- to live a pdf file (if you want to have a strong zoom - increase the value) -->
            <li key="page_mode">auto</li>               <!-- value to 'single', 'double', or 'auto' -->
            <li key="sound_sheet"></li>                 <!-- sound for sheet -->
         </ul> 
        </section>
        
		 <!-- 화살표 -->
		 <ul>
             <li style="position:fixed;top:40%;left:30px;z-index:999;color:#fff;font-size:3rem;opacity:.7;">
                 <a title="prev page" class="fb5-arrow-left"><i class="fa fa-chevron-left"></i></a>
             </li>
             <li style="position:fixed;top:40%;right:30px;z-index:999;color:#fff;font-size:3rem;opacity:.7;">
                 <a title="next page" class="fb5-arrow-right"><i class="fa fa-chevron-right"></i></a>
             </li>
         </ul>
		 <!--화살표 끝-->

        <!-- BEGIN BACK BUTTON -->
        <a href="https://blingbeauty.shop/" id="fb5-button-back">&lt; Back </a>
        <!-- END BACK BUTTON -->                        
      
        <!-- BEGIN CONTAINER BOOK -->
        <div id="fb5-container-book">
     
            <!-- BEGIN deep linking -->  
            <section id="fb5-deeplinking">
              <ul>
<?php 
##############################################
############### 페이지 표시 시작 ##################
##############################################
?>
<?php
for($i=1; $i<=$bpage; $i++)	{
?>
                  <li data-address="page<?php echo $i;?>" data-page="<?php echo $i;?>"></li>
<?php } ?>
<?php 
##############################################
############### 페이지 표시 시작 ##################
##############################################
?>
              </ul>
            </section>
            <!-- END deep linking -->  
                
            <!-- BEGIN ABOUT -->
            <section id="fb5-about">
            </section>
            <!-- END ABOUT -->
            
            
            <!-- BEGIN LINKS -->
            <section id="links">
            
                    
           
           </section>     
           <!-- END LINKS -->                         
                                      
    
            <!-- BEGIN PAGES -->
            <div id="fb5-book">                       
<?php 
##############################################
############### 이북 만드는 곳 시작 ################
##############################################
?>
<?php
$sql = " select * from shop_ebook_view where no = '{$no}' order by bpage asc ";
$result = sql_query($sql);
while($row1=sql_fetch_array($result)) {
?>
                        <!-- begin page <?php echo $row1['bpage'];?> -->   
						<?php if($row1['con']){ ?>
                        <div data-background-image="">          
						<?php }else{ ?>
                        <div data-background-image="/data/ebook/<?php echo $row1['img'];?>">          
						<?php } ?>
                               
                                     <!-- container page book --> 
                                     <div class="fb5-cont-page-book">
                                     
                                            <!-- gradient for page -->
                                            <div class="fb5-gradient-page"></div>                
                                         
                                            <!-- PDF.js --> 
                                            <canvas id="canv1"></canvas>                                                               
                                           
                                            <!-- description for page --> 
                                            <div class="fb5-page-book">
												     <?php echo $row1['con'];?>
                                            </div> 
                                                      
                                     
                                      </div>
                                      <!-- end container page book --> 
                      
               
                          </div>
                         <!-- end page <?php echo $row1['bpage'];?> -->  
<?php } ?>                 
<?php 
##############################################
############### 이북 만드는 곳 시작 ################
##############################################
?>                        
                          
            </div>
            <!-- END PAGES -->
            
        </div>
        <!-- END CONTAINER BOOK -->
    
        <!-- BEGIN FOOTER -->
        <div id="fb5-footer">
        
            <div class="fb5-bcg-tools"></div>
            
            
             
            <a id="fb5-logo" target="_blank" href="https://blingbeauty.shop/">
                <img alt="" src="img/logo.png">
                 
            </a>
       
            <div class="fb5-menu" id="fb5-center">
                <ul>
                
                    <!-- icon_home -->
                    <li>
                        <a title="show home page" class="fb5-home"><i class="fa fa-home"></i></a>
                    </li>
                                    
                    
                    <!-- icon download -->
                    <!--li>
                        <a title="download pdf" class="fb5-download" href="img/file.zip"><i class="fa fa-download"></i></a>
                    </li-->
                                
                            
                    <!-- icon arrow left -->
                    <li>
                        <a title="prev page" class="fb5-arrow-left"><i class="fa fa-chevron-left"></i></a>
                    </li>
                                   
                    
                      <!-- icon arrow right -->
                    <li>
                        <a title="next page" class="fb5-arrow-right"><i class="fa fa-chevron-right"></i></a>
                    </li>
                                    
                    
                    <!-- icon_zoom_in -->                     
                    <li>
                        <a title="zoom in" class="fb5-zoom-in"><i class="fa fa-search-plus"></i></a>
                    </li>
                                                
                               
                    
                    <!-- icon_zoom_out -->                 
                    <li>
                        <a title="zoom out" class="fb5-zoom-out"><i class="fa fa-search-minus"></i></a>
                    </li>
                                    
                    
                     <!-- icon_zoom_auto -->
                    <li>
                        <a title="zoom auto" class="fb5-zoom-auto"><i class="fa fa-search"></i></a>
                    </li>
                                    
                               
                    <!-- icon_allpages -->
                    <li>
                        <a title="show all pages" class="fb5-show-all"><i class="fa fa-list"></i></a>
                    </li>
                                                    
                    
                    <!-- icon fullscreen -->                 
                    <li>
                        <a title="full/normal screen" class="fb5-fullscreen"><i class="fa fa-expand"></i></a>
                    </li>
                                    
                  
                    
                </ul>
            </div>
            
            <div class="fb5-menu" id="fb5-right">
                <ul>              
                    <!-- icon page manager -->                 
                    <li class="fb5-goto">
                        <label for="fb5-page-number" id="fb5-label-page-number"></label>
                        <input type="text" id="fb5-page-number" style="width: 25px;"> 
                        <span id="fb5-page-number-two"></span>
                        
                    </li>                
                </ul>
            </div>
            
            
        
        </div>
        <!-- END FOOTER -->
     
        <!-- BEGIN ALL PAGES -->
        <div id="fb5-all-pages" class="fb5-overlay">
    
          <section class="fb5-container-pages">
    
            <div id="fb5-menu-holder">
    
                <ul id="fb5-slider">       	 
<?php 
##############################################
############### 썸네일 부분 만드는 곳 시작 ############
##############################################
?>
<?php
$sql = " select * from shop_ebook_view where no = '{$no}' order by bpage asc ";
$result = sql_query($sql);
while($row1=sql_fetch_array($result)) {
?>
			       <!-- thumb <?php echo $row1['bpage'];?> -->
			       <li class="<?php echo $row1['bpage'];?>">
			            <img alt="" data-src="/data/ebook/<?php echo $row1['img'];?>">
			        
			       </li> 
<?php } ?>  
<?php 
##############################################
############### 썸네일 부분 만드는 곳 끝 #############
##############################################
?>    
                </ul>
            
            </div>
    
        </section>
    
       </div>
        <!-- END ALL PAGES -->           
         
         
         <!-- BEGIN SOUND FOR SHEET  -->
        <audio preload="auto" id="sound_sheet"></audio>  
        <!-- END SOUND FOR SHEET --> 
         
        <!-- BEGIN CLOSE LIGHTBOX  -->
        <div id="fb5-close-lightbox">
         <i class="fa fa-times pull-right"></i>
        </div>  
        <!-- END CLOSE LIGHTBOX -->
    
    
    </div>
    <!-- END STRUCTURE HTML FLIPBOOK -->

     
</div>
<!-- end flipbook -->                                                            

<?php
}else{ 
####################################################################
####################################################################
####################################################################
####################################################################
####################################################################
########해당 페이지를 현재 사용해야 한다고해서 변수 없을때 현재 설정한 페이지 보이게 함########
####################################################################
####################################################################
####################################################################
####################################################################
####################################################################
?>
<!-- begin flipbook  -->
<div id="fb5-ajax" data-cat="your_book_name" data-template="true"> 			

    <!-- BACKGROUND FLIPBOOK -->
	<div class="fb5-bcg-book"></div> 
    
	<!-- BEGIN PRELOADER -->
    <div class="fb5-preloader"></div>
    <!-- END PRELOADER -->
        
    <!-- BEGIN STRUCTURE HTML FLIPBOOK -->      
    <div class="fb5" id="fb5">      
        
        <!-- CONFIGURATION BOOK -->
        <section id="config">
          <ul>
            <li key="page_width">1500</li>               <!-- width for page -->
            <li key="page_height">2121</li>             <!-- height for page -->
            <li key="gotopage_width">25</li>            <!-- width for field input goto page -->
            <li key="zoom_double_click">1</li>          <!-- value zoom after double click -->
            <li key="zoom_step">0.06</li>				<!-- zoom step ( if click icon zoomIn or zoomOut -->
            <li key="toolbar_visible">true</li>			<!-- enabled/disabled toolbar -->
            <li key="tooltip_visible">true</li>			<!-- enabled/disabled tooltip for icon -->
            <li key="deeplinking_enabled">true</li>   	<!-- enabled/disabled deeplinking -->  
            <li key="lazy_loading_pages">false</li>		<!-- enabled/disabled lazy loading for pages in flipbook -->
            <li key="lazy_loading_thumbs">false</li>	<!-- enabled/disabled lazdy loading for thumbs -->
            <li key="double_click_enabled">true</li> 	<!-- enabled/disabled double click mouse for flipbook -->                 
            <li key="rtl">false</li>					<!-- enabled/disabled 'right to left' for eastern countries -->
            <li key="pdf_url"></li>		                <!-- pathway to a pdf file ( the file will be read live ) -->
            <li key="pdf_scale">1</li>					<!-- to live a pdf file (if you want to have a strong zoom - increase the value) -->
            <li key="page_mode">auto</li>               <!-- value to 'single', 'double', or 'auto' -->
            <li key="sound_sheet"></li>                 <!-- sound for sheet -->
         </ul> 
        </section>
        
              
        <!-- BEGIN BACK BUTTON -->
        <a href="https://blingbeauty.shop/" id="fb5-button-back">&lt; Back </a>
        <!-- END BACK BUTTON -->                        
      
        <!-- BEGIN CONTAINER BOOK -->
        <div id="fb5-container-book">
     
            <!-- BEGIN deep linking -->  
            <section id="fb5-deeplinking">
              <ul>
                  <li data-address="page1" data-page="1"></li>
                  <li data-address="page2" data-page="2"></li>
                  <li data-address="page3" data-page="3"></li>
                  <li data-address="page4" data-page="4"></li>
                  <li data-address="page5" data-page="5"></li>
                  <li data-address="page6" data-page="6"></li>
                  <li data-address="page7" data-page="7"></li>
                  <li data-address="page8" data-page="8"></li>
                  <li data-address="page9" data-page="9"></li>
                  <li data-address="page10" data-page="10"></li>
                  <li data-address="page11" data-page="11"></li>
                  <li data-address="page12" data-page="12"></li>
				  <li data-address="page13" data-page="13"></li>
				  <li data-address="page14" data-page="14"></li>
				  <li data-address="page15" data-page="15"></li>
              </ul>
            </section>
            <!-- END deep linking -->  
                
            <!-- BEGIN ABOUT -->
            <section id="fb5-about">
            </section>
            <!-- END ABOUT -->
            
            
            <!-- BEGIN LINKS -->
            <section id="links">
            
                    
           
           </section>     
           <!-- END LINKS -->                         
                                      
    
            <!-- BEGIN PAGES -->
            <div id="fb5-book">                       
                                     
                        <!-- begin page 1 -->          
                        <div data-background-image="pages/1.jpg">          
                               
                                     <!-- container page book --> 
                                     <div class="fb5-cont-page-book">
                                     
                                            <!-- gradient for page -->
                                            <div class="fb5-gradient-page"></div>                
                                         
                                            <!-- PDF.js --> 
                                            <canvas id="canv1"></canvas>                                                               
                                           
                                            <!-- description for page --> 
                                            <div class="fb5-page-book">
                                                             
                                            </div> 
                                                      
                                     
                                      </div>
                                      <!-- end container page book --> 
                      
               
                          </div>
                         <!-- end page 1 -->                    
                        
                          
                                     
                         <!-- begin page 2 -->          
                        <div data-background-image="pages/2.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv2"></canvas> 
                                                                              
                               
                                <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                          
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 2 --> 
                          
                        
                      
                                     
                                     
                         <!-- begin page 3 -->         
                        <div data-background-image="pages/3.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv3"></canvas> 
                                                                              
                               
                                 <!-- description for page from WYSWIG --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                         
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 3 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 4 -->          
                        <div data-background-image="pages/4.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>                            
                             
                                <!-- PDF.js --> 
                                <canvas id="canv4"></canvas>                                                                           
                               
                                 <!-- description for page  --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 4 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 5 -->          
                        <div data-background-image="pages/5.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv5"></canvas> 
                                                                              
                               
                                 <!-- description for page from WYSWIG --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                   
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 5 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 6 -->         
                        <div data-background-image="pages/6.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv6"></canvas> 
                                                                              
                               
                                 <!-- description for page from WYSWIG --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                
                                
                              </div>
                              <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 6 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 7 -->          
                        <div data-background-image="pages/7.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv7"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                 
                              </div>
                              <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 7 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 8 -->          
                        <div data-background-image="pages/8.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv8"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                   
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 8 -->               
                      
                                     
                                     
                         <!-- begin page 9 -->          
                        <div data-background-image="pages/9.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
    
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv9"></canvas> 
                                                                              
                               
                                 <!-- description for page  --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                  
                                
                              </div>
                              <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 9 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 10 -->          
                        <div data-background-image="pages/10.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv10"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                                          
                                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 10 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 11 -->          
                        <div data-background-image="pages/11.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv11"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                   
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 11 -->
                          
                        
                      
                                     
                                     
                         <!-- begin page 12 -->          
                        <div data-background-image="pages/12.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv12"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                  
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 12 -->

                         <!-- begin page 13 -->          
                        <div data-background-image="pages/13.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv13"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                  
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 13 -->

                         <!-- begin page 14 -->          
                        <div data-background-image="pages/14.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv14"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                  
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 14 -->

                         <!-- begin page 15 -->          
                        <div data-background-image="pages/15.jpg">
                          
                               
                             <!-- container page book --> 
                             <div class="fb5-cont-page-book">
                             
                                <!-- gradient for page -->
                                <div class="fb5-gradient-page"></div>
                                
                             
                                <!-- PDF.js --> 
                                <canvas id="canv15"></canvas> 
                                                                              
                               
                                 <!-- description for page --> 
                                <div class="fb5-page-book">
                                                 
                                </div> 
                  
                                
                              </div> <!-- end container page book --> 
                                
                        </div>
                        <!-- end page 15 -->
              
            
          
              
            </div>
            <!-- END PAGES -->
            
        </div>
        <!-- END CONTAINER BOOK -->
    
        <!-- BEGIN FOOTER -->
        <div id="fb5-footer">
        
            <div class="fb5-bcg-tools"></div>
            
            
             
            <a id="fb5-logo" target="_blank" href="https://blingbeauty.shop/">
                <img alt="" src="img/logo.png">
                 
            </a>
       
            <div class="fb5-menu" id="fb5-center">
                <ul>
                
                    <!-- icon_home -->
                    <li>
                        <a title="show home page" class="fb5-home"><i class="fa fa-home"></i></a>
                    </li>
                                    
                    
                    <!-- icon download -->
                    <!--li>
                        <a title="download pdf" class="fb5-download" href="img/file.zip"><i class="fa fa-download"></i></a>
                    </li-->
                                
                            
                    <!-- icon arrow left -->
                    <li>
                        <a title="prev page" class="fb5-arrow-left"><i class="fa fa-chevron-left"></i></a>
                    </li>
                                   
                    
                      <!-- icon arrow right -->
                    <li>
                        <a title="next page" class="fb5-arrow-right"><i class="fa fa-chevron-right"></i></a>
                    </li>
                                    
                    
                    <!-- icon_zoom_in -->                     
                    <li>
                        <a title="zoom in" class="fb5-zoom-in"><i class="fa fa-search-plus"></i></a>
                    </li>
                                                
                               
                    
                    <!-- icon_zoom_out -->                 
                    <li>
                        <a title="zoom out" class="fb5-zoom-out"><i class="fa fa-search-minus"></i></a>
                    </li>
                                    
                    
                     <!-- icon_zoom_auto -->
                    <li>
                        <a title="zoom auto" class="fb5-zoom-auto"><i class="fa fa-search"></i></a>
                    </li>
                                    
                               
                    <!-- icon_allpages -->
                    <li>
                        <a title="show all pages" class="fb5-show-all"><i class="fa fa-list"></i></a>
                    </li>
                                                    
                    
                    <!-- icon fullscreen -->                 
                    <li>
                        <a title="full/normal screen" class="fb5-fullscreen"><i class="fa fa-expand"></i></a>
                    </li>
                                    
                  
                    
                </ul>
            </div>
            
            <div class="fb5-menu" id="fb5-right">
                <ul>              
                    <!-- icon page manager -->                 
                    <li class="fb5-goto">
                        <label for="fb5-page-number" id="fb5-label-page-number"></label>
                        <input type="text" id="fb5-page-number" style="width: 25px;"> 
                        <span id="fb5-page-number-two"></span>
                        
                    </li>                
                </ul>
            </div>
            
            
        
        </div>
        <!-- END FOOTER -->
     
        <!-- BEGIN ALL PAGES -->
        <div id="fb5-all-pages" class="fb5-overlay">
    
          <section class="fb5-container-pages">
    
            <div id="fb5-menu-holder">
    
                <ul id="fb5-slider">       	 
                                            
                                             <!-- thumb 1 -->
                                             <li class="1">
                                                  <img alt="" data-src="pages/1_.jpg">
                                              
                                             </li>
                                                                                                                             
                                             <!-- thumb 2 -->
                                             <li class="2">
                                                <img alt="" data-src="pages/2_.jpg">										  
                                             </li>
                                        
                                                
                                             <!-- thumb 3 -->
                                             <li class="3">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/3_.jpg">
                                              
                                             </li>
                                             
                                             
                                             <!-- thumb 4 -->
                                             <li class="4">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/4_.jpg">
                                              
                                             </li>
                                             
                                             
                                             <!-- thumb 5 -->								 	
                                             <li class="5">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/5_.jpg">
                                              
                                             </li>
                                             
                                             <!-- thumb 6 -->
                                             <li class="6">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/6_.jpg">
                                              
                                             </li>
                                             
                                                                             
                                             <!-- thumb 7 -->
                                             <li class="7">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/7_.jpg">
                                              
                                             </li>
                                             
                                                                        
                                             <!-- thumb 8 -->
                                             <li class="8">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/8_.jpg">
                                              
                                             </li>
                                             
                                             <!-- thumb 9 -->
                                             <li class="9">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/9_.jpg">
                                              
                                             </li>
                                             
                                             
                                             <!-- thumb 10 -->
                                             <li class="10">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/10_.jpg">
                                              
                                             </li>

                                             <!-- thumb 11 -->
                                             <li class="11">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/11_.jpg">
                                              
                                             </li>

                                             <!-- thumb 12 -->
                                             <li class="12">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/12_.jpg">
                                              
                                             </li>

                                             <!-- thumb 13 -->
                                             <li class="13">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/13_.jpg">
                                              
                                             </li>

                                             <!-- thumb 14 -->
                                             <li class="14">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/14_.jpg">
                                              
                                             </li>

                                             <!-- thumb 15 -->
                                             <li class="15">
                                                  <!-- img -->
                                                  <img alt="" data-src="pages/15_.jpg">
                                              
                                             </li>


                                            
                                                     
    
                </ul>
            
            </div>
    
        </section>
    
       </div>
        <!-- END ALL PAGES -->           
         
         
         <!-- BEGIN SOUND FOR SHEET  -->
        <audio preload="auto" id="sound_sheet"></audio>  
        <!-- END SOUND FOR SHEET --> 
         
        <!-- BEGIN CLOSE LIGHTBOX  -->
        <div id="fb5-close-lightbox">
         <i class="fa fa-times pull-right"></i>
        </div>  
        <!-- END CLOSE LIGHTBOX -->
    
    
    </div>
    <!-- END STRUCTURE HTML FLIPBOOK -->

     
</div>
<!-- end flipbook -->                                                                                             

<?php } ?>




</body>
</html>