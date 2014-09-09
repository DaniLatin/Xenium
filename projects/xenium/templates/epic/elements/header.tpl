<div class="row header">
        <div class="twelve columns header_nav">
        
        <div class="twelve columns">
            
            <div class="logo"><a href="{$project_domain}/{$selected_language}/"><img src="{$project_domain}/{$project_template_folder}/attributes/images/xenium-logo-dark-transparent.png" alt="Xenium" style="border:none" /></a></div>
            
            <ul id="menu-header" class="nav-bar horizontal">
            
             {*<li class="logo"><a href="{$project_domain}"><img src="{$project_domain}/{$project_template_folder}/attributes/images/xenium-logo.png" alt="desc" style="border:none" /></a></li>*}


              {*<li class="has-flyout">
                <a href="#">Example Pages</a><a href="#" class="flyout-toggle"></a>

                <ul class="flyout">
                
                  <li class="has-flyout"><a href="index.html">Homepage</a></li>
                
                  <li class="has-flyout"><a href="blog.html">Blog</a></li>
                  
                  <li class="has-flyout"><a href="blog_single.html">Blog Single Page</a></li>
                  
                  <li class="has-flyout"><a href="products-page.html">Products Page</a></li>

                  <li class="has-flyout"><a href="product-single.html">Product Single</a></li>
                  
                  <li class="has-flyout"><a href="pricing-table.html">Pricing Table</a></li>

                  <li class="has-flyout"><a href="contact.html">Contact Page</a></li>

                </ul>
              </li>*}
              
              <li class=""><a href="{$project_domain}/{$selected_language}/">Home</a></li>

              <li class="">{static_content_url title="About" project_domain="`$project_domain`"}</li>
              
              <li class="">{static_content_url title="Advantages" project_domain="`$project_domain`"}</li>
              
              <li class=""><a href="http://blog.{$main_project_domain}/{$selected_language}/">News / Blog</a></li>
              
              <li class="">{static_content_url title="Contact" project_domain="`$project_domain`"}</li>
              
              {*<li class="follow-us"><span>Follow us on:</span>
                <ul class="">
                    <li class=""><a href="#" title="Facebook"><i class="icon-facebook-rect"></i></a></li>
                    <li class=""><a href="#" title="Twitter"><i class="icon-twitter-bird"></i></a></li>
                    <li class=""><a href="#" title="LinkedIn"><i class="icon-linkedin-rect"></i></a></li>
                    <li class=""><a href="#" title="YouTube"><i class="icon-youtube"></i></a></li>
                </ul>
              </li>
              
              <li class="donate"><span>Make a donation:</span>
                <ul class="">
                    <li class=""><a href="#" title="Paypal"><i class="icon-paypal"></i></a></li>
                </ul>
              </li>*}

            </ul>
            
            <ul class="nav-bar horizontal icon-bar">
                <li class="follow-us"><span>Follow us on:</span>
                <ul class="">
                    <li class=""><a href="https://www.facebook.com/XeniumCMS" title="Facebook" target="_blank"><i class="icon-facebook-rect"></i></a></li>
                    <li class=""><a href="https://plus.google.com/b/108098794844136240603/108098794844136240603/about" title="Google Plus" target="_blank"><i class="icon-googleplus-rect"></i></a></li>
                    <li class=""><a href="https://twitter.com/XeniumCMS" title="Twitter" target="_blank"><i class="icon-twitter-bird"></i></a></li>
                    <li class=""><a href="https://www.linkedin.com/groups/Xenium-8139017" title="LinkedIn" target="_blank"><i class="icon-linkedin-rect"></i></a></li>
                    <li class=""><a href="https://www.youtube.com/channel/UCFJgP6jRVK2Rf9jM0NqPmNg" title="YouTube" target="_blank"><i class="icon-youtube"></i></a></li>
                </ul>
              </li>
              
              <li class="donate"><span>Support us via:</span>
                <ul class="">
                    <li class=""><a href="#" title="Paypal" data-reveal-id="PaypalModal"><i class="icon-paypal"></i></a></li>
                    <li class=""><a href="#" title="Bitcoin" data-reveal-id="BitcoinModal"><i class="icon-bitcoin"></i></a></li>
                </ul>
              </li>
            </ul>
            
            <script type="text/javascript">
           //<![CDATA[
           //$('ul#menu-header').nav-bar();
            //]]>
            </script>
            
            </div>
            
          </div>

          
        </div><!-- END Header -->