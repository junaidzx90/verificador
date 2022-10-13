<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Verificador
 * @subpackage Verificador/public/partials
 */
?>

<?php 
$loader = '<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
<path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
<path fill="#4c9abe" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
C22.32,8.481,24.301,9.057,26.013,10.047z">
<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.9s" repeatCount="indefinite"></animateTransform>
</path>
</svg>';
?>

<div class="verificador">
    <form action="" method="post">

        <div class="vc_contents verificador_step_1 ">
            <div class="vc_alert danger dnone">
                <p>The discount code is invalid!</p>
            </div>

            <div class="vc_inputs">
                <label for="vccoupon">Coupon<span>*</span></label>
                <input type="text" required placeholder="Coupon Code" id="vccoupon" name="vc_coupon_code">
            </div>
            <div class="vc_subbtn">
                <input type="submit" value="Verify" class="vcbtn verify_btn">
                <div class="vcloader dnone"><?php echo $loader ?></div>
            </div>
        </div>

        <div class="vc_contents verificador_step_2 vcnone">
            <div class="vc_alert success">
                <p>The discount code is valid!</p>
            </div>

            <input type="submit" value="Use Now" class="vcbtn usenowbtn">
        </div>

        <div class="vc_contents verificador_step_3 vcnone">
            <div class="vc_inputs">
                <div class="vcinput">
                    <label for="username">User Name<span>*</span></label>
                    <input type="text" required id="username" name="vc_username" placeholder="Your full name">
                </div>
                <div class="vcinput">
                    <label for="useremail">User Email<span>*</span></label>
                    <input type="email" required id="useremail" name="vc_useremail" placeholder="Your email">
                </div>
                <div class="vcinput">
                    <label for="store_manager_name">Store manager name<span>*</span></label>
                    <input type="text" required id="store_manager_name" name="vc_store_manager_name" placeholder="Manager name">
                </div>
            </div>
            
            <div class="vc_subbtn">
                <input type="submit" name="verificador_send" value="Send" class="vcbtn vcsend_btn">
                <div class="vcloader dnone"><?php echo $loader ?></div>
            </div>
        </div>

    </form>
</div>