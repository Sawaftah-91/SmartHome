<?php
session_start();
include("includes/header.php");  ?>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
  <main class="main" id="top">
  <div class="container" data-layout="container">
    <div class="row flex-center min-vh-100 py-3"> <!-- قللنا padding العمودي -->
      <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <a class="d-flex flex-center mb-4" href="#">
          <img class="me-2" src="<?php echo COMPANY_LOGO; ?>" alt="" width="125" />
        </a>
        <div class="card mb-3">
          <div class="card-body p-4 p-sm-5">
            <div class="row flex-between-center mb-2">
               <?php show_messages(); ?>
              <div class="col-auto">
                <h5 class="lang" data-key="login"></h5>
              </div>
              <div class="col-auto fs--1 text-600">
                  <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-start">
                      <div class="flex-1">
                        <h6 class="fs-0 lang" data-key="isRTL"></h6>
                      </div>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input ms-0" id="mode-rtl" type="checkbox" data-theme-control="isRTL" />
                    </div>
                  </div>
              </div>
            </div>
            <form class="needs-validation" action="<?php echo ACTIONS."login.php"?>" method="post" novalidate>
              <div class="mb-3">
               <input class="form-control lang" type="text" name="username" data-key="username" placeholder="" autocomplete="username" required/>
               <div class="invalid-feedback lang" data-key="invalid_username"></div>
              </div>
              <div class="mb-3">
                <input class="form-control lang" type="password" name="password" data-key="password" placeholder="" autocomplete="current-password" required/>
                <div class="invalid-feedback lang" data-key="invalid_password"></div>
              </div>
              <div class="row flex-between-center">
                <div class="col-auto">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="basic-checkbox" checked="checked" name="remember"/>
                    <label class="form-check-label mb-0 lang" data-key="remember" for="basic-checkbox"></label>
                  </div>
                </div>
                <div class="col-auto">
                  <a class="fs--1 lang" data-key="forgot_password" href="#"></a>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-block w-100 mt-3 lang" data-key="submit" type="submit" name="submit"></button>
              </div>
            </form>
            <div class="d-flex align-items-center justify-content-between mb-2" style="font-size: 0.8rem;">
              <!-- القسم الأيسر -->
              <div class="d-flex align-items-center">
                <div class="avatar-icon rounded-circle bg-light text-primary p-1" style="font-size: 0.8rem; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-database"></i>
                </div>
                <p class="mb-0 ms-2">
                  <strong class="lang text-primary fw-normal" data-key="business_unit"></strong> 
                  <strong class="text-dark fw-bold"><?php echo COMPANY_NAME; ?></strong>
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- شعار المبرمج أسفل البطاقة -->
        <div class="text-center mt-2" style="font-size:12px; color:#666;">
          Developed by 
          <a href="https://yourwebsite.com" target="_blank" class="text-decoration-none text-dark">
            <img src="your_logo.png" alt="Your Logo" class="me-1" style="height:16px; vertical-align:middle;"> YourName
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

  

    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

  <?php include("includes/js_plugin.php");  ?>