<div id="PaypalModal" class="reveal-modal" data-reveal>
    <h2>Paypal donation</h2>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <fieldset class="phone-form well form-horizontal" style="margin-top: 5px;">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="K2JJ6EQXPLYF4">
    <div align="center"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></div>
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </fieldset>
    </form>
    <a class="close-reveal-modal">&#215;</a>
</div>

<div id="BitcoinModal" class="reveal-modal" data-reveal>
    <h2>Bitcoin donation</h2>
   <form id="makeDonation" action="https://bitpay.com/checkout" method="post" onsubmit="{literal}return bp.validateMobileCheckoutForm($('#makeDonation'));{/literal}">
        <input name="action" type="hidden" value="checkout">
        <fieldset class="phone-form well form-horizontal" style="margin-top: 5px;">
          <ul>
            <li id="orderID" class="control-group">
              <label class="control-label" style="width: 40px">Email:</label>
              <div class="controls" style="/*margin-left: 60px*/">
                <input name="orderID" type="email" class="input input-xlarge" placeholder="Email address (optional)" maxlength=50 autocapitalize=off autocorrect=off><br>
              </div>
            </li>
            <li id="price" class="control-group">
              <label class="control-label" style="width: 40px">Amount:</label>
              <div class="controls" style="/*margin-left: 60px*/">
                <input name="price" type="number" class="noscroll" value="10.00" placeholder="Amount" maxlength="10" min="0.01" step="0.01" style="width: 39%"  />
                <select name="currency" value="" style="width: 49%" >
                  <option value="USD" selected="selected">USD</option>
                  <option value="BTC">BTC</option>
                  <option value="EUR">EUR</option>
                  <option value="GBP">GBP</option>
                  <option value="AUD">AUD</option>
                  <option value="BGN">BGN</option>
                  <option value="BRL">BRL</option>
                  <option value="CAD">CAD</option>
                  <option value="CHF">CHF</option>
                  <option value="CNY">CNY</option>
                  <option value="CZK">CZK</option>
                  <option value="DKK">DKK</option>
                  <option value="HKD">HKD</option>
                  <option value="HRK">HRK</option>
                  <option value="HUF">HUF</option>
                  <option value="IDR">IDR</option>
                  <option value="ILS">ILS</option>
                  <option value="INR">INR</option>
                  <option value="JPY">JPY</option>
                  <option value="KRW">KRW</option>
                  <option value="LTL">LTL</option>
                  <option value="LVL">LVL</option>
                  <option value="MXN">MXN</option>
                  <option value="MYR">MYR</option>
                  <option value="NOK">NOK</option>
                  <option value="NZD">NZD</option>
                  <option value="PHP">PHP</option>
                  <option value="PLN">PLN</option>
                  <option value="RON">RON</option>
                  <option value="RUB">RUB</option>
                  <option value="SEK">SEK</option>
                  <option value="SGD">SGD</option>
                  <option value="THB">THB</option>
                  <option value="TRY">TRY</option>
                  <option value="ZAR">ZAR</option>
                </select/>
              </div>
            </li>
          </ul>
          <br>
          <input type="hidden" name="data" value="rSR1aAb0/5aJJfU7cPI5VK9Law806yjJRkLuhRKxTIHMsOqr+KCKwT14SSipr1gVto7fd8UJH3bsmVlY+OyZJW8r/x1DkWWzqNhunCDgZlFfnVWDJ/t7aFX03Ie+b+aBhvv7GuHfadct9qAkoqpjl7C6WBrQexlAwjTQrFa8XTRrlLMW5ht0cNrMuXINztk6CbSup5F5quf8uCmBu/48Tus8M+sS6dPezwcf+0C6pqrpLFJG/JEsD3NnKKBZp+kx">
          <div style="margin: auto; width: 100%; text-align: center">
            <input name="submit" src="{$project_domain}/{$project_template_folder}/attributes/images/donate-md.png" type="image" style="width: auto" alt="BitPay, the easy way to pay with bitcoins." border="0">
          </div>
        </fieldset>
      </form>
      <a class="close-reveal-modal">&#215;</a>
</div>