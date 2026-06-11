<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
@Description: The code of the released Hospital software, does NOT lie under
GLP (General Public License) But it has proprietary copyrights. The purpose of the
Informing for public that, the Hospital web based mobile responsible application its associated
different roles are protected by the mentioned copyrights. *
@Version: Miracle Hospital - 1.0
@Author: Neoark Software
@Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
@Email: sales@neoarksoftware.com | support@neoarksoftware.com
@website: www.neoarks.com
@Phone: +91-880-090-0164
Date: 21st August, 2023 
-->

<section id="counterSection">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="counter-area">
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="counter-box">
              <div class="counter-no counter">
                <?php if (isset($doctors) && is_array($doctors)):
                        if(count($doctors) > 15):
                          echo count($doctors); 
                        else:
                          echo "15"; 
                        endif;   
                      else :
                        echo "15";
                      endif; 
                ?>
              </div>
              <div class="counter-label">Doctors</div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="counter-box">
              <div class="counter-no counter">
                <?php if (isset($hospital_wards) && is_array($hospital_wards)):
                        if(count($hospital_wards) > 10):
                          echo count($hospital_wards); 
                        else:
                          echo "10"; 
                        endif;   
                      else :
                        echo "10";
                      endif; 
                ?>
              </div>
              <div class="counter-label">Clinic Wards</div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="counter-box">
              <div class="counter-no counter">
                <?php if (isset($hospital_beds) && is_array($hospital_beds)):
                        if(count($hospital_beds) > 30):
                          echo count($hospital_beds); 
                        else:
                          echo "45"; 
                        endif;   
                      else :
                        echo "45";
                      endif; 
                ?>
              </div>
              <div class="counter-label">Hopital Beds</div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="counter-box">
              <div class="counter-no counter">
                <?php if (isset($patients) && is_array($patients)):
                        if(count($patients) > 500):
                          echo count($patients); 
                        else:
                          echo "500"; 
                        endif;   
                      else :
                        echo "500";
                      endif; 
                ?>
              </div>
              <div class="counter-label">Happy Patients</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>